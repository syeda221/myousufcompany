@extends('admin_panel.layout.app')
@section('content')
    <style>
        .btn-sm i.fa-toggle-on {
            color: green;
            font-size: 20px;
        }

        .btn-sm i.fa-toggle-off {
            color: gray;
            font-size: 20px;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h3>Customer List</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('customers.template') }}" class="btn btn-outline-secondary" title="Download blank CSV template">
                            <i class="fa fa-file-excel me-1"></i> Template
                        </a>
                        <a href="{{ route('customers.export') }}" class="btn btn-success" title="Export customers to CSV">
                            <i class="fa fa-download me-1"></i> Export CSV
                        </a>
                        @can('customers.create')
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#importCustomerModal" id="openImportCustomerModalBtn">
                                <i class="fa fa-upload me-1"></i> Import CSV
                            </button>
                            <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Add New Customer</a>
                        @endcan
                        @can('customers.view')
                            <a href="{{ route('customers.ledger') }}" class="btn btn-info text-white">Ledger</a>
                            <a href="{{ route('customer.payments') }}" class="btn btn-info text-white">Payment</a>
                        @endcan
                        <a href="{{ route('customers.inactive') }}" class="btn btn-secondary">Inactive Customers</a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fa fa-check-circle me-1"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fa fa-exclamation-circle me-1"></i>{{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Credit Limit</th>
                            <th>Status</th>
                            <th>Source</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->customer_id }}</td>
                                <td>{{ $customer->customer_name }}</td>
                                <td>{{ $customer->mobile }}</td>
                                <td>{{ $customer->balance_range == 0 ? 'Unlimited' : number_format($customer->balance_range, 0) }}</td>
                                <td>{{ $customer->status }}</td>
                                <td>
                                    @php
                                        $source = $customer->source ?? 'Manual';
                                    @endphp
                                    @if($source === 'Website')
                                        <span class="badge bg-success">Website</span>
                                    @elseif($source === 'Both')
                                        <span class="badge bg-info text-dark">Both</span>
                                    @else
                                        <span class="badge bg-secondary">Manual</span>
                                    @endif
                                </td>
                                <td>
                                    @include('admin_panel.partials.action_buttons', [
                                        'editRoute' => route('customers.edit', $customer->id),
                                        'deleteRoute' => route('customers.destroy', $customer->id),
                                        'editIsLink' => true,
                                        'permissions' => [
                                            'edit' => 'customers.edit',
                                            'delete' => 'customers.delete',
                                        ],
                                        'dataId' => $customer->id,
                                    ])

                                    @can('customers.edit')
                                        <a href="{{ route('customers.toggleStatus', $customer->id) }}"
                                            class="btn btn-sm {{ $customer->status === 'active' ? 'btn-dark' : 'btn-secondary' }}"
                                            title="Toggle Status">
                                            <i
                                                class="fa-solid {{ $customer->status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </a>
                                    @endcan
                                    @can('customers.view')
                                        <a href="{{ route('customer.payments') }}" class="btn btn-sm btn-info">Payments</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         CUSTOMER IMPORT MODAL
    ══════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="importCustomerModal" tabindex="-1" role="dialog" aria-labelledby="importCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px; overflow:hidden;">
                <div class="modal-header" style="background: linear-gradient(135deg,#4f46e5,#7c3aed); color:#fff; border-bottom: none;">
                    <div>
                        <h5 class="modal-title fw-bold" id="importCustomerModalLabel">
                            <i class="fa fa-file-upload me-2"></i>Import Customers from CSV
                        </h5>
                        <small style="color: rgba(255,255,255,0.85);">New customers will be created. Existing ones (matched by Customer Code) will be updated.</small>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity:.8; text-shadow:none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" style="background:#f8fafc;">
                    <form action="{{ route('customers.import.validate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(session('error'))
                            <div class="alert alert-danger mb-4"><i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}</div>
                        @endif
                        <div class="alert alert-info d-flex gap-2 align-items-start mb-4" style="font-size:.85rem;">
                            <i class="fa fa-info-circle fs-5 mt-1 flex-shrink-0"></i>
                            <div>
                                <strong>How to use:</strong><br>
                                1. Download the <a href="{{ route('customers.template') }}" class="alert-link text-primary fw-bold">CSV Template</a> first.<br>
                                2. Fill in your customer details in Excel and save as <strong>CSV</strong>.<br>
                                3. Upload here to validate and preview the changes.<br>
                                4. Confirm the preview to import the customers.
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">Import Mode</label>
                            <select name="import_mode" class="form-control form-select" required>
                                <option value="create">Create (Add new customers &amp; update existing)</option>
                                <option value="update_only">Update Only (Update existing customers only)</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <div class="form-check custom-control custom-checkbox">
                                <input class="form-check-input custom-control-input" type="checkbox" id="autoCreateCustomerMaster" name="auto_create" value="1" checked>
                                <label class="form-check-label custom-control-label fw-bold" for="autoCreateCustomerMaster">
                                    Auto-create missing Customer Type &amp; Region (Zone)
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">If disabled, missing Customer Types or Zones will throw validation errors.</small>
                        </div>
                        <div class="form-group mb-4">
                            <label class="fw-bold">Upload CSV File</label>
                            <input type="file" name="csv_file" class="form-control p-1" accept=".csv,.txt" required>
                            <small class="text-muted">Max 5 MB (.csv format)</small>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-arrow-right me-1"></i> Next: Validate &amp; Preview
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
$(document).ready(function () {
    $('#openImportCustomerModalBtn').on('click', function () {
        $('#importCustomerModal').modal('show');
    });
});
</script>
@endsection
