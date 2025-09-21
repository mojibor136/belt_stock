@extends('layouts.app')
@section('title', 'Edit Size')
@section('content')
    @include('components.toast')
    <div class="w-full flex flex-col gap-4 mb-20">
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 md:gap-1 gap-3">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Size Management</h2>
                <a href="{{ route('sizes.index') }}"
                    class="block md:hidden bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Sizes
                </a>
            </div>
            <div class="flex justify-between items-center text-gray-600 text-sm">
                <p>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a> / Size / Edit
                </p>
                <a href="{{ route('sizes.index') }}"
                    class="hidden md:block bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Sizes
                </a>
            </div>
        </div>

        <div class="w-full bg-white rounded shadow px-6">
            <form action="{{ route('sizes.update') }}" method="POST">
                @csrf

                <input type="hidden" name="id" value="{{ $size->id }}">

                <div class="mt-4">
                    <label for="brand" class="block text-md font-medium text-gray-700 mb-1.5">Brand <span
                            class="text-red-500">*</span></label>
                    <select id="brand" name="brand"
                        class="select2 text-gray-700 w-full border border-gray-300 rounded px-3 py-2 text-md focus:ring-1 focus:ring-blue-600">
                        <option value="">Select a brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $size->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="group" class="block text-md font-medium text-gray-700 mb-1.5">Group <span
                            class="text-red-500">*</span></label>
                    <select id="group" name="group"
                        class="select2 text-gray-700 w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-600">
                        <option value="">Select a group</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ $size->group_id == $group->id ? 'selected' : '' }}>
                                {{ $group->group }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4">
                    <label for="size" class="block text-md font-medium text-gray-700 mb-1.5">Size</label>
                    <input type="number" id="size" name="size" value="{{ old('size', $size->size) }}"
                        class="w-full text-gray-700 border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600" />
                </div>

                <div class="mt-4">
                    <label for="cost_rate" class="block text-md font-medium text-gray-700 mb-1.5">Cost Rate</label>
                    <input type="text" id="cost_rate" name="cost_rate" value="{{ old('cost_rate', $size->cost_rate) }}"
                        class="w-full text-gray-700 border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600" />
                </div>

                <div class="mt-4">
                    <label for="sales_rate" class="block text-md font-medium text-gray-700 mb-1.5">Sales Rate</label>
                    <input type="text" id="sales_rate" name="sales_rate"
                        value="{{ old('sales_rate', $size->sales_rate) }}"
                        class="w-full text-gray-700 border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600" />
                </div>

                <div class="mt-4">
                    <label for="rate_type" class="block text-md font-medium text-gray-700 mb-1.5">Rate Type</label>
                    <select id="rate_type" name="rate_type"
                        class="w-full text-gray-700 border border-gray-300 rounded px-3 py-2 text-md focus:ring-1 focus:ring-blue-600">
                        <option value="inch" {{ $size->rate_type == 'inch' ? 'selected' : '' }}>Inch</option>
                        <option value="pieces" {{ $size->rate_type == 'pieces' ? 'selected' : '' }}>Pieces</option>
                    </select>
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
            $('#brand').on('change', function() {
                var brandID = $(this).val();
                $('#group').empty().append('<option value="">Select a group</option>');
                $('#cost_rate').val('');
                $('#sales_rate').val('');

                if (brandID) {
                    $.ajax({
                        url: "{{ route('get.groups.by.brand') }}",
                        type: 'GET',
                        data: {
                            brand_id: brandID
                        },
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#group').append('<option value="' + value.id + '">' +
                                    value.group + '</option>');
                            });
                        }
                    });
                }
            });

            $('#group').on('change', function() {
                var groupID = $(this).val();
                if (groupID) {
                    $.ajax({
                        url: "{{ route('get.group.rate') }}",
                        type: 'GET',
                        data: {
                            group_id: groupID
                        },
                        success: function(data) {
                            $('#cost_rate').val(data.cost_rate);
                            $('#sales_rate').val(data.sales_rate);
                        }
                    });
                } else {
                    $('#cost_rate').val('');
                    $('#sales_rate').val('');
                }
            });
        });
    </script>
@endpush
