@extends('layouts.app')
@section('title', 'Group Management')
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
    </style>

    @include('components.toast')

    <div class="w-full flex flex-col gap-4 mb-20">
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 md:gap-1 gap-3">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Group Management</h2>
                <a href="{{ route('groups.index') }}"
                    class="block md:hidden bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Group
                </a>
            </div>
            <div class="flex justify-between items-center text-gray-600 text-sm">
                <p>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a> / Group
                    /
                    Edit
                </p>
                <a href="{{ route('groups.index') }}"
                    class="hidden md:block bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Group
                </a>
            </div>
        </div>

        <div class="w-full bg-white rounded shadow px-6">
            <form action="{{ route('groups.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" value="{{ $group->id }}" name="id">

                <div class="mt-4">
                    <label for="brand" class="block text-md font-medium text-gray-700 mb-1.5">Brand <span
                            class="text-red-500">*</span></label>
                    <select id="brand" name="brand_id"
                        class="select2 w-full border border-gray-300 rounded px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                        <option value="">Select a brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $group->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="group" class="block text-md font-medium text-gray-700 mb-1.5">Group</label>
                    <input type="text" id="group" name="group" value="{{ $group->group }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700" />
                </div>

                <div class="mt-4">
                    <label for="cost_rate" class="block text-md font-medium text-gray-700 mb-1.5">Cost Rate</label>
                    <input type="text" id="cost_rate" name="cost_rate" value="{{ $group->cost_rate }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700" />
                </div>

                <div class="mt-4">
                    <label for="sales_rate" class="block text-md font-medium text-gray-700 mb-1.5">Sales Rate</label>
                    <input type="text" id="sales_rate" name="sales_rate" value="{{ $group->sales_rate }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700" />
                </div>

                <div class="flex justify-end mb-6 mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Update
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
                placeholder: "-- Select Dokan --",
                allowClear: true
            });
        });
    </script>
@endpush
