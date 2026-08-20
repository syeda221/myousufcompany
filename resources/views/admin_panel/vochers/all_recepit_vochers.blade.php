@extends('admin_panel.layout.app')

@section('content')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/css/bootstrap-icons.min.css') }}">

    
    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Receipts Vouchers</h4>
                        <p class="text-muted mb-0 small">View and manage all receipts vouchers</p>
                    </div>
                    @can('receipts.voucher.create')
                        <a class="btn btn-primary shadow-sm fw-bold d-inline-flex align-items-center" href="{{ route('recepit_vochers') }}" style="height: 38px; border-radius: 6px;">
                            <i class="fas fa-plus mr-1" style="margin-right: 5px;"></i> Add Receipts Voucher
                        </a>
                    @endcan
                </div>
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body">
                        <div class="table-responsive mt-2 mb-4">
                            <table id="example" class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Voucher No</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Party / Account</th>
                                        <th>Remarks</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-center">Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receipts as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="fw-bold text-primary">{{ $item->voucher_no }}</span>
                                            </td>
                                            <td>{{ $item->date ? $item->date->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-info text-dark">{{ ucfirst($item->payment_from ?? 'Receipt') }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $item->party_name }}</strong>
                                                <small class="d-block text-muted">{{ $item->type_label }}</small>
                                            </td>
                                            <td>{{ Str::limit($item->remarks, 50) }}</td>
                                            <td class="text-end fw-bold">{{ number_format($item->total_amount, 2) }}</td>
                                            <td class="text-center">
                                                @if ($item->status == 'posted')
                                                    <span class="badge bg-success">Posted</span>
                                                @elseif($item->status == 'draft')
                                                    <span class="badge bg-secondary">Draft</span>
                                                @else
                                                    <span class="badge bg-danger">{{ ucfirst($item->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <a href="{{ route('print', $item->id) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary" title="Print">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                    @can('receipts.voucher.delete')
                                                    <form action="{{ route('receipt_vouchers.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable({
                order: [[0, 'desc']]
            });
        }

        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this Receipt Voucher?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
