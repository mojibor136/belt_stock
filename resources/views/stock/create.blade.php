@extends('layouts.app')
@section('title', 'Stock Management')
@section('content')
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

    @include('components.toast')
    <div class="w-full flex flex-col gap-4 mb-20">
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 md:gap-1 gap-3">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Stock</h2>
                <a href="{{ route('stocks.index') }}"
                    class="block md:hidden bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Group
                </a>
            </div>
            <div class="flex justify-between items-center text-gray-600 text-sm">
                <p>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a> / Stock
                    /
                    Create
                </p>
                <a href="{{ route('stocks.index') }}"
                    class="hidden md:block bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Stock
                </a>
            </div>
        </div>

        <div class="w-full bg-white rounded shadow px-6">
            <form action="{{ route('stocks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mt-4">
                    <label for="brand" class="block text-md font-medium text-gray-700 mb-1.5">
                        Brand <span class="text-red-500">*</span>
                    </label>
                    <select id="brand" name="brand"
                        class="brand w-full border border-gray-300 rounded px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none">
                        <option value="">-- Select Brand --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->brand }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="group" class="block text-md font-medium text-gray-700 mb-1.5">
                        Group <span class="text-red-500">*</span>
                    </label>
                    <select id="group" name="group"
                        class="group w-full border border-gray-300 rounded px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none">
                        <option value="">-- Select Group --</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="size" class="block text-md font-medium text-gray-700 mb-1.5">
                        Size <span class="text-red-500">*</span>
                    </label>
                    <select id="size" name="size"
                        class="size w-full border border-gray-300 rounded px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none">
                        <option value="">-- Select Size --</option>
                    </select>
                </div>

                <div class="mt-4">
                    <label for="quantity" class="block text-md font-medium text-gray-700 mb-1.5">Quantity <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="quantity" name="quantity" placeholder="Enter Quantity"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700" />
                </div>

                <div class="mt-4">
                    <label for="alert" class="block text-md font-medium text-gray-700 mb-1.5">Alert Quantity <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="alert" name="alert" placeholder="Enter AlertQuantity"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700" />
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

                <div class="flex justify-end mb-6 mt-4">
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
            $('#brand').select2({
                placeholder: "-- Select Brand --",
                allowClear: true
            });

            $('#group').select2({
                placeholder: "-- Select Group --",
                allowClear: true
            });

            $('#size').select2({
                placeholder: "-- Select Size --",
                allowClear: true
            });

            flatpickr("#created_at", {
                dateFormat: "d/m/Y",
                defaultDate: "{{ old('created_at', date('d/m/Y')) }}"
            });


            $('#brand').on('change', function() {
                let brandId = $(this).val();
                $('#group').empty().append('<option value="">-- Select Group --</option>');
                $('#size').empty().append('<option value="">-- Select Size --</option>');

                if (brandId) {
                    $.get("{{ url('get-groups-by-brand') }}", {
                        brand_id: brandId
                    }, function(data) {
                        $.each(data, function(index, group) {
                            $('#group').append('<option value="' + group.id + '">' + group
                                .group + '</option>');
                        });
                    });
                }
            });

            $('#group').on('change', function() {
                let groupId = $(this).val();
                $('#size').empty().append('<option value="">-- Select Size --</option>');

                if (groupId) {
                    $.get("{{ url('get-sizes-by-group') }}", {
                        group_id: groupId
                    }, function(data) {
                        $.each(data, function(index, size) {
                            $('#size').append('<option value="' + size.id + '">' + size
                                .size + '</option>');
                        });
                    });
                }
            });

        });
    </script>
@endpush
