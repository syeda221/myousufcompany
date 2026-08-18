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

    .subcat-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    /* Page Header */
    .subcat-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .subcat-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .subcat-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    /* Stat Cards */
    .subcat-stat-card {
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
    .subcat-stat-icon {
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
    .subcat-stat-val {
        font-weight: 800;
        font-size: 1.25rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .subcat-stat-lbl {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Primary Gradient Button */
    .btn-subcat-primary {
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
    .btn-subcat-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
    }

    /* Card & Table */
    .subcat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .subcat-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .subcat-table-wrap {
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

    /* Badges */
    .subcat-id-badge {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-family: monospace;
    }
    .parent-cat-badge {
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid #e0e7ff;
    }

    /* Mobile Cards View */
    .mobile-subcat-cards {
        display: none;
        padding: 14px;
        flex-direction: column;
        gap: 12px;
    }
    .subcat-mcard {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }
    .subcat-mcard-hdr {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .subcat-mcard-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #0f172a;
    }
    .subcat-mcard-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 12px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
    }

    /* Responsive Breakpoints */
    @media (max-width: 768px) {
        .subcat-card-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 16px;
        }
        .btn-subcat-primary {
            width: 100%;
            justify-content: center;
            height: 42px;
        }
        .subcat-table-wrap {
            display: none !important;
        }
        .mobile-subcat-cards {
            display: flex;
        }
    }
</style>

<div class="subcat-page container-fluid px-3 px-md-4 pt-3">
    
    {{-- Header Row --}}
    <div class="subcat-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="subcat-title"><i class="fas fa-sitemap text-primary me-2"></i>Subcategory Management</h3>
            <div class="subcat-sub">Organize sub-items under main product categories</div>
        </div>
        @can('subcategories.create')
            <button type="button" class="btn-subcat-primary" data-toggle="modal" data-target="#exampleModal" id="reset">
                <i class="fas fa-plus"></i> Create Subcategory
            </button>
        @endcan
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="subcat-stat-card">
                <div class="subcat-stat-icon"><i class="fas fa-cubes"></i></div>
                <div>
                    <div class="subcat-stat-val">{{ count($subcategory) }}</div>
                    <div class="subcat-stat-lbl">Total Subcategories</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="subcat-card">
        <div class="subcat-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Subcategories</div>
            <div class="text-muted small">Showing {{ count($subcategory) }} entries</div>
        </div>

        {{-- Desktop Table View --}}
        <div class="subcat-table-wrap">
            <table id="default-datatable" class="table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 90px;">Id</th>
                        <th class="text-start">Subcategory Name</th>
                        <th class="text-start">Parent Category</th>
                        <th class="text-end pe-4" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subcategory as $company)
                        <tr>
                            <td class="text-center id"><span class="subcat-id-badge">#{{ $company->id }}</span></td>
                            <td class="text-start name fw-semibold text-dark">{{ $company->name }}</td>
                            <td class="text-start cat-name">
                                <span class="parent-cat-badge"><i class="fas fa-folder me-1"></i>{{ $company->category->name ?? 'Unassigned' }}</span>
                            </td>
                            <td class="text-end pe-4">
                                @include('admin_panel.partials.action_buttons', [
                                    'editRoute' => route('store.subcategory'),
                                    'deleteRoute' => route('delete.subcategory', $company->id),
                                    'editIsLink' => false,
                                    'permissions' => [
                                        'edit' => 'subcategories.edit',
                                        'delete' => 'subcategories.delete',
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
        <div class="mobile-subcat-cards">
            @foreach ($subcategory as $company)
                <div class="subcat-mcard">
                    <div class="subcat-mcard-hdr">
                        <span class="subcat-id-badge">#{{ $company->id }}</span>
                        <span class="parent-cat-badge"><i class="fas fa-folder me-1"></i>{{ $company->category->name ?? 'Unassigned' }}</span>
                    </div>
                    <div class="subcat-mcard-title mb-2">{{ $company->name }}</div>
                    <div class="subcat-mcard-actions">
                        @include('admin_panel.partials.action_buttons', [
                            'editRoute' => route('store.subcategory'),
                            'deleteRoute' => route('delete.subcategory', $company->id),
                            'editIsLink' => false,
                            'permissions' => [
                                'edit' => 'subcategories.edit',
                                'delete' => 'subcategories.delete',
                            ],
                            'dataId' => $company->id,
                        ])
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Add/Edit Subcategory Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-white px-4 py-3 border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="exampleModalLabel"><i class="fas fa-sitemap text-primary me-2"></i><span id="modalTitleText">Add Subcategory</span></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="myform" action="{{ route('store.subcategory') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="edit_id" id="id" />
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-dark small">Subcategory Title <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control px-3 py-2" id="name" placeholder="Enter subcategory title..." required style="border-radius: 10px; border: 1.5px solid #cbd5e1;" />
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-semibold text-dark small">Parent Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select px-3 py-2" required style="border-radius: 10px; border: 1.5px solid #cbd5e1;">
                            <option value="" disabled selected>Select Parent Category...</option>
                            @foreach ($category as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-outline-secondary px-4 fw-semibold" data-dismiss="modal" style="border-radius: 8px;">Close</button>
                    @can('subcategories.create')
                        <button type="submit" class="btn btn-primary px-4 fw-bold save-btn" style="border-radius: 8px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">
                            <i class="fas fa-check me-1"></i> Save Subcategory
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
        var tr = $(this).closest("tr, .subcat-mcard");
        var id = tr.find(".id, .subcat-id-badge").text().replace('#', '').trim();
        var name = tr.find(".name, .subcat-mcard-title").text().trim();
        var catName = tr.find(".cat-name, .parent-cat-badge").text().trim();

        $('#id').val(id);
        $('#name').val(name);
        
        // Select category by matching text if possible
        if (catName) {
            $("#category_id option").filter(function() {
                return $(this).text().trim() === catName;
            }).prop('selected', true);
        }

        $('#modalTitleText').text('Edit Subcategory');
        $("#exampleModal").modal("show");
    });

    $('#reset').on('click', function() {
        $('#id').val('');
        $('#name').val('');
        $('#category_id').val('');
        $('#modalTitleText').text('Add Subcategory');
    });

    $(document).ready(function() {
        $('#default-datatable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "order": [[0, 'desc']],
            "language": {
                "search": "Search Subcategory:",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
</script>
@endsection
