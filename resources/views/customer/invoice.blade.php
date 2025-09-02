@extends('layouts.app')
@section('title', 'Billing & Invoice')
@section('content')
    @include('components.toast')
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%);
            height: 100%;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151 !important;
            line-height: 32px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            color: #374151 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 1px #2563eb !important;
            outline: none !important;
        }

        .select2-container--default .select2-results__option {
            color: #374151 !important;
        }

        .select2-container--default .select2-results__option--highlighted {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        #created_at,
        .flatpickr-input {
            background-color: white !important;
        }

        #created_at:focus,
        .flatpickr-input:focus {
            background-color: white !important;
            border-color: #2563eb !important;
            outline: none !important;
            box-shadow: 0 0 0 1px #2563eb !important;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="w-full flex flex-col gap-4 mb-20">
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 md:gap-1 gap-3">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Billing & Invoice</h2>
                <a href="{{ route('customer.transaction') }}"
                    class="block md:hidden bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Transaction
                </a>
            </div>
            <div class="flex justify-between items-center text-gray-600 text-sm">
                <p>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a> / Customer
                    /
                    Billing & Invoice
                </p>
                <a href="{{ route('customer.transaction') }}"
                    class="hidden md:block bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Transaction
                </a>
            </div>
        </div>

        <div class="w-full bg-white rounded shadow px-6">
            <form action="{{ route('customer.invoice.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mt-4">
                    <label for="customer" class="block text-md font-medium text-gray-700 mb-1.5">Customer <span
                            class="text-red-500">*</span></label>
                    <select id="customer" name="customer_id"
                        class="select2 w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                        <option value="">Select a customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="invoice_type" class="block text-gray-700 font-medium">Invoice <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="invoice_type" id="invoice_type" placeholder="Invoice type"
                        class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700" value="{{ old('invoice') }}">
                    @error('invoice_type')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="amount" class="block text-gray-700 font-medium">Amount <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="amount" id="amount" placeholder="Amount"
                        class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700" value="{{ old('amount') }}">
                    @error('amount')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="created_at" class="block text-gray-700 font-medium">Date <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="created_at" id="created_at" placeholder="dd/mm/yyyy"
                        class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700"
                        value="{{ old('created_at', date('d/m/Y')) }}">
                    @error('created_at')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-end mt-4 mb-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select a customer",
                allowClear: true
            });

            flatpickr("#created_at", {
                dateFormat: "d/m/Y",
                defaultDate: "{{ old('created_at', date('d/m/Y')) }}"
            });
        });
    </script>
@endpush
