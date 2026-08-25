@extends('admin_panel.layout.app')

@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid py-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Bookings Management</h4>
                        <p class="text-muted mb-0 small">View and manage your product bookings</p>
                    </div>
                    <div>
                        @can('bookings.create')
                            <a href="{{ route('sale.add') }}?type=booking" class="btn btn-primary px-4 shadow-sm fw-medium align-items-center gap-2">
                                <i class="fas fa-plus"></i> Add Booking
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 mb-4">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table id="bookings-table" class="table table-hover align-middle datanew" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3 ps-3 rounded-start text-secondary fw-semibold text-uppercase small">ID</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small">Customer</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small">Reference</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small">Product</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small text-center">Qty</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small text-end">Price</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small text-center">Discount</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small text-end">Total</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small">Status</th>
                                        <th class="py-3 text-secondary fw-semibold text-uppercase small">Booking Date</th>
                                        <th class="py-3 pe-3 rounded-end text-secondary fw-semibold text-uppercase small text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr class="border-bottom-0">
                                            <td class="ps-3 fw-bold text-muted font-monospace">#{{ $booking->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-info-subtle text-info me-2 fw-bold d-flex align-items-center justify-content-center rounded-circle"
                                                        style="width: 32px; height: 32px; font-size: 14px; background-color: #e0f2fe; color: #0369a1;">
                                                        {{ strtoupper(substr(optional($booking->customer_relation)->customer_name ?? 'C', 0, 1)) }}
                                                    </div>
                                                    <span class="fw-medium text-dark">{{ optional($booking->customer_relation)->customer_name ?? 'N/A' }}</span>
                                                </div>
                                            </td>
                                            <td class="font-monospace text-dark">{{ $booking->reference ?? '-' }}</td>
                                            <td class="text-muted small">
                                                @foreach ($booking->items as $item)
                                                    {{ optional($item->product)->item_name ?? 'N/A' }} <br>
                                                @endforeach
                                            </td>
                                            <td class="text-center font-monospace small">
                                                @foreach ($booking->items as $item)
                                                    {{ $item->qty }} <br>
                                                @endforeach
                                            </td>
                                            <td class="text-end font-monospace small">
                                                @foreach ($booking->items as $item)
                                                    {{ number_format($item->price, 2) }} <br>
                                                @endforeach
                                            </td>
                                            <td class="text-center text-danger small">
                                                @foreach ($booking->items as $item)
                                                    {{ $item->discount_percent }}% <br>
                                                @endforeach
                                            </td>
                                            <td class="text-end text-success fw-bold font-monospace">
                                                {{ number_format($booking->total_net, 2) }}
                                            </td>
                                            <td>
                                                @if ($booking->sale_status === 'booked')
                                                    <span class="badge badge-warning text-dark border border-warning"><i class="fas fa-bookmark me-1"></i>Booked</span>
                                                @elseif ($booking->sale_status === 'posted')
                                                    <span class="badge badge-success border border-success"><i class="fas fa-check-circle me-1"></i>Confirmed</span>
                                                @else
                                                    <span class="badge badge-secondary border border-secondary">{{ ucfirst($booking->sale_status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap small text-muted">
                                                {{ $booking->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="pe-3 text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light border dropdown-toggle"
                                                        type="button" data-toggle="dropdown" data-display="static" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v small"></i> Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right border-0 shadow-lg rounded-3">
                                                        @if ($booking->sale_status === 'booked')
                                                            <li>
                                                                <form action="{{ route('sales.confirm', $booking->id) }}" method="POST" class="d-inline confirm-booking-form">
                                                                    @csrf
                                                                    <button type="button" class="dropdown-item d-flex align-items-center gap-2 py-2 text-success confirm-booking-btn">
                                                                        <i class="fas fa-check-circle fa-fw text-success"></i> Confirm Booking
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.edit', $booking->id) }}">
                                                                    <i class="fas fa-edit text-primary fa-fw"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.invoice', $booking->id) }}" target="_blank">
                                                                    <i class="fas fa-file-invoice text-info fa-fw"></i> View Invoice
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.invoice', $booking->id) }}" target="_blank">
                                                                    <i class="fas fa-file-invoice text-info fa-fw"></i> View Invoice
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.dc', $booking->id) }}" target="_blank">
                                                                    <i class="fas fa-shipping-fast text-warning fa-fw"></i> DC Receipt
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('sales.receipt', $booking->id) }}" target="_blank">
                                                                    <i class="fas fa-receipt text-success fa-fw"></i> View Receipt
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            if ($.fn.DataTable.isDataTable('.datanew')) {
                $('.datanew').DataTable().destroy();
            }
            $('.datanew').DataTable({
                "pageLength": 10,
                "order": [],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search bookings..."
                },
                "dom": "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            });

            // Confirm Booking SweetAlert Action
            $(document).on('click', '.confirm-booking-btn', function(e) {
                e.preventDefault();
                let form = $(this).closest("form");

                Swal.fire({
                    title: "Confirm Booking?",
                    text: "This will convert the booking into a confirmed sale, deduct stock, and update customer ledger.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, Confirm it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
