@extends('admin_panel.layout.app')
@section('title', 'Import Customers Preview')

@section('content')
<div class="content-wrapper text-sm">
    <div class="content-header pb-2">
        <div class="container-fluid">
            <div class="row align-items-center mb-1">
                <div class="col-sm-6">
                    <h5 class="m-0 text-dark fw-bold">Import Customers (Preview)</h5>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left"></i> Cancel</a>
                    @if(count($payload['customers']) > 0)
                    <form action="{{ route('customers.import.confirm') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Confirm &amp; Import</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-sm bg-white p-3 rounded mb-3 border">
                        <div class="d-flex align-items-center">
                            <span class="info-box-icon bg-success text-white p-3 rounded mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fa fa-user-plus fa-lg"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Customers to Create</span><br>
                                <span class="info-box-number fw-bold fs-4">{{ $payload['preview_stats']['customers_create'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-sm bg-white p-3 rounded mb-3 border">
                        <div class="d-flex align-items-center">
                            <span class="info-box-icon bg-info text-white p-3 rounded mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fa fa-user-edit fa-lg"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Customers to Update</span><br>
                                <span class="info-box-number fw-bold fs-4">{{ $payload['preview_stats']['customers_update'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-sm bg-white p-3 rounded mb-3 border">
                        <div class="d-flex align-items-center">
                            <span class="info-box-icon bg-secondary text-white p-3 rounded mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fa fa-database fa-lg"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Master Data to Auto-create</span><br>
                                <span class="info-box-number fw-bold fs-4">{{ $payload['preview_stats']['master_create'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box shadow-sm bg-white p-3 rounded mb-3 border">
                        <div class="d-flex align-items-center">
                            <span class="info-box-icon bg-warning text-dark p-3 rounded mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                <i class="fa fa-ban fa-lg"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text text-muted text-uppercase fw-bold" style="font-size:0.75rem;">Ignored / Skipped</span><br>
                                <span class="info-box-number fw-bold fs-4">{{ $payload['preview_stats']['ignored'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($payload['errors']) && count($payload['errors']) > 0)
            <div class="alert alert-danger shadow-sm">
                <strong><i class="fa fa-times-circle"></i> Validation Errors ({{ count($payload['errors']) }}):</strong>
                <ul class="mb-0 mt-2" style="max-height: 150px; overflow-y: auto;">
                    @foreach($payload['errors'] as $error)
                        <li>Row {{ $error['row'] }}: {{ $error['msg'] }}</li>
                    @endforeach
                </ul>
                <div class="mt-2 text-sm fw-bold">Please note: Rows with errors will be skipped if you proceed.</div>
            </div>
            @endif

            @if(count($payload['master_data']['customer_types']) > 0 || count($payload['master_data']['zones']) > 0)
            <div class="alert alert-warning shadow-sm">
                <strong><i class="fa fa-exclamation-triangle"></i> Notice:</strong> The following master data is missing and will be auto-created:<br>
                @if(count($payload['master_data']['customer_types']) > 0)
                    <strong>Customer Types:</strong> {{ implode(', ', $payload['master_data']['customer_types']) }}<br>
                @endif
                @if(count($payload['master_data']['zones']) > 0)
                    <strong>Regions / Zones:</strong> {{ implode(', ', $payload['master_data']['zones']) }}<br>
                @endif
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="card-title fw-bold m-0">Data Preview</h5>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-bordered mb-0" style="font-size:0.85rem;">
                        <thead class="bg-light">
                            <tr>
                                <th>Status</th>
                                <th>Customer Code</th>
                                <th>Customer Name</th>
                                <th>Customer Type</th>
                                <th>Mobile</th>
                                <th>Region (Zone)</th>
                                <th>Address</th>
                                <th class="text-end">Opening Balance</th>
                                <th class="text-end">Credit Limit</th>
                                <th>Reminder Day</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payload['customers'] as $ref => $cData)
                                @php
                                    $exists = !empty($cData['customer_id']) && App\Models\Customer::where('customer_id', $cData['customer_id'])->exists();
                                @endphp
                                <tr>
                                    <td>
                                        @if($exists)
                                            <span class="badge bg-info text-white">Update</span>
                                        @else
                                            <span class="badge bg-success text-white">Create</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ !empty($cData['customer_id']) ? $cData['customer_id'] : '(Auto-generate)' }}</strong></td>
                                    <td>{{ $cData['customer_name'] }}</td>
                                    <td>{{ $cData['customer_type'] ?: '-' }}</td>
                                    <td>{{ $cData['mobile'] ?: '-' }}</td>
                                    <td>{{ $cData['zone'] ?: '-' }}</td>
                                    <td>{{ $cData['address'] ?: '-' }}</td>
                                    <td class="text-end">{{ number_format($cData['opening_balance'], 2) }}</td>
                                    <td class="text-end">{{ $cData['balance_range'] == 0 ? 'Unlimited' : number_format($cData['balance_range'], 2) }}</td>
                                    <td>{{ $cData['reminder_day'] ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">No valid data found to import.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
