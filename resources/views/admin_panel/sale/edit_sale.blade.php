@extends('admin_panel.layout.app')

@section('content')
        <link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- Loader Overlay -->
    <div id="pageLoader"
        class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column gap-3 justify-content-center align-items-center"
        style="background: rgba(255,255,255,0.9); z-index: 1055; position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="fw-bold text-primary fs-5">Loading...</div>
    </div>
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        /* ================= RESPONSIVE SALES UI ================= */

        /* allow smooth horizontal scroll on small devices */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* base table width */
        .sales-table {
            min-width: 700px;
        }

        /* 🔹 DISCOUNT COLUMN – THORI SI BARI */
        .sales-table td.large-col {
            min-width: 95px;
            width: 95px;
            padding: 4px;
        }

        /* 🔹 DISCOUNT LAYOUT */
        .discount-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: nowrap;
        }

        /* 🔹 INPUT – NOT TOO SMALL */
        .discount-wrapper .discount-value {
            width: 60px;
            min-width: 60px;
            font-size: 0.8rem;
            padding: 4px 6px;
        }

        /* 🔹 PLUS ICON – NEAT & SMALL */
        .discount-wrapper .discount-plus {
            width: 22px;
            height: 22px;
            padding: 0;
            font-size: 13px;
            line-height: 1;
        }

        /* 🔹 DROPDOWN */
        .discount-wrapper .discount-type {
            position: absolute;
            right: 0;
            top: 115%;
            width: 65px;
            font-size: 0.75rem;
            z-index: 30;
        }



        /* ---------- TABLET (<= 992px) ---------- */
        @media (max-width: 992px) {

            .main-container {
                max-width: 100%;
            }

            .sales-table {
                min-width: 700px;
            }

            .minw-350 {
                min-width: 100%;
            }

        }

        /* ---------- MOBILE (<= 768px) ---------- */
        @media (max-width: 768px) {

            .header-text {
                font-size: 1rem;
            }

            .btn {
                padding: .35rem .5rem;
            }

            /* stack header buttons */
            .d-flex.justify-content-between.align-items-center {
                flex-wrap: wrap;
                gap: 8px;
            }

            /* customer + invoice panel full width */
            .minw-350 {
                width: 100%;
            }

            /* reduce input font */
            .form-control,
            .form-select {
                font-size: .8rem;
            }

        }

        /* ---------- VERY SMALL DEVICES ---------- */
        @media (max-width: 576px) {

            .sales-table {
                min-width: 650px;
            }

            .discount-wrapper .discount-value {
                min-width: 90px;
            }

        }
    </style>
    <style>
        /* Premium Customer Card CSS - Refactored 2-row layout */
        .customer-card-premium {
            background-color: #111827 !important;
            border-radius: 10px !important;
            padding: 12px 16px !important;
            color: #f3f4f6 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            width: 100% !important;
        }

        .customer-card-premium .col-title {
            font-size: 0.65rem !important;
            text-transform: uppercase !important;
            font-weight: 700 !important;
            color: #9ca3af !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            margin-bottom: 2px !important;
        }

        .customer-card-premium .col-title i {
            margin-right: 2px !important;
        }

        .customer-card-premium .col-value {
            font-size: 0.88rem !important;
            font-weight: 700 !important;
            line-height: 1.2 !important;
        }

        .customer-card-premium .col-value-sub {
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            margin-top: -2px !important;
        }

        /* Sleek Segmented Control for Radio Buttons */
        .toggle-button-group {
            background-color: #f1f5f9;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            padding: 2px;
            display: flex;
            height: 38px;
            align-items: center;
        }
        .toggle-button-group .btn-check {
            display: none;
        }
        .toggle-button-group .toggle-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            height: 100%;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 0 !important;
            padding: 0 !important;
        }
        .toggle-button-group .toggle-btn:hover {
            color: #1e293b;
            background-color: #e2e8f0;
        }
        .toggle-button-group .btn-check:checked + .toggle-btn {
            background-color: #2563eb;
            color: #ffffff !important;
        }
        .toggle-button-group .btn-check:checked + .toggle-btn:hover {
            background-color: #1d4ed8;
            color: #ffffff !important;
        }

        /* Specific styling for the customer selection Select2 to match standard input heights */
        #customerSelect + .select2-container--default .select2-selection--single {
            border: 2px solid #cbd5e1 !important;
            border-radius: 8px !important;
            height: 38px !important;
            padding: 0 12px !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            background-color: #ffffff !important;
            transition: all 0.2s ease-in-out !important;
            display: flex !important;
            align-items: center !important;
        }
        #customerSelect + .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px !important;
            padding-left: 0 !important;
            font-size: 0.85rem !important;
            color: #1e293b !important;
        }
        #customerSelect + .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 34px !important;
            top: 2px !important;
            right: 8px !important;
        }
        #customerSelect + .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15) !important;
        }

        /* 💎 PREMIUM MODERN ERP THEME FOR TRANSACTION ENTRY 💎 */
        body {
            background-color: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        /* Containers & Cards */
        .main-container {
            border: 2px solid #475569 !important; /* Bold outer border */
            border-radius: 12px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05) !important;
            background-color: #ffffff !important;
            padding: 24px !important;
            font-size: .85rem;
            max-width: 98%;
        }
        
        .card-panel {
            background-color: #f8fafc !important;
            border: 2px solid #cbd5e1 !important; /* Bold panel borders */
            border-radius: 10px !important;
            padding: 20px !important;
            height: 100%;
            transition: all 0.2s;
        }
        
        .card-panel:hover {
            border-color: #94a3b8 !important;
        }
        
        .totals-card {
            background-color: #f1f5f9 !important;
            border: 2px solid #cbd5e1 !important; /* Bold summary borders */
            border-radius: 10px !important;
            padding: 20px !important;
        }
        
        /* Bold Section Titles */
        .section-title {
            font-weight: 800 !important;
            text-transform: uppercase;
            font-size: 0.8rem !important;
            letter-spacing: 1px !important;
            color: #1e293b !important;
            margin-bottom: 16px !important;
            border-left: 4px solid #2563eb !important;
            padding-left: 10px !important;
        }
        
        /* Clean inputs with bold borders */
        .form-control,
        .form-select,
        .select2-container--default .select2-selection--single {
            border: 2px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            background-color: #ffffff !important;
            transition: all 0.2s ease-in-out !important;
            height: auto !important;
            font-size: 0.85rem !important;
        }
        
        .form-control:focus,
        .form-select:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15) !important;
            outline: none !important;
        }
        
        /* Read-only fields */
        .input-readonly {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            color: #475569 !important;
            font-weight: 600 !important;
            cursor: not-allowed !important;
        }
        
        /* Elegant & Bold Buttons */
        .btn-action-primary {
            background-color: #2563eb !important;
            border: 2px solid #1d4ed8 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border-radius: 8px !important;
            padding: 8px 20px !important;
            transition: all 0.2s;
            font-size: 0.85rem !important;
        }
        .btn-action-primary:hover {
            background-color: #1d4ed8 !important;
            transform: translateY(-1px);
            color: #ffffff !important;
        }
        
        .btn-action-secondary {
            background-color: #ffffff !important;
            border: 2px solid #cbd5e1 !important;
            color: #475569 !important;
            font-weight: 700 !important;
            border-radius: 8px !important;
            padding: 8px 20px !important;
            transition: all 0.2s;
            font-size: 0.85rem !important;
        }
        .btn-action-secondary:hover {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
        }
        
        /* Transaction Grid / Table */
        .table-responsive {
            border: 1px solid #cbd5e1 !important; /* Elegant outer border */
            border-radius: 8px !important;
            overflow-x: auto !important;
            overflow-y: visible !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
            min-height: 200px;
            background-color: #ffffff;
        }
        
        .minw-350 {
            min-width: 320px;
            width: 320px;
            flex-shrink: 0;
        }

        .sales-table {
            border-collapse: collapse !important;
            margin-bottom: 0 !important;
            width: 100%;
            min-width: 900px;
        }
        
        .sales-table thead th {
            background-color: #f8fafc !important; /* Light clean header */
            color: #0f172a !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 11px !important;
            letter-spacing: 0.5px;
            padding: 10px 8px !important;
            border: 1px solid #cbd5e1 !important;
            border-bottom: 2px solid #94a3b8 !important; /* Thick header separator border */
            vertical-align: middle !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1) !important;
            outline: none !important;
        }
        
        .sales-table tbody td {
            border: 1px solid #cbd5e1 !important;
            padding: 0 !important;
            background-color: #ffffff;
            vertical-align: middle !important;
        }

        /* ⚡ FLAT BORDERLESS GRID INPUTS - COMPACT ⚡ */
        .sales-table tbody .form-control,
        .sales-table tbody .form-select {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            height: 26px !important;
            margin: 0 !important;
            padding: 1px 4px !important;
            width: 100% !important;
            background-color: transparent !important;
            text-align: center;
            color: #1e293b !important;
            font-weight: 500 !important;
            font-size: 0.76rem !important;
        }

        .sales-table tbody td.col-product .form-select {
            text-align: left !important;
            padding-left: 4px !important;
        }

        .sales-table tbody .input-readonly,
        .sales-table tbody input[readonly],
        .sales-table tbody select[disabled] {
            background-color: #f1f5f9 !important;
            cursor: not-allowed !important;
            color: #475569 !important;
            font-weight: 600 !important;
        }

        .sales-table tbody .form-control:focus,
        .sales-table tbody .form-select:focus {
            outline: none !important;
            background-color: #eff6ff !important;
            box-shadow: inset 0 0 0 1px #2563eb !important;
        }

        /* Select2 Specific flat borderless styling */
        .sales-table tbody .select2-container--default .select2-selection--single {
            height: 26px !important;
            padding: 0 !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background-color: transparent !important;
            display: flex;
            align-items: center;
        }

        .sales-table tbody .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px !important;
            padding-left: 4px !important;
            padding-right: 16px !important;
            font-size: 0.76rem !important;
            color: #1e293b !important;
            font-weight: 500 !important;
            text-align: left !important;
        }

        .sales-table tbody .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px !important;
            right: 4px !important;
        }

        /* Select2 Focus state */
        .sales-table tbody .select2-container--default.select2-container--focus .select2-selection--single {
            background-color: #eff6ff !important;
            box-shadow: inset 0 0 0 1px #2563eb !important;
        }

        .sales-table tbody .discount-wrapper {
            display: flex !important;
            align-items: stretch !important;
            width: 100% !important;
            height: 26px !important;
            gap: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .sales-table tbody .discount-wrapper .discount-value {
            flex-grow: 1 !important;
            border: none !important;
            border-radius: 0 !important;
            height: 100% !important;
            text-align: center;
            background-color: transparent !important;
            padding: 1px 3px !important;
        }

        .sales-table tbody .discount-wrapper .discount-toggle {
            border: none !important;
            border-radius: 0 !important;
            background-color: #e2e8f0 !important;
            color: #475569 !important;
            font-weight: 700 !important;
            font-size: 0.7rem !important;
            width: 24px !important;
            min-width: 24px !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            cursor: pointer !important;
        }

        .sales-table tbody .discount-wrapper .discount-toggle:hover {
            background-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        
        .sales-table tfoot td {
            background-color: #e2e8f0 !important;
            border: 1px solid #94a3b8 !important;
            border-top: 2px solid #64748b !important;
            padding: 2px 4px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            font-size: 0.78rem !important;
        }
        
        /* Row hover */
        .sales-table tbody tr:hover td {
            background-color: #f8fafc !important;
        }
        
        /* Column Widths - Compact & Full Width */
        .col-product { width: auto; min-width: 150px; }
        .col-warehouse { min-width: 90px; }
        .col-stock { width: 55px; min-width: 55px; }
        .col-qty, .col-qty-wrapper { width: 95px; min-width: 95px; }
        .col-price { width: 75px; min-width: 75px; }
        .col-disc { width: 75px; min-width: 75px; }
        .col-disc-amt { width: 70px; min-width: 70px; }
        .col-pieces { width: 55px; min-width: 55px; }
        .col-price-p { width: 75px; min-width: 75px; }
        .col-price-m2 { width: 75px; min-width: 75px; }
        .col-amount { width: 85px; min-width: 85px; }
        .col-action { width: 32px; min-width: 32px; text-align: center; }
        .col-size { width: 55px; min-width: 55px; }
        .col-color { width: 60px; min-width: 60px; }

        /* Invalid cells & inputs */
        .invalid-cell {
            background-color: #fff5f5 !important;
            border: 2px solid #ef4444 !important;
        }
        .invalid-select,
        .invalid-input {
            border-color: #ef4444 !important;
            box-shadow: none !important;
        }
        
        .badge-soft {
            background: #eef2ff;
            color: #3730a3;
            font-weight: 700;
        }

        /* Walk-in Customer Select Alignment */
        #customerInputWrapper .select2-container--default .select2-selection--single {
            height: 26px !important;
            min-height: 26px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            border: 2px solid #cbd5e1 !important;
            border-radius: 8px !important;
            background-color: #ffffff !important;
        }
        #customerInputWrapper .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 22px !important;
            padding-left: 10px !important;
            font-size: 0.85rem !important;
            color: #1e293b !important;
            font-weight: 500 !important;
        }
        #customerInputWrapper .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 22px !important;
            top: 0 !important;
            right: 4px !important;
        }
        #customerInputWrapper .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15) !important;
        }
    </style>

    <div class="container-fluid py-0 px-1">
        <div class="main-container bg-white border mx-auto">
            <div id="alertBox" class="alert d-none mb-1" role="alert" style="padding:4px 8px; font-size:0.78rem;"></div>
            <form id="saleForm">
                @csrf
                {{-- No method PUT needed here if we handle update via same endpoint or different. 
                     Typically Laravel edit form uses PUT. 
                     We are using AJAX save, so method usually handled in JS. 
                     But let's stick to the existing structure. --}}
                <input type="hidden" name="booking_id" id="booking_id" value="">
                <input type="hidden" id="action" name="action" value="sale">

                {{-- HEADER - Compact --}}
                <div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom" style="min-height:28px;">
                    <small class="text-secondary" id="entryDateTime" style="font-size:0.72rem;">Entry Date_Time: --</small>
                    <div class="d-flex align-items-center gap-1">
                        <small class="text-secondary d-none" id="entryDate" style="font-size:0.72rem;">Date: --</small>
                        <button type="button" class="btn btn-sm btn-success py-0 px-2" id="btnHeaderPosted"
                            disabled style="font-size:0.72rem; height:22px; line-height:20px;">Sale</button>
                    </div>
                </div>

                <!-- HORIZONTAL TOP PANEL -->
                <div class="p-1 border bg-white mb-1" style="border-radius:3px;">
                    <div class="row g-1 align-items-end w-100 m-0">
                        <!-- Invoice No -->
                        <div class="col-sm-2">
                            <label class="form-label fw-bold text-secondary mb-0" style="font-size: 0.72rem;">Invoice No.</label>
                            <input type="text" class="form-control input-readonly" name="Invoice_no"
                                value="{{ $nextInvoiceNumber ?? ($sale->invoice_no ?? '') }}" readonly style="height: 26px !important;">
                        </div>
                        
                        <!-- Credit Days -->
                        <div class="col-sm-1">
                            <label class="form-label fw-bold text-secondary mb-0" style="font-size: 0.72rem;">Cr. Days</label>
                            <input type="number" class="form-control" name="credit_days" placeholder="0"
                                min="0" value="{{ $sale->credit_days ?? '' }}" style="height: 26px !important; padding: 0 4px;">
                        </div>
                        
                        <!-- Date -->
                        <div class="col-sm-2">
                            <label class="form-label fw-bold text-secondary mb-0" style="font-size: 0.72rem;">Date:</label>
                            <input type="text" name="sale_date" class="form-control datepicker-custom" id="displayDateInput" value="{{ isset($sale) ? $sale->created_at->format('Y-m-d') : date('Y-m-d') }}" style="background-color: #ffffff; height: 26px !important; padding: 0 4px;">
                        </div>
                        
                        <!-- M.Bill -->
                        <div class="col-sm-1">
                            <label class="form-label fw-bold text-secondary mb-0" style="font-size: 0.72rem;">M.Bill:</label>
                            <input type="text" class="form-control" name="reference" id="remarks" placeholder="Remarks" style="height: 26px !important; padding: 0 4px;" value="{{ $sale->reference ?? '' }}">
                        </div>
                        
                        <!-- Customer Type -->
                        <div class="col-sm-2" id="customerTypeCol">
                            <label class="form-label fw-bold text-secondary mb-0" style="font-size: 0.72rem;">Customer Type:</label>
                            <select class="form-select fw-bold" id="partyTypeSelect" name="partyType" style="font-size: 0.85rem; height: 26px; padding: 0 4px;">
                                @foreach(\App\Models\CustomerType::orderBy('name')->get() as $type)
                                    <option value="{{ $type->name }}" {{ $type->name === ($sale->walkin_name ? 'Walking Customer' : ($sale->customer_relation->customer_type ?? 'Main Customer')) ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Customer & Walkin Toggle -->
                        <div class="col-sm-4">
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <label class="form-label fw-bold text-secondary mb-0" style="font-size: 0.72rem;">Customer:</label>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-outline-success py-0 px-1" data-bs-toggle="modal" data-bs-target="#addCustomerModal" title="Add New Customer" style="font-size: 0.68rem; height: 18px; line-height: 16px;">
                                        <i class="fas fa-plus"></i> New
                                    </button>
                                    <input type="hidden" name="is_walkin" id="is_walkin" value="{{ (!isset($sale) || $sale->walkin_name) ? '1' : '0' }}">
                                </div>
                            </div>
                            <!-- Input Morph Container -->
                            <div id="customerInputWrapper" style="height: 26px;">
                                <!-- Walk-in Input -->
                                <input type="text" class="form-control {{ (!isset($sale) || $sale->walkin_name) ? '' : 'd-none' }}" name="walkin_name" id="walkinNameInput" value="{{ $sale->walkin_name ?? 'Walk-in Customer' }}" placeholder="Enter Name..." style="height: 26px !important;">
                                <!-- Main Customer Select2 -->
                                <select class="form-select {{ (!isset($sale) || $sale->walkin_name) ? 'd-none' : '' }}" id="customerSelect" name="customer" style="width:100%">
                                    @if (isset($sale) && $sale->customer_relation)
                                        <option value="{{ $sale->customer_id }}" selected>
                                             {{ $sale->customer_relation->customer_name }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Sales Person -->
                        <div class="col-sm-2">
                            <label class="form-label fw-bold text-secondary mb-0" style="font-size: 0.72rem;">Sales Person:</label>
                            <input type="text" class="form-control" name="salesman_name" id="salesmanName" placeholder="Sales Person Name" value="{{ $sale->salesman_name ?? '' }}" style="height: 26px !important; padding: 0 4px;">
                        </div>

                        <!-- Commission -->
                        <div class="col-sm-2">
                            <div class="d-flex justify-content-between align-items-center mb-0">
                                <label class="form-label fw-bold text-secondary mb-0" style="font-size: 0.72rem;">Commission:</label>
                                <span class="badge bg-light text-muted border px-1" id="commissionCalculatedBadge" style="font-size:0.62rem;">Rs. 0.00</span>
                            </div>
                            <div class="input-group input-group-sm" style="height: 26px;">
                                <input type="number" step="any" min="0" class="form-control text-end fw-bold" name="commission_rate" id="commissionRate" placeholder="0" value="{{ $sale->commission_rate ?? '0' }}" style="height: 26px !important; padding: 0 4px; font-size:0.75rem;">
                                <input type="hidden" name="commission_type" id="commissionType" value="{{ $sale->commission_type ?? 'fixed' }}">
                                <input type="hidden" name="commission_amount" id="commissionAmount" value="{{ $sale->commission_amount ?? '0' }}">
                                <button type="button" class="btn {{ (isset($sale) && $sale->commission_type === 'percent') ? 'btn-outline-primary' : 'btn-outline-success' }} fw-bold px-1 py-0" id="btnToggleCommissionType" title="Toggle Commission (Rs or %)" style="font-size:0.68rem; height: 26px; min-width: 28px;">
                                    {{ (isset($sale) && $sale->commission_type === 'percent') ? '%' : 'Rs' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden fields for backend --}}
                <input type="hidden" id="address" name="address" value="{{ optional($sale->customer_relation)->address }}">
                <input type="hidden" id="tel" name="tel" value="{{ optional($sale->customer_relation)->mobile }}">
                <input type="hidden" id="previousBalance" value="{{ optional($sale->customer_relation)->previous_balance ?? 0 }}">
                <input type="hidden" id="rangeBalance" value="{{ optional($sale->customer_relation)->balance_range ?? 0 }}">

                <!-- Items Section full width -->
                <div class="p-1 border bg-white mt-1" style="border-radius:3px;">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="section-title mb-0" style="font-size:0.7rem;">Items</div>
                        <button type="button" class="btn btn-primary py-0 px-1" id="btnAdd" style="font-size:0.7rem; height:20px; line-height:18px;">+Row</button>
                    </div>

                        <div class="table-responsive">
                            <table class="table table-bordered sales-table mb-0">

                                <thead>
                                    <tr>
                                        <th style="width:30px;" class="text-center">#</th>
                                        <th class="col-product" style="min-width: 160px;">PRODUCT</th>
                                        <th class="col-stock" style="width: 60px;">STOCK</th>
                                        <th class="col-qty" style="width: 85px;">QTY</th>
                                        <th class="col-size" style="width: 55px;">SIZE</th>
                                        <th class="col-color" style="width: 65px;">COLOR</th>
                                        <th class="col-pieces" style="width: 55px;">PCS</th>
                                        <th class="col-price-p" style="width: 85px;">PRICE</th>
                                        <th class="col-disc" style="width: 85px;">DISCOUNT</th>
                                        <th class="col-amount" style="width: 95px;">AMOUNT</th>
                                        <th class="col-action" style="width: 34px;">×</th>
                                    </tr>
                                </thead>
                                <tbody id="salesTableBody">
                                    @if (isset($sale) && $sale->items)
                                        @foreach ($sale->items as $item)
                                            @php
                                                $prod = $item->product;
                                                $sizeMode = $item->size_mode ?? ($prod->size_mode ?? 'std');

                                                $ppb = 1;
                                                if ($item->pieces_per_box > 0) {
                                                    $ppb = $item->pieces_per_box;
                                                } elseif ($prod && $prod->pieces_per_box > 0) {
                                                    $ppb = $prod->pieces_per_box;
                                                }

                                                $cartons = 0;
                                                $loose = 0;
                                                if ($ppb > 0) {
                                                    $cartons = floor($item->total_pieces / $ppb);
                                                    $loose = $item->total_pieces % $ppb;
                                                } else {
                                                    $loose = $item->total_pieces;
                                                }

                                                // Calculate Warehouse Stock Display for the SELECTED warehouse
                                                $selStockDisp = '';
                                                if ($item->warehouse_id) {
                                                    $selWs = $prod ? $prod->warehouseStocks
                                                        ->where('warehouse_id', $item->warehouse_id)
                                                        ->first() : null;
                                                    if ($selWs) {
                                                        $stk = (float) $selWs->total_pieces;
                                                        if ($stk <= 0 && $selWs->quantity > 0) {
                                                            $stk = $selWs->quantity * $ppb;
                                                        }

                                                        $b = floor($stk / $ppb);
                                                        $l = $stk % $ppb;

                                                        $selStockDisp =
                                                            in_array($sizeMode, ['by_cartons', 'by_size']) && $ppb > 0
                                                                ? ($l > 0
                                                                    ? "$b.$l"
                                                                    : $b)
                                                                : $stk;
                                                    }
                                                }
                                            @endphp
                                            <tr data-size_mode="{{ $sizeMode }}"
                                                data-pieces_per_box="{{ $ppb }}"
                                                data-price_per_m2="{{ $prod->price_per_m2 ?? 0 }}">
                                                <!-- # ROW INDEX -->
                                                <td class="text-center fw-bold text-muted row-index" style="vertical-align:middle; font-size:0.75rem;">{{ $loop->iteration }}</td>

                                                <!-- Product -->
                                                <td class="col-product">
                                                    @php
                                                        $variantLabel = '';
                                                        $vSize = '-';
                                                        $vCol = '-';
                                                        if ($item->color) {
                                                            try {
                                                                $vData = json_decode(base64_decode($item->color), true);
                                                                if ($vData && isset($vData['name'])) {
                                                                    $vSize = (isset($vData['size']) && $vData['size'] !== '-') ? $vData['size'] : '-';
                                                                    $vCol  = (isset($vData['color']) && $vData['color'] !== '-') ? $vData['color'] : '-';
                                                                    $sStr = $vSize !== '-' ? " {$vSize}" : '';
                                                                    $cStr = $vCol !== '-' ? " ({$vCol})" : '';
                                                                    $variantLabel = ' — ' . $vData['name'] . $sStr . $cStr;
                                                                }
                                                            } catch (\Exception $e) {}
                                                        }
                                                    @endphp
                                                    <select class="form-select product" style="width:100%">
                                                        @if ($prod)
                                                            <option value="{{ $item->product_id }}" selected>
                                                                {{ $prod->item_name }}{{ $variantLabel }}</option>
                                                        @endif
                                                    </select>
                                                    <input type="hidden" class="product-id-hidden" name="product_id[]" value="{{ $item->product_id }}">
                                                    <input type="hidden" class="variant-data-hidden" name="color[]" value="{{ $item->color ?? '' }}">
                                                    <input type="hidden" class="item-code-display" value="{{ $prod->item_code ?? '' }}">
                                                    <input type="hidden" class="size-h" value="{{ $prod->height ?? '-' }}">
                                                    <input type="hidden" class="size-w" value="{{ $prod->width ?? '-' }}">
                                                    <input type="hidden" class="size-mode-text" value="{{ $sizeMode }}">
                                                </td>

                                                <!-- Stock & Warehouse -->
                                                <td class="col-stock">
                                                    <input type="text"
                                                        class="form-control stock text-center input-readonly" readonly
                                                        value="{{ $selStockDisp }}" tabindex="-1">
                                                    <select class="warehouse d-none" name="warehouse_id[]">
                                                        @foreach ($warehouse as $w)
                                                            <option value="{{ $w->id }}"
                                                                {{ $item->warehouse_id == $w->id ? 'selected' : '' }}>
                                                                {{ $w->warehouse_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" class="variant-stock-value">
                                                </td>

                                                <!-- Carton -->
                                                <td style="width:85px;" class="col-qty-wrapper">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <input type="number" step="any" class="form-control carton-qty text-start fw-bold"
                                                            name="carton_qty[]" value="{{ $cartons }}" placeholder="0" min="0" style="flex: 1; min-width: 0; padding-left: 6px;">
                                                        <button type="button" class="btn btn-sm btn-outline-primary qty-unit-toggle px-1 py-0 d-none" 
                                                                data-unit-mode="main" title="Toggle Unit" style="font-size: 0.65rem; height: 26px; min-width: 28px; font-weight: 700; border-radius: 4px; flex-shrink: 0;">
                                                            Kg
                                                        </button>
                                                    </div>
                                                    <input type="hidden" class="hidden-sub-unit-mode" name="sub_unit_mode[]" value="main">
                                                </td>

                                                <!-- Loose Pcs (hidden) -->
                                                <td style="width:55px;min-width:55px;" class="d-none">
                                                    <input type="number" class="form-control loose-pcs-input text-end"
                                                        name="loose_qty[]" value="{{ $loose }}" placeholder="0" min="0">
                                                </td>

                                                <!-- Size -->
                                                <td class="col-size">
                                                    <input type="text"
                                                        class="form-control size-display text-center"
                                                        name="size_display[]"
                                                        value="{{ ($vSize ?? '') !== '-' ? ($vSize ?? '') : '' }}"
                                                        placeholder="-">
                                                    <input type="hidden" class="pack-qty" name="pack_qty[]" value="{{ $ppb }}">
                                                </td>
                                                
                                                <!-- Color -->
                                                <td class="col-color">
                                                    <input type="text"
                                                        class="form-control color-display text-center input-readonly"
                                                        readonly value="{{ $vCol ?? '-' }}"
                                                        tabindex="-1">
                                                </td>

                                                <!-- Total Pieces -->
                                                <td class="col-pieces">
                                                    <input type="text"
                                                        class="form-control total-pieces text-end input-readonly fw-semibold"
                                                        name="total_pieces[]" readonly value="{{ $item->total_pieces }}"
                                                        tabindex="-1">
                                                    <!-- Hidden qty field for backend compatibility -->
                                                    <input type="hidden" class="sales-qty" name="qty[]" value="{{ $cartons . ($loose > 0 ? '.' . $loose : '') }}">
                                                </td>

                                                <!-- Retail Price -->
                                                <td class="col-price-p">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <input type="text"
                                                            class="form-control visible-price text-end fw-semibold"
                                                            name="visible_price[]"
                                                            value="{{ $item->price }}"
                                                            placeholder="0.00" style="flex: 1; min-width: 0;">
                                                        <button type="button" class="btn btn-sm btn-outline-primary price-mode-row-toggle px-1 py-0" 
                                                                data-mode="retail" title="Retail Mode">
                                                            R
                                                        </button>
                                                    </div>
                                                    <input type="hidden" class="price-per-piece"
                                                        name="price_per_piece[]"
                                                        value="{{ $item->price }}">
                                                    <input type="hidden" class="retail-price"
                                                        value="{{ $prod->retail_price ?? $item->price }}">
                                                    <input type="hidden" class="wholesale-price"
                                                        value="{{ $prod->wholesale_price ?? $item->price }}">
                                                    <input type="hidden" class="weight-per-piece"
                                                        value="{{ $prod->weight_per_piece ?? 0 }}">
                                                </td>

                                                <!-- Discount -->
                                                <td class="col-disc">
                                                    <div class="discount-wrapper">
                                                        <input type="number" class="form-control discount-value text-end"
                                                            name="item_disc[]" value="{{ $item->discount_percent }}">
                                                        <input type="hidden" class="discount-type-hidden" name="discount_type[]" value="percent">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary discount-toggle"
                                                            data-type="percent" tabindex="-1">%</button>
                                                    </div>
                                                    <input type="hidden" class="discount-amount" value="{{ $item->discount_amount }}">
                                                </td>

                                                <!-- Net Amount -->
                                                <td class="col-amount">
                                                    <input type="text"
                                                        class="form-control sales-amount text-end input-readonly fw-bold text-dark"
                                                        name="total[]" value="{{ $item->total }}" readonly
                                                        tabindex="-1">
                                                    <input type="hidden" class="gross-amount" value="{{ $item->total + $item->discount_amount }}">
                                                </td>

                                                <!-- Action -->
                                                <td class="col-action text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-danger del-row"
                                                        tabindex="-1" title="Delete Row">&times;</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="9" class="text-end fw-bold text-uppercase text-secondary" style="font-size:0.8rem;">GRID TOTAL:</td>
                                        <td class="text-end fw-bold text-success fs-6"><span id="totalAmount">0.00</span></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                {{-- Totals + Receipts --}}
                <div class="row g-2 mt-2 align-items-stretch">
                    <!-- LEFT SIDE: Receipt Vouchers -->
                    <div class="col-lg-7" id="receiptVouchersSection">
                        <div class="card border-0 shadow-sm h-100 rounded-3">
                            <div class="card-header bg-light border-bottom-0 py-2 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-secondary text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Receipt Vouchers</span>
                                <span class="badge bg-primary rounded-pill" style="font-size:0.75rem;">Total: <span id="receiptsTotalBadge">0.00</span></span>
                            </div>
                            <div class="card-body p-2 bg-white">
                                <div id="rvWrapper">
                                    @php
                                        $receiptVoucher = \App\Models\VoucherMaster::with('details')
                                            ->where('voucher_type', \App\Models\VoucherMaster::TYPE_RECEIPT)
                                            ->where('remarks', 'like', "%#{$sale->invoice_no}%")
                                            ->first();
                                        $receiptLines = collect();
                                        if ($receiptVoucher) {
                                            $receiptLines = $receiptVoucher->details->where('debit', '>', 0);
                                        }
                                        $firstLine = $receiptLines->first();
                                        $otherLines = $receiptLines->skip(1);

                                        $defaultCashAcc = $accounts->first(function($a) {
                                            $t = strtolower($a->title);
                                            return str_contains($t, 'cash in hand') || str_contains($t, 'main cash') || $t === 'cash' || $t === 'cash account';
                                        }) ?? $accounts->first(function($a) {
                                            return str_contains(strtolower($a->title), 'cash');
                                        }) ?? $accounts->first();
                                        $defaultCashId = $defaultCashAcc ? $defaultCashAcc->id : null;

                                        $selectedFirstAccId = $firstLine ? $firstLine->account_id : $defaultCashId;
                                    @endphp
                                    <div class="d-flex gap-2 align-items-center mb-2 rv-row">
                                        <select class="form-select form-select-sm rv-account bg-light" name="receipt_account_id[]" style="max-width: 280px; border-radius: 4px;">
                                            @foreach ($accounts as $acc)
                                                <option value="{{ $acc->id }}" {{ $selectedFirstAccId == $acc->id ? 'selected' : '' }}>{{ $acc->title }}</option>
                                            @endforeach
                                        </select>
                                        <input type="number" step="0.01" class="form-control form-control-sm text-end rv-amount fw-bold" name="receipt_amount[]" value="{{ $firstLine ? number_format($firstLine->debit, 2, '.', '') : ((isset($sale) && $sale->cash > 0) ? number_format($sale->cash, 2, '.', '') : '') }}" placeholder="0.00" style="max-width:140px; border-radius: 4px;">
                                        <button type="button" class="btn btn-primary btn-sm px-2 rounded-2 shadow-sm" id="btnAddRV"><i class="fas fa-plus"></i> Add</button>
                                    </div>
                                    @foreach ($otherLines as $line)
                                        <div class="d-flex gap-2 align-items-center mb-2 rv-row">
                                            <select class="form-select form-select-sm rv-account bg-light" name="receipt_account_id[]" style="max-width: 280px; border-radius: 4px;">
                                                @foreach ($accounts as $acc)
                                                    <option value="{{ $acc->id }}" {{ $line->account_id == $acc->id ? 'selected' : '' }}>{{ $acc->title }}</option>
                                                @endforeach
                                            </select>
                                            <input type="number" step="0.01" class="form-control form-control-sm text-end rv-amount fw-bold" name="receipt_amount[]" value="{{ number_format($line->debit, 2, '.', '') }}" placeholder="0.00" style="max-width:140px; border-radius: 4px;">
                                            <button type="button" class="btn btn-outline-danger btn-sm btnRemRV shadow-sm px-2 rounded-2">&times;</button>
                                        </div>
                                    @endforeach
                                    <div class="text-end d-none">
                                        <span class="me-2">Receipts Total:</span>
                                        <span class="fw-bold" id="receiptsTotal">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT SIDE: Totals -->
                    <div class="col-lg-5" id="totalsSection">
                        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                            <!-- Walk-in View -->
                            <div id="totalsWalkinView" class="h-100 d-none align-items-center bg-white justify-content-between p-2 gap-2 flex-nowrap" style="overflow-x: auto;">
                                <!-- Net Total -->
                                <div class="d-flex align-items-center bg-light px-2 py-1 border rounded gap-1 flex-shrink-0">
                                    <span class="fw-bold text-secondary" style="font-size:0.78rem;">Net Total:</span>
                                    <span id="walkinNetTotal" class="fw-bold text-primary mb-0" style="font-size:1rem; line-height:1;">0.00</span>
                                </div>
                                
                                <!-- Discount -->
                                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                    <span class="text-muted fw-semibold" style="font-size:0.78rem;">Disc (Rs):</span>
                                    <input type="number" class="form-control form-control-sm text-end fw-bold text-danger border-danger" id="walkinDiscountRs" value="{{ isset($sale) && ($sale->is_walkin || $sale->walkin_name || empty($sale->customer_id)) ? (float) $sale->total_extradiscount : '0' }}" placeholder="0" style="width: 80px; background-color: #fef2f2; height: 26px !important; padding: 1px 4px;">
                                </div>
                                
                                <!-- Payments -->
                                <div class="d-flex align-items-center gap-1 px-2 border-start border-end" style="min-width: 320px;">
                                    <span class="text-muted fw-semibold mb-0 flex-shrink-0" style="font-size:0.78rem;">Payments:</span>
                                    <div id="walkinReceiptsContainer" class="flex-grow-1 w-100"></div>
                                </div>
                                
                                <!-- Change -->
                                <div class="d-flex align-items-center gap-1 px-2 flex-shrink-0">
                                    <span class="fw-bold text-uppercase text-secondary" style="font-size:0.78rem;">Change:</span>
                                    <span id="walkinChange" class="fw-bold text-warning mb-0" style="font-size:1.1rem; line-height:1;">0.00</span>
                                </div>

                                <!-- Change Account -->
                                <div class="align-items-center gap-1 px-2 flex-shrink-0" id="editChangeAccountContainer" style="display: none;">
                                    <span class="fw-bold text-danger" style="font-size:0.78rem;">Change A/C:</span>
                                    <select class="form-select form-select-sm bg-light fw-bold text-danger border-danger" name="change_account_id" id="editChangeAccountId" style="width: 140px; font-size:0.75rem; height: 26px; padding: 1px 4px;">
                                        @foreach ($accounts as $acc)
                                            <option value="{{ $acc->id }}" {{ (isset($sale) && $sale->change_account_id == $acc->id) ? 'selected' : ($defaultCashId == $acc->id ? 'selected' : '') }}>{{ $acc->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Customer View -->
                            <div id="totalsCustomerView" class="h-100 flex-column bg-light">
                                <div class="p-2 d-flex flex-column gap-1" style="font-size:0.8rem;">
                                    <div class="d-flex justify-content-between px-2">
                                        <span class="text-muted">Total Qty</span>
                                        <span class="fw-semibold text-dark" id="tQty">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between px-2">
                                        <span class="text-muted">Invoice Gross</span>
                                        <span class="fw-semibold text-dark" id="tGross">0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between px-2">
                                        <span class="text-muted">Line Discount</span>
                                        <span class="fw-semibold text-danger" id="tLineDisc">0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between px-2 py-1 bg-white border rounded shadow-sm my-1">
                                        <span class="fw-bold">Sub-Total</span>
                                        <span class="fw-bold text-primary" id="tSub">0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center px-2">
                                        <span class="text-muted">Add. Discount %</span>
                                        <input type="number" class="form-control form-control-sm text-end p-1" name="discountPercent" id="discountPercent" value="{{ isset($sale) && $sale->total_bill_amount > 0 ? number_format(($sale->total_extradiscount / $sale->total_bill_amount) * 100, 2) : 0 }}" style="width:80px; height:24px;">
                                    </div>
                                    <div class="d-flex justify-content-between px-2">
                                        <span class="text-muted">Add. Discount Rs</span>
                                        <span class="fw-semibold text-danger" id="tOrderDisc">0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between px-2 py-1 bg-white border border-primary border-opacity-25 rounded shadow-sm my-1">
                                        <span class="fw-bold text-primary">Current Bill</span>
                                        <span class="fw-bold text-primary" id="tCurrentBill">0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between px-2">
                                        <span class="text-muted">Previous Balance</span>
                                        <span class="fw-semibold text-danger" id="tPrev">{{ number_format(optional($sale->customer_relation)->previous_balance ?? 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="mt-auto p-2 bg-dark text-white d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-uppercase" style="font-size:0.75rem; letter-spacing:1px;">Payable / Total</span>
                                    <span class="fs-5 fw-bold" id="tPayable">0.00</span>
                                </div>
                            </div>

                            {{-- hidden mirrors for backend --}}
                            <input type="hidden" name="subTotal1" id="subTotal1" value="0">
                            <input type="hidden" name="total_subtotal" id="subTotal2" value="0">
                            <input type="hidden" name="total_extra_cost" id="discountAmount" value="0">
                            <input type="hidden" name="total_net" id="totalBalance" value="0">
                            <input type="hidden" name="cash" value="0">
                            <input type="hidden" name="card" value="0">
                            <input type="hidden" name="change" id="backendChange" value="0">
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex flex-wrap gap-1 justify-content-center py-1 px-2 mt-1 border-top">
                    <button type="button" class="btn btn-action-primary bg-primary border-primary" id="btnSave"><i class="fas fa-bookmark me-1"></i>Booking</button>
                    <button type="button" class="btn btn-action-primary bg-success border-success" id="btnPosted"><i class="fas fa-check-circle me-1"></i>Sale</button>

                    <button type="button" class="btn btn-action-secondary" id="btnPrint"><i class="fas fa-print me-1"></i>A4</button>
                    <button type="button" class="btn btn-action-secondary" id="btnEstimate"><i class="fas fa-file-invoice me-1"></i>Est</button>
                    <button type="button" class="btn btn-action-secondary" id="btnPrint2"><i class="fas fa-receipt me-1"></i>Thermal</button>
                    <button type="button" class="btn btn-action-secondary" id="btnDcThermal"><i class="fas fa-truck me-1"></i>DC</button>
                </div>
            </form>
            @endsection

            @section('js')
                @include('admin_panel.sale.scripts.shared_logic')
                <script>
                    $(document).ready(function() {
                        // ============================================================
                        // CUSTOMER SELECT2 AJAX SEARCH (Name or Code)
                        // ============================================================
                        function getPartyType() {
                            return $('#partyTypeSelect').val() || 'Main Customer';
                        }

                        $('#customerSelect').select2({
                            placeholder: 'Search by Name or Code...',
                            allowClear: true,
                            width: '100%',
                            minimumInputLength: 0,
                            ajax: {
                                url: '{{ route('salecustomers.index') }}',
                                dataType: 'json',
                                delay: 250,
                                data: function(params) {
                                    return {
                                        type: getPartyType(),
                                        search: params.term || ''
                                    };
                                },
                                processResults: function(data) {
                                    return {
                                        results: data.map(function(c) {
                                            return {
                                                id: c.id,
                                                text: (c.customer_id || '') + ' — ' + c.customer_name,
                                                customer: c
                                            };
                                        })
                                    };
                                },
                                cache: false
                            },
                            templateResult: function(item) {
                                if (item.loading) return item.text;
                                if (!item.customer) return item.text;
                                const c = item.customer;
                                return $(`<div>
                                    <strong>${c.customer_name}</strong>
                                    <small class="text-muted ms-2">${c.customer_id || ''}</small>
                                    ${c.mobile ? '<br><small class="text-muted">' + c.mobile + '</small>' : ''}
                                </div>`);
                            },
                            templateSelection: function(item) {
                                if (!item.customer) return item.text;
                                return item.customer.customer_id + ' — ' + item.customer.customer_name;
                            }
                        });

                        // Set initial visibility state of Customer Select / Walk-in input
                        if (typeof handleCustomerTypeChange === 'function') {
                            handleCustomerTypeChange();
                        }

                        // Party type change → reset customer only when user changes
                        $(document).on('change', '#partyTypeSelect', function(e) {
                            if (!e.isTrigger) {
                                $('#customerSelect').val(null).trigger('change');
                                clearCustomerInfo();
                            }
                        });

                        // Customer selected → load details
                        $('#customerSelect').on('select2:select', function(e) {
                            const id = e.params.data.id;
                            if (!id) return;

                            $.get("{{ url('sale/customers') }}/" + id + "?t=" + new Date().getTime(), function(d) {
                                $('#address').val(d.address || '');
                                $('#tel').val(d.mobile || '');
                                $('#remarks').val(d.status || '');
                                const prev = parseFloat(d.previous_balance || 0);
                                const range = parseFloat(d.balance_range || 0);
                                $('#previousBalance').val(prev.toFixed(2));
                                $('#rangeBalance').val(range.toFixed(2));

                                if (typeof updateGrandTotals === 'function') updateGrandTotals();
                            }).fail(function() {
                                showAlert('error', 'Failed to load customer details');
                            });
                        });

                        // Customer cleared
                        $('#customerSelect').on('select2:clear', function() {
                            clearCustomerInfo();
                            if (typeof updateGrandTotals === 'function') updateGrandTotals();
                        });

                        function clearCustomerInfo() {
                            $('#address, #tel, #remarks').val('');
                            $('#previousBalance, #rangeBalance').val('0');
                        }

                        $('#clearCustomerData').on('click', function() {
                            $('#customerSelect').val(null).trigger('change');
                            clearCustomerInfo();
                            if (typeof updateGrandTotals === 'function') updateGrandTotals();
                        });

                        // Edit Sale Specific Handlers
                        $('#btnPrint').on('click', function() {
                            ensureSaved().then(id => window.open('{{ url('sales') }}/' + id + '/invoice', '_blank'));
                        });
                        $('#btnEstimate').on('click', function() {
                            ensureSaved().then(id => window.open('{{ url('sales') }}/' + id + '/invoice?type=estimate', '_blank'));
                        });
                        $('#btnPrint2').on('click', function() {
                            ensureSaved().then(id => window.open('{{ url('sales') }}/' + id + '/recepit', '_blank'));
                        });
                        $('#btnDcThermal').on('click', function() {
                            ensureSaved().then(id => window.open('{{ url('sales') }}/' + id + '/dc-thermal', '_blank'));
                        });
                    });
                </script>
                @if (isset($sale))
                    <script>
                        $(document).ready(function() {
                            // --- PRE-FILL EDIT MODE (Server Side Rendered) ---
                            console.log("Loading Edit Mode for Sale #{{ $sale->id }}");
                            $('#booking_id').val("{{ $sale->id }}");
                            $('#entryDateTime').text("Date: {{ $sale->created_at->format('d/m/Y H:i') }}");

                            // Initialize Select2 on server-rendered rows
                            $('.product').each(function() {
                                if (typeof initProductSelect2 === 'function') {
                                    initProductSelect2($(this));
                                }
                            });

                            // Recalculate totals based on rendered values
                            $('#salesTableBody tr').each(function() {
                                if (typeof computeRow === 'function') {
                                    computeRow($(this));
                                }
                            });

                            // Recompute Receipts and then updateGrandTotals
                            if (typeof window.recomputeReceipts === 'function') {
                                window.recomputeReceipts();
                            } else {
                                updateGrandTotals();
                            }

                            if (typeof refreshPostedState === 'function') {
                                refreshPostedState();
                            }

                            setTimeout(() => {
                                $('#pageLoader').addClass('d-none');
                            }, 300);
                        });
                    </script>
                @endif
            @endsection
