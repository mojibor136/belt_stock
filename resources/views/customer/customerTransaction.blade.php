@extends('layouts.app')
@section('title', 'Customer Transaction')
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
                        Phone: <span class="font-medium">{{ $customer->phone }}</span>
                    </span>
                    <p class="font-medium text-md text-gray-700">Address: {{ $customer->address }}</p>
                    <p class="font-medium text-md text-gray-700">Subject: <strong>Transaction</strong></p>
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

        <form method="GET"
            action="{{ route('customer.all.transaction', [\Illuminate\Support\Str::slug($customer->name), $customer->id]) }}"
            class="py-4 flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..."
                class="flex-grow px-4 py-2 border border-gray-300 text-gray-700 rounded focus:outline-none focus:ring-1 focus:ring-blue-600" />

            <div class="relative w-full sm:w-1/3">
                <input type="date" id="created_at" name="date" value="{{ request('date') }}" placeholder="dd/mm/yyyy"
                    class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
            </div>

            <button type="submit" class="w-10 h-10 text-sm bg-indigo-600 rounded text-white hover:bg-indigo-700 transition"
                title="Search">
                <i class="ri-search-line text-lg"></i>
            </button>

            <a href="{{ route('customer.all.transaction', [\Illuminate\Support\Str::slug($customer->name), $customer->id]) }}"
                class="w-20 h-10 flex items-center justify-center bg-red-600 rounded text-sm text-white hover:bg-red-700 transition"
                title="Reset Search">
                Reset
            </a>
        </form>

        <div class="bg-white">
            <div class="overflow-x-auto">
                <div class="overflow-x-auto bg-white rounded shadow">
                    <table id="transactionsTable" class="w-full border-collapse table-auto rounded">
                        <thead>
                            <tr class="bg-blue-600 text-white text-sm font-semibold">
                                <th class="px-4 py-4 text-left text-xs uppercase whitespace-nowrap">Date</th>
                                <th class="px-4 py-4 text-left text-xs uppercase whitespace-nowrap">Type</th>
                                <th class="px-4 py-4 text-left text-xs uppercase whitespace-nowrap">Payment</th>
                                <th class="px-4 py-4 text-left text-xs uppercase whitespace-nowrap">Invoice</th>
                                <th class="px-4 py-4 text-left text-xs uppercase whitespace-nowrap">Credit / Debit</th>
                                <th class="px-4 py-4 text-left text-xs uppercase whitespace-nowrap">Amount</th>
                                <th class="px-4 py-4 text-center text-xs uppercase whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $trx)
                                <tr
                                    class="border-b hover:bg-gray-50 text-gray-700 text-sm cursor-pointer {{ $trx->invoice_status === 'invoice' ? 'bg-green-100' : 'bg-red-100' }}">
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $trx->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">{{ $trx->invoice_type }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap"> ৳ {{ number_format($trx->payment) }} .00</td>
                                    <td class="px-4 py-3 whitespace-nowrap"> ৳ {{ number_format($trx->invoice) }} .00</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if ($trx->status === 'credit')
                                            <span
                                                class="px-8 py-1 text-xs rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white shadow">
                                                Credit
                                            </span>
                                        @else
                                            <span
                                                class="px-8 py-1 text-xs rounded-full bg-gradient-to-r from-red-400 to-red-600 text-white shadow">
                                                Debit
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 whitespace-nowrap {{ $trx->status == 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                        ৳ {{ number_format($trx->debit_credit) }} .00
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <form action="{{ route('customer.transactions.destroy', $trx->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('আপনি কি এই লেনদেনটি মুছে ফেলতে চান?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-6 py-1 text-white bg-red-600 hover:bg-red-700 rounded-3xl text-xs transition">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($transactions->hasPages())
                    <div class="mt-4 flex justify-end">
                        @if ($transactions->onFirstPage())
                            <span class="px-4 py-2 mr-2 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $transactions->previousPageUrl() }}"
                                class="px-4 py-2 mr-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        @if ($transactions->hasMorePages())
                            <a href="{{ $transactions->nextPageUrl() }}"
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
