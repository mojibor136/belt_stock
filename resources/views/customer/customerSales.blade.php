@extends('layouts.app')
@section('title', 'Customer Sales')
@section('content')
    <div class="bg-white w-full mb-6 flex flex-col">
        <div class="py-6 px-4 mb-4 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 rounded">
            <div class="grid grid-cols-1 md:grid-cols-3 items-center text-center md:text-left gap-4">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <span class="text-gray-700 text-md">
                        Phone: <span class="font-medium">{{ $customer->phone }}</span>
                    </span>
                    <p class="font-medium text-md text-gray-700">Address: {{ $customer->address }}</p>
                    <p class="font-medium text-md text-gray-700">Subject: <strong>Sales Items</strong></p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="text-2xl font-extrabold text-green-700">{{ $customer->name }}</span>
                    <img src="{{ asset($setting->site_logo) }}" alt="Logo" class="w-44 h-auto" />
                </div>

                <div class="flex flex-col items-center md:items-end gap-2 text-gray-700">
                    <p class="font-medium text-md">Email: {{ $customer->email }}</p>
                    <p class="font-medium text-md text-gray-700">Transport: {{ $customer->transport }}</p>
                    <p class="font-medium text-MD">Customer ID: #{{ $customer->id }}</p>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('customer.sales.items', [$customer->id, Str::slug($customer->name)]) }}"
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2 sm:gap-4 w-full">

            <div class="flex flex-col sm:flex-row w-full sm:w-2/3 gap-2">
                <div class="relative w-full sm:w-1/2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search memo..."
                        class="w-full pl-10 pr-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                    <i
                        class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-base"></i>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <input type="text" name="created_at" id="created_at" placeholder="dd/mm/yyyy"
                        class="w-full p-2 border rounded border-gray-300 text-gray-700" value="{{ request('created_at') }}">
                </div>

                <button type="submit"
                    class="flex justify-center items-center px-4 py-2 h-10 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                    <i class="ri-search-line mr-1"></i> Search
                </button>
            </div>

            <a href="{{ route('customer.sales.items', [$customer->id, Str::slug($customer->name)]) }}"
                class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                Reset
            </a>
        </form>

        <div class="bg-white rounded shadow">
            <div class="overflow-x-auto">
                <div class="overflow-x-auto rounded">
                    <table class="min-w-full table-auto">
                        <thead class="bg-blue-600 text-white text-sm font-semibold">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Memo No</th>
                                <th class="px-4 py-3 text-left">Brand</th>
                                <th class="px-4 py-3 text-center">Group</th>
                                <th class="px-4 py-3 text-center">Size</th>
                                <th class="px-4 py-3 text-center">Quantity</th>
                                <th class="px-4 py-3 text-center">Sales Date</th>
                                <th class="px-4 py-3 text-center">Action</th>
                            </tr>
                        </thead>
                        @php $serial = 1; @endphp
                        <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                            @forelse ($memos as $index => $memo)
                                @foreach ($memo->items as $item)
                                    @foreach ($item->sizes as $size)
                                        <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                                            <td class="px-4 py-3">
                                                {{ $serial++ }}
                                            </td>
                                            <td class="px-4 py-3">{{ $memo->memo_no }}</td>
                                            <td class="px-4 py-3">{{ $item->brand->brand ?? '-' }}</td>
                                            <td class="px-4 py-3 text-center">{{ $item->group->group ?? '-' }}</td>
                                            <td class="px-4 py-3 text-center">{{ $size->size }}</td>
                                            <td class="px-4 py-3 text-center font-semibold">{{ $size->quantity }}</td>
                                            <td class="px-4 py-3 text-center text-gray-600">
                                                {{ $memo->created_at->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 text-center space-x-2">
                                                <span
                                                    onclick="window.location='{{ route('memo.items.return', $size->id) }}'"
                                                    class="px-3 py-2 rounded text-white capitalize text-sm font-medium bg-red-600 cursor-pointer hover:bg-red-700">
                                                    Return
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                        কোনো ডাটা পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($memos->hasPages())
                    <div class="mt-4 flex justify-end">
                        @if ($memos->onFirstPage())
                            <span class="px-4 py-2 mr-2 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $memos->previousPageUrl() }}"
                                class="px-4 py-2 mr-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        @if ($memos->hasMorePages())
                            <a href="{{ $memos->nextPageUrl() }}"
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
        $(document).ready(function() {
            flatpickr("#created_at", {
                dateFormat: "d/m/Y",
                defaultDate: null
            });
        });
    </script>
@endpush
