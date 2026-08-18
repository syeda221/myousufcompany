@extends('admin_panel.layout.app')
@section('content')

@if (session('success'))
    <script>
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#4f46e5'
        });
    });
    </script>
@endif

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .brand-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    /* Page Header */
    .brand-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .brand-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .brand-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    /* Stat Cards */
    .brand-stat-card {
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
    .brand-stat-icon {
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
    .brand-stat-val {
        font-weight: 800;
        font-size: 1.25rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .brand-stat-lbl {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Primary Gradient Button */
    .btn-brand-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        color: #ffffff !important;
        border: none;
        padding: 10px 20px;
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
    .btn-brand-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
    }

    /* Card & Table */
    .brand-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .brand-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .brand-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    #default-datatable {
        width: 100% !important;
        margin-bottom: 0 !important;
    }
    #default-datatable thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 20px;
    }
    #default-datatable tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    #default-datatable tbody tr:hover {
        background-color: #f8fafc;
    }

    /* ID Badge */
    .brand-id-badge {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-family: monospace;
    }

    /* Mobile Cards View */
    .mobile-brand-cards {
        display: none;
        padding: 14px;
        flex-direction: column;
        gap: 12px;
    }
    .brand-mcard {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }
    .brand-mcard-hdr {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .brand-mcard-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f172a;
    }
    .brand-mcard-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
    }

    /* Responsive Breakpoints */
    @media (max-width: 768px) {
        .brand-card-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 16px;
        }
        .btn-brand-primary {
            width: 100%;
            justify-content: center;
            height: 42px;
        }
        .brand-table-wrap {
            display: none !important;
        }
        .mobile-brand-cards {
            display: flex;
        }
    }
</style>

<div class="brand-page container-fluid px-3 px-md-4 pt-3">
    
    {{-- Header Row --}}
    <div class="brand-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="brand-title"><i class="fas fa-copyright text-primary me-2"></i>Brand Management</h3>
            <div class="brand-sub">Manage manufacturer brands and product lines</div>
        </div>
        @can('brands.create')
            <button type="button" class="btn-brand-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                <i class="fas fa-plus"></i> Create Brand
            </button>
        @endcan
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="brand-stat-card">
                <div class="brand-stat-icon"><i class="fas fa-award"></i></div>
                <div>
                    <div class="brand-stat-val">{{ count($Brand) }}</div>
                    <div class="brand-stat-lbl">Total Brands</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="brand-card">
        <div class="brand-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Brands</div>
            <div class="text-muted small">Showing {{ count($Brand) }} entries</div>
        </div>

        {{-- Desktop Table View --}}
        <div class="brand-table-wrap">
            <table id="default-datatable" class="table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 90px;">Id</th>
                        <th class="text-start">Brand Name</th>
                        <th class="text-end pe-4" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($Brand as $company)
                        <tr>
                            <td class="text-center id"><span class="brand-id-badge">#{{ $company->id }}</span></td>
                            <td class="text-start name fw-semibold text-dark">{{ $company->name }}</td>
                            <td class="text-end pe-4">
                                @include('admin_panel.partials.action_buttons', [
                                    'editRoute' => route('store.Brand'),
                                    'deleteRoute' => route('delete.Brand', $company->id),
                                    'editIsLink' => false,
                                    'permissions' => [
                                        'edit' => 'brands.edit',
                                        'delete' => 'brands.delete',
                                    ],
                                    'dataId' => $company->id,
                                ])
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards View (< 768px) --}}
        <div class="mobile-brand-cards">
            @foreach ($Brand as $company)
                <div class="brand-mcard">
                    <div class="brand-mcard-hdr">
                        <span class="brand-id-badge">#{{ $company->id }}</span>
                        <span class="badge bg-light text-dark border">Brand</span>
                    </div>
                    <div class="brand-mcard-title mb-2">{{ $company->name }}</div>
                    <div class="brand-mcard-actions">
                        @include('admin_panel.partials.action_buttons', [
                            'editRoute' => route('store.Brand'),
                            'deleteRoute' => route('delete.Brand', $company->id),
                            'editIsLink' => false,
                            'permissions' => [
                                'edit' => 'brands.edit',
                                'delete' => 'brands.delete',
                            ],
                            'dataId' => $company->id,
                        ])
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Add/Edit Brand Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-white px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="exampleModalLabel"><i class="fas fa-award text-primary me-2"></i><span id="modalTitleText">Add Brand</span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="myform" action="{{ route('store.Brand') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark small">Brand Title <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control px-3 py-2" id="name" placeholder="Enter brand title..." required style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" data-dismiss="modal" style="border-radius: 8px;">Close</button>
                    @can('brands.create')
                        <button type="submit" class="btn btn-primary px-4 fw-bold save-btn" style="border-radius: 8px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">
                            <i class="fas fa-check me-1"></i> Save Brand
                        </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/js/mycode.js') }}"></script>
<script>
    // Fix ARIA focus warning on modal close
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    $(document).on('submit', '.myform', function(e) {
        e.preventDefault();
        var formdata = new FormData(this);
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        $(this).find(':submit').attr('disabled', true);
        myAjax(url, formdata, method);
    });

    $(document).on('click', '.edit-btn', function() {
        var tr = $(this).closest("tr, .brand-mcard");
        var id = tr.find(".id, .brand-id-badge").text().replace('#', '').trim();
        var name = tr.find(".name, .brand-mcard-title").text().trim();
        $('#id').val(id);
        $('#name').val(name);
        $('#modalTitleText').text('Edit Brand');
        $("#exampleModal").modal("show");
    });

    $('#reset').on('click', function() {
        $('#id').val('');
        $('#name').val('');
        $('#modalTitleText').text('Add Brand');
    });

    $(document).ready(function() {
        $('#default-datatable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, 'desc']],
            "language": {
                "search": "Search Brand:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>
@endsection
