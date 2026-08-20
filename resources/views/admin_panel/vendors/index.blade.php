@extends('admin_panel.layout.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .vendor-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    /* Page Header */
    .vendor-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .vendor-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .vendor-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    /* Action Buttons Hub */
    .vendor-action-hub {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .btn-vendor-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        color: #ffffff !important;
        border: none;
        padding: 9px 18px;
        font-size: 0.86rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.22);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-vendor-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
    }
    .btn-vendor-sub {
        background: #ffffff;
        color: #475569 !important;
        border: 1.5px solid #cbd5e1;
        padding: 9px 16px;
        font-size: 0.84rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-vendor-sub:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a !important;
    }

    /* Stat Cards */
    .vendor-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
    }
    .vendor-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .vendor-stat-val {
        font-weight: 800;
        font-size: 1.25rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .vendor-stat-lbl {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Card & Table */
    .vendor-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .vendor-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .vendor-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .datanew {
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    .datanew thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 20px;
    }
    .datanew tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .datanew tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Avatar Circle */
    .vendor-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 800;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Mobile Cards View */
    .mobile-vendor-cards {
        display: none;
        padding: 14px;
        flex-direction: column;
        gap: 12px;
    }
    .vendor-mcard {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }
    .vendor-mcard-hdr {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .vendor-mcard-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f172a;
    }
    .vendor-mcard-details {
        display: flex;
        flex-direction: column;
        gap: 6px;
        font-size: 0.82rem;
        color: #475569;
        padding: 8px 0;
    }
    .vendor-mcard-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
    }

    /* Responsive Breakpoints */
    @media (max-width: 768px) {
        .vendor-header {
            padding: 16px;
        }
        .vendor-action-hub {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .btn-vendor-primary, .btn-vendor-sub {
            width: 100%;
            justify-content: center;
            height: 40px;
            font-size: 0.78rem;
            padding: 6px;
        }
        .vendor-table-wrap {
            display: none !important;
        }
        .mobile-vendor-cards {
            display: flex;
        }
    }
</style>

<div class="vendor-page container-fluid px-3 px-md-4 pt-3">
    
    {{-- Header Row --}}
    <div class="vendor-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="vendor-title"><i class="fas fa-truck-loading text-primary me-2"></i>Vendor Management</h3>
            <div class="vendor-sub">Manage suppliers, opening balances, ledgers and payments</div>
        </div>
        <div class="vendor-action-hub">
            @can('vendors.create')
                <button type="button" class="btn-vendor-primary" id="btnAddVendor">
                    <i class="fas fa-plus"></i> Add Vendor
                </button>
            @endcan
            <a href="{{ url('vendors-ledger') }}" class="btn-vendor-sub">
                <i class="fas fa-book me-1"></i> Ledger
            </a>
            <a href="{{ route('vendor.payments') }}" class="btn-vendor-sub">
                <i class="fas fa-money-check-alt me-1"></i> Payments
            </a>
            <a href="{{ url('vendor/bilties') }}" class="btn-vendor-sub">
                <i class="fas fa-shipping-fast me-1"></i> Bilty
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="vendor-stat-card">
                <div class="vendor-stat-icon"><i class="fas fa-store"></i></div>
                <div>
                    <div class="vendor-stat-val">{{ count($vendors) }}</div>
                    <div class="vendor-stat-lbl">Total Vendors</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="vendor-card">
        <div class="vendor-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Vendors</div>
            <div class="text-muted small">Showing {{ count($vendors) }} entries</div>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 mx-4 mt-3 mb-0">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Desktop Table View --}}
        <div class="vendor-table-wrap p-3">
            <table class="table table-hover align-middle datanew">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th class="text-start">Vendor Name</th>
                        <th class="text-start">Contact Phone</th>
                        <th class="text-start">Opening Balance</th>
                        <th class="text-start">Address</th>
                        <th class="text-end pe-4" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $key => $v)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $key + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="vendor-avatar">
                                        {{ strtoupper(substr($v->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $v->name }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($v->phone)
                                    <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-phone-alt me-1 text-muted"></i>{{ $v->phone }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="fw-bold text-success">
                                Rs. {{ number_format((float) $v->opening_balance, 2) }}
                            </td>
                            <td class="text-secondary small">{{ Str::limit($v->address, 35) ?: '—' }}</td>
                            <td class="text-end pe-4">
                                @include('admin_panel.partials.action_buttons', [
                                    'editRoute' => 'javascript:void(0)',
                                    'deleteRoute' => url('vendor/delete/' . $v->id),
                                    'editIsLink' => false,
                                    'permissions' => [
                                        'edit' => 'vendors.edit',
                                        'delete' => 'vendors.delete',
                                    ],
                                    'dataId' => $v->id,
                                ])
                            </td>
                            <!-- Hidden inputs for reliable JS edit -->
                            <input type="hidden" class="v-name" value="{{ $v->name }}">
                            <input type="hidden" class="v-phone" value="{{ $v->phone }}">
                            <input type="hidden" class="v-balance" value="{{ $v->opening_balance }}">
                            <input type="hidden" class="v-address" value="{{ $v->address }}">
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View (< 768px) --}}
        <div class="mobile-vendor-cards">
            @foreach ($vendors as $key => $v)
                <div class="vendor-mcard">
                    <div class="vendor-mcard-hdr">
                        <div class="d-flex align-items-center gap-2">
                            <div class="vendor-avatar">
                                {{ strtoupper(substr($v->name, 0, 1)) }}
                            </div>
                            <div class="vendor-mcard-title">{{ $v->name }}</div>
                        </div>
                        <span class="badge bg-light text-dark border">#{{ $key + 1 }}</span>
                    </div>

                    <div class="vendor-mcard-details">
                        @if ($v->phone)
                            <div><i class="fas fa-phone me-2 text-primary"></i> <strong>Phone:</strong> {{ $v->phone }}</div>
                        @endif
                        <div><i class="fas fa-wallet me-2 text-success"></i> <strong>Opening Balance:</strong> <span class="text-success fw-bold">Rs. {{ number_format((float) $v->opening_balance, 2) }}</span></div>
                        @if ($v->address)
                            <div><i class="fas fa-map-marker-alt me-2 text-muted"></i> <strong>Address:</strong> {{ Str::limit($v->address, 50) }}</div>
                        @endif
                    </div>

                    <div class="vendor-mcard-actions">
                        @include('admin_panel.partials.action_buttons', [
                            'editRoute' => 'javascript:void(0)',
                            'deleteRoute' => url('vendor/delete/' . $v->id),
                            'editIsLink' => false,
                            'permissions' => [
                                'edit' => 'vendors.edit',
                                'delete' => 'vendors.delete',
                            ],
                            'dataId' => $v->id,
                        ])
                    </div>

                    <!-- Hidden inputs for mobile JS edit -->
                    <input type="hidden" class="v-name" value="{{ $v->name }}">
                    <input type="hidden" class="v-phone" value="{{ $v->phone }}">
                    <input type="hidden" class="v-balance" value="{{ $v->opening_balance }}">
                    <input type="hidden" class="v-address" value="{{ $v->address }}">
                </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Add/Edit Vendor Modal --}}
<div class="modal fade" id="vendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <form action="{{ url('vendor/store') }}" method="POST" id="vendorForm">
                @csrf
                <input type="hidden" id="vendor_id" name="id">

                <div class="modal-header bg-white px-4 py-3 border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="modalTitle"><i class="fas fa-truck text-primary me-2"></i>New Vendor</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="vname" class="form-label fw-semibold text-dark small">Vendor Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control px-3 py-2" name="name" id="vname" placeholder="Enter vendor name..." required style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="vphone" class="form-label fw-semibold text-dark small">Phone Number</label>
                            <input type="text" class="form-control px-3 py-2" name="phone" id="vphone" placeholder="Phone number..." style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                        <div class="col-md-6">
                            <label for="opening_balance" class="form-label fw-semibold text-dark small">Opening Balance <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control px-3 py-2" name="opening_balance" id="opening_balance" placeholder="0.00" required style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="vaddress" class="form-label fw-semibold text-dark small">Full Address</label>
                        <textarea class="form-control px-3 py-2" name="address" id="vaddress" placeholder="Enter address details..." style="border-radius: 10px; border: 1.5px solid #cbd5e1; height: 90px;"></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" data-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                    @can('vendors.create')
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" style="border-radius: 8px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">
                            <i class="fas fa-check me-1"></i> Save Vendor
                        </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('.datanew')) {
            $('.datanew').DataTable().destroy();
        }
        $('.datanew').DataTable({
            "pageLength": 10,
            "order": [],
            "language": {
                "search": "",
                "searchPlaceholder": "Search vendors..."
            },
            "dom": "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });

        // Open Modal for Create
        $('#btnAddVendor').click(function() {
            $('#vendorForm')[0].reset();
            $('#vendor_id').val('');
            $('#modalTitle').html('<i class="fas fa-truck text-primary me-2"></i>New Vendor');
            $('#opening_balance').prop('readonly', false);
            $('#vendorModal').modal('show');
        });

        // Edit Vendor
        $(document).on('click', '.edit-btn', function() {
            const $container = $(this).closest('tr, .vendor-mcard');
            const id = $(this).data('id');

            const name = $container.find('.v-name').val();
            const phone = $container.find('.v-phone').val();
            const balance = $container.find('.v-balance').val();
            const address = $container.find('.v-address').val();

            $('#vendor_id').val(id);
            $('#vname').val(name);
            $('#vphone').val(phone);
            $('#opening_balance').val(balance).prop('readonly', false);
            $('#vaddress').val(address);

            $('#modalTitle').html('<i class="fas fa-edit text-primary me-2"></i>Edit Vendor');
            $('#vendorModal').modal('show');
        });
    });
</script>
@endsection
