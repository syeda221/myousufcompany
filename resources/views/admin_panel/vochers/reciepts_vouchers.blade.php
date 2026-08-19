@extends('admin_panel.layout.app')
@section('content')
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/css/bootstrap-icons.min.css') }}">

   
    <style>
        :root {
            --rv-primary: #4f46e5;
            --rv-bg: #f8fafc;
            --rv-border: #e2e8f0;
            --rv-text: #1e293b;
            --rv-muted: #64748b;
        }

        .rv-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
            border: 1px solid var(--rv-border);
            padding: 24px;
        }

        .rv-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--rv-border);
        }

        .rv-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--rv-text);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rv-title i {
            color: var(--rv-primary);
            background: #e0e7ff;
            padding: 8px 10px;
            border-radius: 10px;
        }

        .rv-section-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--rv-primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 16px 0 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .rv-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--rv-border);
        }

        .rv-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--rv-muted);
            margin-bottom: 4px;
        }

        .rv-input {
            background: #fff;
            border: 1px solid var(--rv-border);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            color: var(--rv-text);
            transition: all 0.2s ease;
            width: 100%;
        }
        .rv-input:focus {
            border-color: var(--rv-primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
            outline: none;
        }
        .rv-input::placeholder { color: #cbd5e1; }

        select.rv-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .rv-table {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--rv-border);
        }
        .rv-table thead th {
            background: #1e293b;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 10px 12px;
            text-align: center;
            border: none;
        }
        .rv-table tbody td {
            padding: 8px 10px;
            vertical-align: middle;
            border-color: var(--rv-border);
        }
        .rv-table tfoot td {
            padding: 10px 12px;
            background: #f1f5f9;
            font-weight: 700;
        }

        .btn-rv-primary {
            background: var(--rv-primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 28px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 2px 5px rgba(79,70,229,0.3);
            transition: all 0.2s;
        }
        .btn-rv-primary:hover {
            background: #4338ca;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(79,70,229,0.4);
        }

        .btn-rv-secondary {
            background: #f1f5f9;
            color: var(--rv-text);
            border: 1px solid var(--rv-border);
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-rv-secondary:hover { background: #e2e8f0; color: var(--rv-text); }

        .btn-rv-add {
            background: #ecfdf5;
            color: #059669;
            border: 1px dashed #10b981;
            border-radius: 8px;
            padding: 6px 16px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-rv-add:hover { background: #d1fae5; }

        .balance-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
        }
        .balance-dr { background: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }
        .balance-cr { background: #f0fdf4; color: #16a34a; border: 1px solid #86efac; }

        /* Select2 RV Custom Styling */
        .select2-container--default .select2-selection--single {
            background-color: #fff !important;
            border: 1px solid var(--rv-border) !important;
            border-radius: 8px !important;
            height: 38px !important;
            padding: 4px 8px !important;
            font-size: 0.9rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px !important;
            color: var(--rv-text) !important;
            padding-left: 2px !important;
            font-weight: 600;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 8px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--rv-primary) !important;
            box-shadow: 0 0 0 3px rgba(79,70,229,0.1) !important;
            outline: none !important;
        }
        .select2-dropdown {
            border: 1px solid var(--rv-border) !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important;
            z-index: 9999 !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--rv-border) !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            outline: none !important;
        }

        /* Disable number input spin buttons / up-down arrows */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] { 
            -moz-appearance: textfield; 
            appearance: textfield;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner" style="padding: 10px;">
            <div class="container-fluid p-0">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" style="border-radius: 10px;">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('store_rec_vochers') }}" method="POST" id="receiptForm">
                    @csrf

                    <div class="rv-card">
                        <!-- Header -->
                        <div class="rv-header">
                            <div class="rv-title">
                                <i class="bi bi-receipt-cutoff"></i> Receipt Voucher
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('all_recepit_vochers') }}" class="btn btn-rv-secondary">
                                    <i class="bi bi-list-ul me-1"></i> All Vouchers
                                </a>
                            </div>
                        </div>

                        <!-- Row 1: Voucher Info -->
                        <div class="rv-section-label">Voucher Details</div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-2">
                                <label class="rv-label">RVID</label>
                                <input type="text" class="rv-input" style="background: #f1f5f9;" name="rvid"
                                    value="{{ $nextRvid }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="rv-label">Receipt Date</label>
                                <input type="date" name="receipt_date" class="rv-input"
                                    value="{{ now()->toDateString() }}">
                            </div>
                            <div class="col-md-2">
                                <label class="rv-label">Entry Date</label>
                                <input type="date" name="entry_date" class="rv-input"
                                    value="{{ now()->toDateString() }}">
                            </div>
                            <div class="col-md-6">
                                <label class="rv-label">Remarks <small class="text-muted fw-normal">(Optional)</small></label>
                                <input type="text" name="remarks" class="rv-input" id="remarks" placeholder="Auto-generated if left blank">
                            </div>
                        </div>

                        <!-- Row 2: Party Selection -->
                        <div class="rv-section-label">Received From</div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="rv-label">Type</label>
                                <select name="vendor_type" class="rv-input" id="partyType">
                                    <option value="customer" selected>Customer</option>
                                    <option value="walkin">Walk-in</option>
                                    <option value="vendor">Vendor</option>/
                                    @foreach ($AccountHeads as $head)
                                        <option value="{{ $head->id }}">{{ $head->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="rv-label">Party / Account</label>
                                <select name="vendor_id" class="rv-input" id="partyId">
                                    <option disabled selected>Select Party</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="rv-label">Mobile</label>
                                <input type="text" name="tel" id="tel" class="rv-input" style="background: #f1f5f9;" readonly>
                            </div>
                             <div class="col-2">
        <label class="rv-label">Current Balance</label>
        <div id="balanceDisplay" class="balance-badge balance-dr p-2 text-center" style="width:100%;">20000.00 Dr</div>
        <input type="hidden" id="openingBal">
    </div>
                        </div>

                        <!-- Payment Rows -->
                        <div class="rv-section-label">Payment Details</div>
                        <div class="rv-table">
                            <table class="table table-bordered align-middle mb-0" id="voucherTable">
                                <thead>
                                    <tr>
                                        <th style="width: 28%;">Account Head</th>
                                        <th style="width: 32%;">Account</th>
                                        <th style="width: 24%;">Amount</th>
                                        <th style="width: 16%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="row_account_head[]" class="rv-input rowAccountHead">
                                                <option value="">Select Head</option>
                                                @foreach ($AccountHeads as $head)
                                                    <option value="{{ $head->id }}">{{ $head->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="row_account_id[]" class="rv-input rowAccountSub">
                                                <option disabled selected>Select Account</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="amount[]"
                                                class="rv-input text-end fw-bold amount" placeholder="0.00"
                                                style="font-size: 1rem;">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm removeRow" title="Remove">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold" style="font-size: 1rem; vertical-align: middle;">Total Amount:</td>
                                        <td style="vertical-align: middle;">
                                            <input type="text" name="total_amount"
                                                class="rv-input text-end fw-bold" id="totalAmount" readonly
                                                value="0.00" style="background: #f0fdf4; border-color: #86efac; font-size: 1.1rem; color: #16a34a;">
                                        </td>
                                        <td class="text-center p-1" style="vertical-align: middle;">
                                            <button type="submit" class="btn btn-rv-primary w-100 py-2 px-2 fw-bold d-flex align-items-center justify-content-center" id="btnSavePrint" style="font-size: 0.88rem; white-space: nowrap; height: 38px; border-radius: 8px;">
                                                <i class="bi bi-printer me-1"></i> Save (Print)
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <button type="button" class="btn btn-rv-add mt-3" id="addNewRow">
                            <i class="bi bi-plus-circle me-1"></i> Add Another Account
                        </button>

                        {{-- Hidden fields for backward compatibility --}}
                        <input type="hidden" name="narration_id[]" value="">
                        <input type="hidden" name="narration_text[]" value="">
                        <input type="hidden" name="reference_no[]" value="">
                        <input type="hidden" name="discount_value[]" value="0">
                        <input type="hidden" name="rate[]" value="0">

                    </div><!-- /rv-card -->

                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            // Initialize Searchable Party Select2
            function initPartySelect2() {
                $('#partyId').select2({
                    placeholder: 'Search by Name or Code...',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 0,
                    ajax: {
                        url: '{{ route("party.list") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                type: $('#partyType').val(),
                                search: params.term || ''
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.id,
                                        text: item.text,
                                        party: item
                                    };
                                })
                            };
                        },
                        cache: false
                    },
                    templateResult: function(item) {
                        if (item.loading) return item.text;
                        if (!item.party) return item.text;
                        const p = item.party;
                        const code = p.customer_id || p.account_code || '';
                        const name = p.customer_name || p.name || p.title || item.text;
                        const bal = p.closing_balance !== undefined ? parseFloat(p.closing_balance) : 0;
                        const balFormatted = Math.abs(bal).toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 2});
                        const balType = bal >= 0 ? (p.customer_name ? 'Dr' : 'Cr') : (p.customer_name ? 'Cr' : 'Dr');
                        
                        return $(`<div class="d-flex justify-content-between align-items-center py-1">
                            <div>
                                <div class="fw-bold text-dark">${name}</div>
                                <div class="d-flex gap-2 align-items-center">
                                    ${code ? '<small class="text-primary fw-semibold">' + code + '</small>' : ''}
                                    ${p.mobile ? '<small class="text-muted"><i class="bi bi-telephone me-1"></i>' + p.mobile + '</small>' : ''}
                                </div>
                            </div>
                            <div class="text-end ms-2">
                                <span class="badge ${bal >= 0 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'} border px-2 py-1" style="font-size: 0.75rem;">
                                    Bal: ${balFormatted} ${balType}
                                </span>
                            </div>
                        </div>`);
                    },
                    templateSelection: function(item) {
                        if (!item.party) return item.text;
                        const p = item.party;
                        const name = p.customer_name || p.name || p.title || item.text;
                        const code = p.customer_id || p.account_code ? ` [${p.customer_id || p.account_code}]` : '';
                        const bal = p.closing_balance !== undefined ? parseFloat(p.closing_balance) : 0;
                        const balFormatted = Math.abs(bal).toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 2});
                        const balType = bal >= 0 ? (p.customer_name ? 'Dr' : 'Cr') : (p.customer_name ? 'Cr' : 'Dr');
                        return `${name}${code} (Bal: ${balFormatted} ${balType})`;
                    }
                });
            }

            initPartySelect2();

            // Header Party Type Change → Reset & Clear
            $('#partyType').on('change', function() {
                $('#partyId').val(null).trigger('change');
                $('#tel').val('');
                updateBalance(0);
            });

            // Party selected → load details & update balance
            $('#partyId').on('select2:select', function(e) {
                let item = e.params.data.party || e.params.data;
                if (!item) return;

                $('#tel').val(item.mobile || item.phone || '');
                let bal = parseFloat(item.closing_balance) || 0;
                updateBalance(bal);

                // Auto-set remarks
                let partyName = item.customer_name || item.name || item.title || '';
                if (!$('#remarks').val() && partyName) {
                    $('#remarks').val('Receipt from ' + partyName);
                }
            });

            $('#partyId').on('select2:clear', function() {
                $('#tel').val('');
                updateBalance(0);
            });

            function updateBalance(bal) {
                let $badge = $('#balanceDisplay');
                let formatted = Math.abs(bal).toFixed(2);
                if (bal >= 0) {
                    $badge.removeClass('balance-cr').addClass('balance-dr');
                    $badge.html(formatted + ' <small>Dr</small>');
                } else {
                    $badge.removeClass('balance-dr').addClass('balance-cr');
                    $badge.html(formatted + ' <small>Cr</small>');
                }
            }

            // Row Logic (destination accounts)
            $(document).on('change', '.rowAccountHead', function() {
                let headId = $(this).val();
                let $subSelect = $(this).closest('tr').find('.rowAccountSub');

                if (!headId) {
                    $subSelect.html('<option value="">Select Account</option>');
                    return;
                }

                $.get('{{ url("get-accounts-by-head") }}/' + headId, function(res) {
                    let html = '<option value="">Select Account</option>';
                    res.forEach(acc => {
                        html += `<option value="${acc.id}">${acc.title}</option>`;
                    });
                    $subSelect.html(html);
                });
            });

            // Totals
            function calculateTotal() {
                let total = 0;
                $('.amount').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#totalAmount').val(total.toFixed(2));
            }
            $(document).on('input', '.amount', function() {
                calculateTotal();
            });

            // Prevent arrow up/down keys and scroll wheel from changing amount / number values
            $(document).on('keydown', 'input[type="number"], .amount', function(e) {
                if (e.key === 'ArrowUp' || e.key === 'ArrowDown' || e.keyCode === 38 || e.keyCode === 40) {
                    e.preventDefault();
                }
            });

            $(document).on('wheel', 'input[type="number"], .amount', function(e) {
                $(this).blur();
            });

            // Add Row
            $('#addNewRow').on('click', function() {
                let newRow = `
                <tr>
                    <td>
                        <select name="row_account_head[]" class="rv-input rowAccountHead">
                            <option value="">Select Head</option>
                            @foreach ($AccountHeads as $head)
                                <option value="{{ $head->id }}">{{ $head->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="row_account_id[]" class="rv-input rowAccountSub">
                            <option disabled selected>Select Account</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="amount[]" class="rv-input text-end fw-bold amount" placeholder="0.00" style="font-size: 1rem;">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm removeRow"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `;
                $('#voucherTable tbody').append(newRow);
            });

            $(document).on('click', '.removeRow', function() {
                if ($('#voucherTable tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                    calculateTotal();
                }
            });

            // Handle Save (Print) Form Submission
            $('#receiptForm').on('submit', function(e) {
                e.preventDefault();

                let partyType = $('#partyType').val();
                let partyId = $('#partyId').val();

                if (!partyId && (partyType === 'customer' || partyType === 'vendor' || partyType === 'walkin')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Party Required',
                        text: 'Please select a Party / Account first.'
                    });
                    return;
                }

                let totalAmt = parseFloat($('#totalAmount').val()) || 0;
                if (totalAmt <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Amount Required',
                        text: 'Please enter a valid Amount greater than 0.'
                    });
                    return;
                }

                let $btn = $('#btnSavePrint');
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(res) {
                        if (res && res.success) {
                            // Redirect directly to the thermal invoice print page
                            window.location.href = res.print_url;
                        } else {
                            $btn.prop('disabled', false).html('<i class="bi bi-printer me-1"></i> Save (Print)');
                            Swal.fire('Error', res.message || 'Failed to save voucher.', 'error');
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html('<i class="bi bi-printer me-1"></i> Save (Print)');
                        let msg = 'Failed to save voucher.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
            });
        });
    </script>
@endsection
