@extends('layouts.app')
@section('title', 'Complete Memo')
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

    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b rounded-md mb-4">
            <div class="flex flex-col gap-2 w-full md:w-2/3">
                <h1 class="text-2xl font-bold text-gray-800">Complete Memo</h1>
                <p class="text-sm text-gray-500 ml-1">Manage your complete and their transactions efficiently</p>
            </div>

            <div class="flex flex-row gap-2 mt-3 md:mt-0 w-full md:w-auto items-start sm:items-center">
                <a href=""
                    class="flex items-center gap-2 h-10 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-add-line text-lg"></i> Create Memo
                </a>

                <button
                    class="flex items-center gap-2 h-10 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-download-line"></i> Export
                </button>
            </div>
        </div>

        <!-- Filter -->
        <form method="GET" action="{{ route('memo.complete') }}"
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

            <a href="{{ route('memo.complete') }}"
                class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                Reset
            </a>
        </form>

        <!-- Sales Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Memo No</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Debit/Credit</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Bill Amount</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap">Final Amount</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Memo Date</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Action/Status</th>
                    </tr>
                </thead>
                @php
                    function bd_format($number)
                    {
                        $number = (float) $number;
                        $afterPoint = '';
                        if (strpos($number, '.') !== false) {
                            $afterPoint = substr($number, strpos($number, '.'), strlen($number));
                        }
                        $number = floor($number);
                        $lastThree = substr($number, -3);
                        $otherNumbers = substr($number, 0, -3);
                        if ($otherNumbers != '') {
                            $lastThree = ',' . $lastThree;
                        }
                        return preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $otherNumbers) . $lastThree . $afterPoint;
                    }
                @endphp
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @forelse ($memo as $index => $m)
                        @php
                            $grandTotal = (float) $m->grand_total;
                            $debitCredit = (float) $m->debit_credit;
                            $total = 0;

                            if ($m->debit_credit_status == 'debit') {
                                $total = $debitCredit + $grandTotal;
                            } else {
                                $total = $debitCredit - $grandTotal;
                            }
                        @endphp
                        <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $m->customer->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $m->memo_no }}</td>
                            <td class="px-4 py-3 text-left whitespace-nowrap">
                                <div class="inline-flex items-center space-x-2 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold capitalize
                                        {{ $m->debit_credit_status == 'debit' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $m->debit_credit_status }} &#2547; {{ bd_format($m->debit_credit) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-left whitespace-nowrap">&#2547;
                                {{ bd_format($m->grand_total) }}.00</td>
                            <td class="px-4 py-3 text-left whitespace-nowrap">
                                &#2547; {{ bd_format($total) }} .00
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600 whitespace-nowrap">
                                {{ $m->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span
                                    class="inline-flex items-center justify-center h-8 px-3 rounded text-white capitalize text-sm font-medium
                                    {{ $m->memo_status == 'pending' ? 'bg-yellow-500' : 'bg-green-600' }}">
                                    {{ ucfirst($m->memo_status) }}
                                </span>

                                <span onclick="window.location='{{ route('memo.show', $m->id) }}'"
                                    class="inline-flex items-center justify-center h-8 px-3 rounded text-white capitalize text-sm font-medium bg-red-600 cursor-pointer hover:bg-red-700">
                                    View
                                </span>
                            </td>
                        </tr>
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
