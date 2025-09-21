@extends('layouts.app')
@section('title', 'Brand Management')
@section('content')
    <style>
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
    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b rounded-md mb-4">
            <div class="flex flex-col gap-2 w-full md:w-2/3">
                <h1 class="text-2xl font-bold text-gray-800">Brand Management</h1>
                <p class="text-sm text-gray-500 ml-1">Manage your brands and their transactions efficiently</p>
            </div>

            <div class="flex flex-row gap-2 mt-3 md:mt-0 w-full md:w-auto items-start sm:items-center">
                <a href="{{ route('brands.create') }}"
                    class="flex items-center gap-2 h-10 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-add-line text-lg"></i>Add Brand
                </a>

                <button
                    class="flex items-center gap-2 h-10 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-download-line"></i> Export
                </button>
            </div>
        </div>

        <form action="{{ route('brands.index') }}" method="GET"
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2 sm:gap-4">
            <div class="flex flex-col sm:flex-row w-full sm:w-2/3 gap-2">

                <div class="relative w-full sm:w-1/2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search brands..."
                        class="w-full pl-10 pr-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                    <i
                        class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-base"></i>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <input type="date" id="created_at" name="date" value="{{ request('date') }}"
                        placeholder="dd/mm/yyyy"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                </div>

                <button type="submit"
                    class="flex justify-center items-center px-4 py-2 h-10 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                    <i class="ri-search-line mr-1"></i> Search
                </button>
            </div>

            <a href="{{ route('brands.index') }}"
                class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                Reset
            </a>
        </form>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Brand</th>
                        <th class="px-4 py-3 text-center">Total Group</th>
                        <th class="px-4 py-3 text-center">Total Size</th>
                        <th class="px-4 py-3 text-center">Inchi</th>
                        <th class="px-4 py-3 text-center">Value</th>
                        <th class="px-4 py-3 text-center">Created At</th>
                        <th class="px-4 py-3 text-right pr-8">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @forelse ($brands as $index => $brand)
                        <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 capitalize">{{ $brand->brand }}</td>
                            <td class="px-4 py-3 text-center">{{ $brand->groups_count }}</td>
                            <td class="px-4 py-3 text-center">{{ $brand->sizes_count }}</td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $total_inchi = 0;
                                    $total_value = 0;

                                    foreach ($brand->groups as $group) {
                                        foreach ($group->sizes as $size) {
                                            $quantity = $size->stocks->sum('quantity');

                                            if ($size->rate_type === 'inch') {
                                                $total_inchi += $size->size * $quantity;
                                            }

                                            $total_value += $size->size * $size->cost_rate * $quantity;
                                        }
                                    }
                                @endphp
                                {{ number_format($total_inchi, 2) }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{ number_format($total_value, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">{{ $brand->created_at->format('d M, Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end items-center gap-1">
                                    <a href="{{ route('brands.edit', $brand->id) }}"
                                        class="inline-flex items-center justify-center w-10 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow"
                                        title="Edit">
                                        <i class="ri-edit-line text-lg"></i>
                                    </a>

                                    <a href="{{ route('brands.show', $brand->id) }}"
                                        class="inline-flex items-center justify-center w-10 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded shadow"
                                        title="View">
                                        <i class="ri-eye-line text-lg"></i>
                                    </a>

                                    <form action="{{ route('brands.destroy', $brand->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-10 h-8 bg-red-500 hover:bg-red-600 text-white rounded shadow"
                                            title="Delete">
                                            <i class="ri-delete-bin-6-line text-md"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                                No groups found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            flatpickr("#created_at", {
                dateFormat: "d/m/Y",
                defaultDate: null
            });
        });
    </script>
@endpush
