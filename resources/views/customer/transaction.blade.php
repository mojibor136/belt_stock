@extends('layouts.app')
@section('title', 'Transaction')
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b rounded-md mb-4">
            <div class="flex flex-col gap-1 w-full md:w-2/3">
                <h1 class="text-2xl font-bold text-gray-800">Transaction</h1>
                <p class="text-sm text-gray-500">Manage your customers and their transactions efficiently</p>
            </div>
        </div>

        <form method="GET" action="{{ route('customer.transaction') }}"
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2 sm:gap-4">

            <div class="flex flex-col sm:flex-row w-full sm:w-2/3 gap-2">

                <div class="relative w-full sm:w-1/3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transaction..."
                        class="w-full pl-10 pr-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                    <i
                        class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-base"></i>
                </div>

                <div class="relative w-full sm:w-1/3">
                    <select name="status"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">All Status</option>
                        <option value="credit" {{ request('status') == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ request('status') == 'debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>

                <div class="relative w-full sm:w-1/3">
                    <input type="date" id="created_at" name="date" value="{{ request('date') }}"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                </div>

                <button type="submit"
                    class="flex justify-center items-center px-4 py-2 h-10 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                    <i class="ri-search-line mr-1"></i> Search
                </button>
            </div>

            <a href="{{ route('customer.transaction') }}"
                class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                Back
            </a>
        </form>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Payment</th>
                        <th class="px-4 py-3 text-left">Invoice</th>
                        <th class="px-4 py-3 text-left">Final Amount</th>
                        <th class="px-4 py-3 text-left">Credit / Debit</th>
                        <th class="px-4 py-3 text-center">Created At</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @foreach ($transactions as $index => $trx)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <td class="px-4 py-3">{{ $index + $transactions->firstItem() }}</td>
                            <td class="px-4 py-3 font-bold text-[13px]">
                                {{ $trx->customer ? $trx->customer->name : 'N/A' }}
                            </td>
                            <td class="px-4 py-3">{{ $trx->invoice_type }}</td>
                            <td class="px-4 py-3">
                                ৳ {{ number_format($trx->payment) }} .00
                            </td>
                            <td class="px-4 py-3">
                                ৳ {{ number_format($trx->invoice) }} .00
                            </td>
                            <td class="px-4 py-3">
                                ৳ {{ number_format($trx->debit_credit) }} .00
                            </td>
                            <td class="px-4 py-3">
                                @if ($trx->status === 'credit')
                                    <span
                                        class="px-8 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white shadow">
                                        Credit
                                    </span>
                                @else
                                    <span
                                        class="px-8 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-red-400 to-red-600 text-white shadow">
                                        Debit
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            flatpickr("#created_at", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ request('date') ?? now()->format('Y-m-d') }}"
            });
        });
    </script>
@endpush
