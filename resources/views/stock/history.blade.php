@extends('layouts.app')
@section('title', 'History Management')
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
    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b rounded-md mb-4">
            <div class="flex flex-col gap-2 w-full md:w-2/3">
                <h1 class="text-2xl font-bold text-gray-800">History Management</h1>
                <p class="text-sm text-gray-500 ml-1">Manage your history and their transactions efficiently</p>
            </div>
        </div>

        <form method="GET"
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2 sm:gap-4">
            <div class="flex flex-col sm:flex-row w-full sm:w-2/3 gap-2">

                <div class="relative w-full sm:w-1/2">
                    <select name="brand"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">All Brands</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->brand }}" {{ request('brand') == $brand->brand ? 'selected' : '' }}>
                                {{ $brand->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <select name="group"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">All Groups</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->group }}" {{ request('group') == $group->group ? 'selected' : '' }}>
                                {{ $group->group }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <select name="size"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">All Sizes</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->size }}" {{ request('size') == $size->size ? 'selected' : '' }}>
                                {{ $size->size }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <input type="text" id="created_at" name="created_at" value="{{ request('created_at') }}"
                        placeholder="dd/mm/yyyy"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                </div>
            </div>

            <div class="flex gap-2 mt-2 sm:mt-0">
                <button type="submit"
                    class="flex justify-center items-center px-4 py-2 h-10 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition-all duration-150">
                    <i class="ri-search-line mr-1"></i> Search
                </button>

                <a href="{{ route('stocks.history') }}"
                    class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150">
                    Back
                </a>
            </div>
        </form>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Group</th>
                        <th class="px-4 py-3 text-left">Size</th>
                        <th class="px-4 py-3 text-left">Brand</th>
                        <th class="px-4 py-3 text-center">Quantity</th>
                        <th class="px-4 py-3 text-center">History/Type</th>
                        <th class="px-4 py-3 text-center">History Date</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @forelse($histories as $index => $history)
                        <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-left">{{ $history->group }}</td>
                            <td class="px-4 py-3">{{ $history->size }}</td>
                            <td class="px-4 py-3 capitalize">{{ $history->brand }}</td>
                            <td class="px-4 py-3 text-center">{{ $history->quantity }}</td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $type = strtolower($history->type);
                                    $styles = [
                                        'add' => ['label' => 'Added', 'class' => 'bg-green-100 text-green-700'],
                                        'edit' => ['label' => 'Edited', 'class' => 'bg-yellow-100 text-yellow-700'],
                                        'sales' => ['label' => 'Sold Out', 'class' => 'bg-indigo-100 text-indigo-900'],
                                        'purchase' => ['label' => 'Purchased', 'class' => 'bg-blue-100 text-blue-700'],
                                        'transfer' => [
                                            'label' => 'Transferred',
                                            'class' => 'bg-purple-100 text-purple-700',
                                        ],
                                        'return' => ['label' => 'Returned', 'class' => 'bg-pink-100 text-pink-700'],
                                        'delete' => ['label' => 'Deleted', 'class' => 'bg-red-100 text-red-700'],
                                        'adjust' => ['label' => 'Adjusted', 'class' => 'bg-orange-100 text-orange-700'],
                                    ];
                                    $styleData = $styles[$type] ?? [
                                        'label' => ucfirst($type),
                                        'class' => 'bg-gray-200 text-gray-700',
                                    ];
                                @endphp

                                <button
                                    class="capitalize inline-flex justify-center items-center w-20 h-8 rounded font-semibold text-xs {{ $styleData['class'] }}">
                                    {{ $styleData['label'] }}
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{ \Carbon\Carbon::parse($history->created_at)->format('d M, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500">No history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($histories->hasPages())
            <div class="mt-4 flex justify-end">
                @if ($histories->onFirstPage())
                    <span class="px-4 py-2 mr-2 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                        Previous
                    </span>
                @else
                    <a href="{{ $histories->previousPageUrl() }}"
                        class="px-4 py-2 mr-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Previous
                    </a>
                @endif

                @if ($histories->hasMorePages())
                    <a href="{{ $histories->nextPageUrl() }}"
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
