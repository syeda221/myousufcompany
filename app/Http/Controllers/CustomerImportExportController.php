<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLedger;
use App\Models\CustomerType;
use App\Models\JournalEntry;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CustomerImportExportController extends Controller
{
    // ──────────────────────────────────────────────────────────
    //  TEMPLATE – CSV with exact fields from the Customer creation form
    // ──────────────────────────────────────────────────────────
    public function template()
    {
        $headers = $this->csvHeaders();

        $callback = function () use ($headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            // Row 1: Example Existing Customer / Full Data
            fputcsv($handle, [
                'CUST-0001',
                'Ali Traders',
                'Wholesale',
                '0300-1234567',
                'North Zone',
                'Shop #12, Market Road, Lahore',
                '5000',
                '100000',
                'Monday',
            ]);

            // Row 2: Example New Customer (Code left blank for auto-generation)
            fputcsv($handle, [
                '',
                'Ahmed Khan',
                'Retail',
                '0321-9876543',
                'South Zone',
                'Plaza 4, Saddar, Karachi',
                '0',
                '0',
                'Thursday',
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="customers_template.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    //  EXPORT – all customers as CSV
    // ──────────────────────────────────────────────────────────
    public function export(Request $request)
    {
        $query = Customer::query()->orderBy('id');

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('customer_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->get();
        $zonesMap  = Zone::pluck('zone', 'id')->toArray();
        $headers   = $this->csvHeaders();

        $callback = function () use ($customers, $zonesMap, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($customers as $c) {
                // Resolve zone name if stored as zone ID
                $zoneVal = $c->zone;
                if (is_numeric($zoneVal) && isset($zonesMap[$zoneVal])) {
                    $zoneVal = $zonesMap[$zoneVal];
                }

                fputcsv($handle, [
                    $c->customer_id,
                    $c->customer_name,
                    $c->customer_type ?? '',
                    $c->mobile ?? '',
                    $zoneVal ?? '',
                    $c->address ?? '',
                    $c->opening_balance ?? 0,
                    $c->balance_range ?? 0,
                    $c->reminder_day ?? '',
                ]);
            }

            fclose($handle);
        };

        $filename = 'customers_export_' . now()->format('Y-m-d_H-i') . '.csv';

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    // ──────────────────────────────────────────────────────────
    //  IMPORT STEP 1: VALIDATE & PREVIEW
    // ──────────────────────────────────────────────────────────
    public function importValidate(Request $request)
    {
        $request->validate([
            'csv_file'    => 'required|file|mimes:csv,txt|max:5120',
            'import_mode' => 'required|in:create,update_only',
        ]);

        $mode       = $request->input('import_mode');
        $autoCreate = $request->has('auto_create');

        $file   = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $headerRow = fgetcsv($handle);
        if (!$headerRow) {
            return redirect()->back()->with('error', 'CSV file is empty or invalid.');
        }

        $headerMap = [];
        foreach ($headerRow as $i => $col) {
            $headerMap[strtolower(trim($col))] = $i;
        }

        // Required Columns & Aliases
        $requiredCols = [
            'customer_name' => ['customer name', 'customer_name', 'name', 'full name', 'fullname'],
        ];

        foreach ($requiredCols as $key => $possibleNames) {
            $found = false;
            foreach ($possibleNames as $pName) {
                if (isset($headerMap[$pName])) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return redirect()->back()->with('error', "Required column matching '{$possibleNames[0]}' not found. Please use the downloaded template.");
            }
        }

        $get = function (array $row, $possibleNames, $default = '') use ($headerMap) {
            if (!is_array($possibleNames)) {
                $possibleNames = [$possibleNames];
            }
            foreach ($possibleNames as $pName) {
                $key = strtolower(trim($pName));
                if (isset($headerMap[$key]) && isset($row[$headerMap[$key]])) {
                    return trim($row[$headerMap[$key]]);
                }
            }
            return $default;
        };

        $existingCustomerTypes = CustomerType::pluck('name', 'id')
            ->map(fn($name) => strtolower(trim($name)))
            ->toArray();

        $existingZones = Zone::pluck('zone', 'id')
            ->map(fn($name) => strtolower(trim($name)))
            ->toArray();

        $existingCustomerCodes = Customer::pluck('id', 'customer_id')->toArray();

        $customersToProcess = [];
        $errors = [];
        $rowNum = 1;

        $masterDataToCreate = [
            'customer_types' => [],
            'zones'          => [],
        ];

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (empty(array_filter($row))) {
                continue;
            }

            $custCode = $get($row, ['customer code', 'customer_code', 'customer id', 'customer_id', 'code', 'id']);
            $custName = $get($row, $requiredCols['customer_name']);
            $custType = $get($row, ['customer type', 'customer_type', 'type']);
            $mobile   = $get($row, ['mobile', 'phone', 'contact']);
            $zoneName = $get($row, ['region (zone)', 'region', 'zone', 'region_zone']);
            $address  = $get($row, ['address', 'customer address']);
            $opening  = (float) $get($row, ['opening balance', 'opening_balance', 'opening balance (dr)', 'balance'], 0);
            $limit    = (float) $get($row, ['credit limit', 'balance_range', 'credit_limit', 'limit'], 0);
            $reminder = $get($row, ['payment reminder day', 'reminder day', 'reminder_day']);

            if (empty($custName)) {
                $errors[] = ["row" => $rowNum, "msg" => "Customer Name is required."];
                continue;
            }

            // Check Customer Type
            if (!empty($custType)) {
                $typeKey = strtolower($custType);
                if (!in_array($typeKey, $existingCustomerTypes)) {
                    if ($autoCreate) {
                        if (!in_array($custType, $masterDataToCreate['customer_types'])) {
                            $masterDataToCreate['customer_types'][] = $custType;
                        }
                    } else {
                        $errors[] = ["row" => $rowNum, "msg" => "Customer Type '{$custType}' does not exist."];
                    }
                }
            }

            // Check Zone
            if (!empty($zoneName)) {
                $zoneKey = strtolower($zoneName);
                if (!in_array($zoneKey, $existingZones) && !isset($existingZones[$zoneName])) {
                    if ($autoCreate) {
                        if (!in_array($zoneName, $masterDataToCreate['zones'])) {
                            $masterDataToCreate['zones'][] = $zoneName;
                        }
                    } else {
                        $errors[] = ["row" => $rowNum, "msg" => "Region/Zone '{$zoneName}' does not exist."];
                    }
                }
            }

            $refKey = !empty($custCode) ? $custCode : ('AUTO_GEN_' . $rowNum);

            $customersToProcess[$refKey] = [
                'row'             => $rowNum,
                'customer_id'     => $custCode,
                'customer_name'   => $custName,
                'customer_type'   => $custType,
                'mobile'          => $mobile,
                'zone'            => $zoneName,
                'address'         => $address,
                'opening_balance' => max(0, $opening),
                'balance_range'   => max(0, $limit),
                'reminder_day'    => $reminder,
            ];
        }

        fclose($handle);

        // Compute summary counts
        $customersToCreate = 0;
        $customersToUpdate = 0;
        $ignored = 0;
        $validPayload = [];

        foreach ($customersToProcess as $ref => $cData) {
            $isUpdate = !empty($cData['customer_id']) && isset($existingCustomerCodes[$cData['customer_id']]);

            if ($isUpdate) {
                $customersToUpdate++;
            } else {
                if ($mode === 'update_only') {
                    $ignored++;
                    continue; // Skip creating in update only mode
                }
                $customersToCreate++;
            }

            $validPayload[$ref] = $cData;
        }

        // Store payload in session
        Session::put('customer_import_payload', [
            'mode'          => $mode,
            'auto_create'   => $autoCreate,
            'customers'     => $validPayload,
            'master_data'   => $masterDataToCreate,
            'errors'        => $errors,
            'preview_stats' => [
                'customers_create' => $customersToCreate,
                'customers_update' => $customersToUpdate,
                'ignored'          => $ignored,
                'master_create'    => count($masterDataToCreate['customer_types']) + count($masterDataToCreate['zones']),
            ],
        ]);

        return redirect()->route('customers.import.preview');
    }

    // ──────────────────────────────────────────────────────────
    //  IMPORT STEP 2: SHOW PREVIEW
    // ──────────────────────────────────────────────────────────
    public function importPreview()
    {
        if (!Session::has('customer_import_payload')) {
            return redirect()->route('customers.index')->with('error', 'Import session expired or invalid.');
        }

        $payload = Session::get('customer_import_payload');
        return view('admin_panel.customers.import_preview', compact('payload'));
    }

    // ──────────────────────────────────────────────────────────
    //  IMPORT STEP 3: CONFIRM & IMPORT
    // ──────────────────────────────────────────────────────────
    public function importConfirm()
    {
        if (!Session::has('customer_import_payload')) {
            return redirect()->route('customers.index')->with('error', 'Import session expired.');
        }

        $payload            = Session::get('customer_import_payload');
        $customersToProcess = $payload['customers'];
        $autoCreate         = $payload['auto_create'];

        $createdCustomers = 0;
        $updatedCustomers = 0;

        DB::beginTransaction();
        try {
            // 1. Auto-create Master Data
            $typeMap = CustomerType::pluck('name', 'name')
                ->mapWithKeys(fn($item, $key) => [strtolower($key) => $item])
                ->toArray();

            $zoneMap = Zone::pluck('id', 'zone')
                ->mapWithKeys(fn($item, $key) => [strtolower($key) => $item])
                ->toArray();

            if ($autoCreate) {
                foreach ($payload['master_data']['customer_types'] as $typeName) {
                    $key = strtolower(trim($typeName));
                    if (!isset($typeMap[$key])) {
                        $ct = CustomerType::create(['name' => $typeName]);
                        $typeMap[$key] = $ct->name;
                    }
                }

                foreach ($payload['master_data']['zones'] as $zoneName) {
                    $key = strtolower(trim($zoneName));
                    if (!isset($zoneMap[$key])) {
                        $z = Zone::create(['zone' => $zoneName]);
                        $zoneMap[$key] = $z->id;
                    }
                }
            }

            // 2. Process Customers
            $maxId = Customer::max('id') ?? 0;

            foreach ($customersToProcess as $ref => $cData) {
                $customer = null;
                if (!empty($cData['customer_id'])) {
                    $customer = Customer::where('customer_id', $cData['customer_id'])->first();
                }

                // Resolve Zone ID
                $zoneId = null;
                if (!empty($cData['zone'])) {
                    $zKey = strtolower(trim($cData['zone']));
                    if (isset($zoneMap[$zKey])) {
                        $zoneId = $zoneMap[$zKey];
                    } elseif (is_numeric($cData['zone']) && Zone::where('id', $cData['zone'])->exists()) {
                        $zoneId = $cData['zone'];
                    }
                }

                // Resolve Customer Type
                $custTypeName = $cData['customer_type'];
                if (!empty($custTypeName)) {
                    $tKey = strtolower(trim($custTypeName));
                    if (isset($typeMap[$tKey])) {
                        $custTypeName = $typeMap[$tKey];
                    }
                }

                $openingBalance = (float) ($cData['opening_balance'] ?? 0);

                if ($customer) {
                    // Update Customer
                    $customer->update([
                        'customer_name'   => $cData['customer_name'],
                        'customer_type'   => $custTypeName ?: $customer->customer_type,
                        'mobile'          => $cData['mobile'] ?: $customer->mobile,
                        'zone'            => $zoneId ?: $customer->zone,
                        'address'         => $cData['address'] ?: $customer->address,
                        'opening_balance' => $openingBalance,
                        'balance_range'   => $cData['balance_range'],
                        'reminder_day'    => $cData['reminder_day'] ?: $customer->reminder_day,
                    ]);

                    $this->syncOpeningBalance($customer, $openingBalance);
                    $updatedCustomers++;
                } else {
                    // Create New Customer
                    $maxId++;
                    $actualCode = !empty($cData['customer_id'])
                        ? $cData['customer_id']
                        : ('CUST-' . str_pad($maxId, 4, '0', STR_PAD_LEFT));

                    // In case code collision occurs
                    if (Customer::where('customer_id', $actualCode)->exists()) {
                        $actualCode = 'CUST-' . str_pad($maxId + rand(100, 999), 4, '0', STR_PAD_LEFT);
                    }

                    $customer = Customer::create([
                        'customer_id'     => $actualCode,
                        'customer_name'   => $cData['customer_name'],
                        'customer_type'   => $custTypeName ?: 'Retail',
                        'mobile'          => $cData['mobile'] ?? null,
                        'zone'            => $zoneId,
                        'address'         => $cData['address'] ?? null,
                        'opening_balance' => $openingBalance,
                        'balance_range'   => $cData['balance_range'] ?? 0,
                        'reminder_day'    => $cData['reminder_day'] ?? null,
                        'status'          => 'active',
                        'source'          => 'Manual',
                    ]);

                    $this->syncOpeningBalance($customer, $openingBalance);
                    $createdCustomers++;
                }
            }

            DB::commit();
            Session::forget('customer_import_payload');

            return redirect()->route('customers.index')->with(
                'success',
                "Import completed successfully. {$createdCustomers} customers created, {$updatedCustomers} customers updated."
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Import Error: ' . $e->getMessage());
            return redirect()->route('customers.index')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────
    //  Helper: Synchronize opening balance in CustomerLedger and JournalEntry
    // ──────────────────────────────────────────────────────────
    private function syncOpeningBalance(Customer $customer, float $newOpening)
    {
        try {
            $balanceService = app(\App\Services\BalanceService::class);
            $journalService = app(\App\Services\JournalEntryService::class);
            $arId           = $balanceService->getAccountsReceivableId();
            $entryDate      = $customer->created_at ? $customer->created_at->format('Y-m-d') : now()->format('Y-m-d');

            // 1. Find existing Opening Balance Journal Entry
            $obJournal = JournalEntry::where(function ($q) use ($customer) {
                $q->where('source_type', Customer::class)->where('source_id', $customer->id);
            })
            ->where(function ($q) {
                $q->where('description', 'Opening Balance')
                  ->orWhere('description', 'LIKE', 'Opening Balance%');
            })
            ->first();

            if (!$obJournal) {
                $obJournal = JournalEntry::where('party_type', Customer::class)
                    ->where('party_id', $customer->id)
                    ->where(function ($q) {
                        $q->where('description', 'Opening Balance')
                          ->orWhere('description', 'LIKE', 'Opening Balance%');
                    })
                    ->first();
            }

            if ($newOpening > 0) {
                if ($obJournal) {
                    $diff = $newOpening - (float) $obJournal->debit;
                    if ($diff != 0) {
                        $account = \App\Models\Account::find($obJournal->account_id ?: $arId);
                        if ($account) {
                            $account->current_balance = ($account->current_balance ?? 0) + $diff;
                            $account->save();
                        }
                        $obJournal->debit       = $newOpening;
                        $obJournal->credit      = 0;
                        $obJournal->account_id  = $obJournal->account_id ?: $arId;
                        $obJournal->party_type  = Customer::class;
                        $obJournal->party_id    = $customer->id;
                        $obJournal->source_type = Customer::class;
                        $obJournal->source_id   = $customer->id;
                        $obJournal->save();
                    }
                } else {
                    $journalService->recordEntry(
                        $customer,
                        $arId,
                        $newOpening, // Debit (Asset)
                        0,           // Credit
                        'Opening Balance',
                        $entryDate,
                        $customer
                    );
                }
            } else {
                if ($obJournal) {
                    $account = \App\Models\Account::find($obJournal->account_id);
                    if ($account) {
                        $account->current_balance = ($account->current_balance ?? 0) - (float) $obJournal->debit + (float) $obJournal->credit;
                        $account->save();
                    }
                    $obJournal->delete();
                }
            }

            // 2. Sync CustomerLedger
            $obLedger = CustomerLedger::where('customer_id', $customer->id)
                ->where(function ($q) {
                    $q->where('previous_balance', 0)->where('opening_balance', '>', 0)
                      ->orWhere('description', 'Opening Balance');
                })
                ->first();

            if ($newOpening > 0) {
                if ($obLedger) {
                    $obLedger->update([
                        'opening_balance' => $newOpening,
                        'closing_balance' => $newOpening,
                    ]);
                } else {
                    CustomerLedger::create([
                        'customer_id'      => $customer->id,
                        'admin_or_user_id' => Auth::id() ?? 1,
                        'previous_balance' => 0,
                        'opening_balance'  => $newOpening,
                        'closing_balance'  => $newOpening,
                        'description'      => 'Opening Balance',
                        'created_at'       => $customer->created_at ?? now(),
                    ]);
                }
            } else {
                if ($obLedger) {
                    $obLedger->delete();
                }
            }
        } catch (\Exception $e) {
            Log::error('Customer syncOpeningBalance Error: ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────
    //  Helper: Canonical CSV column headers (Matching active form fields)
    // ──────────────────────────────────────────────────────────
    private function csvHeaders(): array
    {
        return [
            'Customer Code',
            'Customer Name',
            'Customer Type',
            'Mobile',
            'Region (Zone)',
            'Address',
            'Opening Balance',
            'Credit Limit',
            'Payment Reminder Day',
        ];
    }
}
