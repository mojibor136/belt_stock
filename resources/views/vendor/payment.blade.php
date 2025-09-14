@extends('layouts.app')
@section('title', 'Payment')
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
    
    <div class="w-full flex flex-col gap-4 mb-20">
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 md:gap-1 gap-3">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment</h2>
                <a href="{{ route('vendor.transaction') }}"
                    class="block md:hidden bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Transaction
                </a>
            </div>
            <div class="flex justify-between items-center text-gray-600 text-sm">
                <p>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a> / Vendor
                    /
                    Payment
                </p>
                <a href="{{ route('vendor.transaction') }}"
                    class="hidden md:block bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Transaction
                </a>
            </div>
        </div>

        <div class="w-full bg-white rounded shadow px-6">
            <form action="{{ route('vendor.payment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if (session('error'))
                    <div class="bg-red-600 mt-3 text-white px-4 py-3 rounded flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="white" viewBox="0 0 24 24">
                            <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V7h2v5z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mt-4">
                    <label for="vendor" class="block text-md font-medium text-gray-700 mb-1.5">Vendor <span
                            class="text-red-500">*</span></label>
                    <select id="vendor" name="vendor_id"
                        class="select2 w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                        <option value="">Select a vendor</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="invoice" class="block text-gray-700 font-medium">Invoice <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="invoice_type" id="invoice" placeholder="Invoice type"
                        class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700" value="{{ old('invoice') }}">
                    @error('invoice')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="amount" class="block text-gray-700 font-medium">Amount <span
                            class="text-red-500">*</span></label>
                    <input type="text" step="0.01" name="amount" id="amount" placeholder="Amount"
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
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select a vendor",
                allowClear: true
            });

            flatpickr("#created_at", {
                dateFormat: "d/m/Y",
                defaultDate: "{{ old('created_at', date('d/m/Y')) }}"
            });
        });
    </script>
@endpush
