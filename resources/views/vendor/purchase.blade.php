@extends('layouts.app')
@section('title', 'Vendor Transaction')
@section('content')
    @include('components.toast')
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

    <div class="bg-white w-full mb-6 flex flex-col">
        <div class="py-6 px-4 mb-4 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 rounded">
            <div class="grid grid-cols-1 md:grid-cols-3 items-center text-center md:text-left gap-4">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <span class="text-gray-700 text-md">
                        Phone: <span class="font-medium">{{ $vendor->phone }}</span>
                    </span>
                    <p class="font-medium text-md text-gray-700">Address: {{ $vendor->address }}</p>
                    <p class="font-medium text-md text-gray-700">Subject: Purchase</p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="text-2xl font-extrabold text-green-700">{{ $vendor->name }}</span>
                    <img src="{{ asset($setting->site_logo) }}" alt="Logo" class="w-44 h-auto" />
                </div>

                <div class="flex flex-col items-center md:items-end gap-2 text-gray-700">
                    <p class="font-medium text-md">Email: {{ $vendor->email }}</p>
                    <p class="font-medium text-md text-gray-700">Transport: Null</p>
                    <p class="font-medium text-MD">Vendor ID: #{{ $vendor->id }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('vendor.purchase', ['name' => Str::slug($vendor->name), 'id' => $vendor->id]) }}"
            method="GET"
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2 sm:gap-4">
            <div class="flex flex-col sm:flex-row w-full sm:w-2/3 gap-2">
                <div class="relative w-full sm:w-1/2">
                    <select name="brand"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">Select Brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <select name="group"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">Select Group</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group') == $group->id ? 'selected' : '' }}>
                                {{ $group->group }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <select name="size"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">Select Sizes</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->size }}" {{ request('size') == $size->size ? 'selected' : '' }}>
                                {{ $size->size }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-1/3">
                    <input type="text" name="created_at" id="created_at" placeholder="dd/mm/yyyy"
                        class="w-full p-2 border rounded border-gray-300 text-gray-700"
                        value="{{ request('created_at') }}">
                </div>
            </div>

            <div class="flex gap-2 mt-2 sm:mt-0">
                <button type="submit"
                    class="flex justify-center items-center px-4 py-2 h-10 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition-all duration-150">
                    <i class="ri-search-line mr-1"></i> Search
                </button>

                <a href="{{ route('vendor.purchase', ['name' => Str::slug($vendor->name), 'id' => $vendor->id]) }}"
                    class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150">
                    Reset
                </a>
            </div>
        </form>

        <div class="bg-white">
            <div class="overflow-x-auto">
                <div class="overflow-x-auto bg-white rounded shadow">
                    <table class="min-w-full table-auto">
                        <thead class="bg-blue-600 text-white text-sm font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Vendor Name</th>
                                <th class="px-4 py-3 text-left">Group</th>
                                <th class="px-4 py-3 text-left">Brand</th>
                                <th class="px-4 py-3 text-center">Size</th>
                                <th class="px-4 py-3 text-center">Quantity</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-left">Purchases</th>
                                <th class="px-4 py-3 text-right pr-8">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                            @forelse($purchases as $index => $purchase)
                                <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">{{ $purchase->vendor->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $purchase->group->group ?? '-' }}</td>
                                    <td class="px-4 py-3 capitalize">{{ $purchase->brand->brand ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $purchase->size ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">{{ $purchase->quantity }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="px-3 py-1 text-xs rounded 
                                    {{ $purchase->status == 'confirm' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">{{ $purchase->created_at->format('d M, Y') }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex justify-end items-center gap-1">
                                            <a href="{{ route('purchase.edit', $purchase->id) }}"
                                                class="inline-flex items-center justify-center w-10 h-8 bg-yellow-500 hover:bg-yellow-600 text-white rounded shadow"
                                                title="Edit">
                                                <i class="ri-edit-line text-lg"></i>
                                            </a>

                                            @php
                                                $statusClasses =
                                                    $purchase->status == 'confirm'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-red-100 text-red-700';

                                                $statusIcon =
                                                    $purchase->status == 'confirm'
                                                        ? 'ri-check-double-line'
                                                        : 'ri-refresh-line';

                                                $statusText = ucfirst($purchase->status);
                                            @endphp

                                            <a href="{{ route('purchase.status', $purchase->id) }}"
                                                class="inline-flex items-center justify-center gap-1 w-24 h-8 {{ $statusClasses }} rounded shadow hover:opacity-90"
                                                title="Change Status">
                                                <i class="{{ $statusIcon }} text-md"></i>
                                                <span>{{ $statusText }}</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                                        No purchases found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($purchases->hasPages())
                    <div class="mt-4 flex justify-end">
                        @if ($purchases->onFirstPage())
                            <span class="px-4 py-2 mr-2 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $purchases->previousPageUrl() }}"
                                class="px-4 py-2 mr-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        @if ($purchases->hasMorePages())
                            <a href="{{ $purchases->nextPageUrl() }}"
                                class="px-4 py-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchBtn').addEventListener('click', filterTable);
        document.getElementById('clearBtn').addEventListener('click', () => {
            document.getElementById('searchInput').value = '';
            filterTable();
        });

        function filterTable() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#transactionsTable tbody tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(query) ? '' : 'none';
            });
        }
    </script>
@endpush
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
