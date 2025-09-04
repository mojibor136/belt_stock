@extends('layouts.app')
@section('title', 'Pending Memo')
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b rounded-md mb-4">
            <div class="flex flex-col gap-1 w-full md:w-2/3">
                <h1 class="text-2xl font-bold text-gray-800">Pending Memo</h1>
                <p class="text-sm text-gray-500">Manage your pending and their transactions efficiently</p>
            </div>

            <div class="flex flex-row gap-2 mt-3 md:mt-0 w-full md:w-auto items-start sm:items-center">
                <a href="{{ route('memo.create') }}"
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
        <form method="GET" action="{{ route('memo.pending') }}"
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

            <a href="{{ route('memo.pending') }}"
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
                        <th class="px-4 py-3 text-left">Memo No</th>
                        <th class="px-4 py-3 text-left">Debit/Credit</th>
                        <th class="px-4 py-3 text-left">Bill Amount</th>
                        <th class="px-4 py-3 text-left">Final Amount</th>
                        <th class="px-4 py-3 text-center">Memo Date</th>
                        <th class="px-4 py-3 text-center">Action/Status</th>
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
                            <td class="px-4 py-3 font-bold">{{ $m->customer->name }}</td>
                            <td class="px-4 py-3">{{ $m->memo_no }}</td>
                            <td
                                class="px-4 py-3 text-left capitalize 
                                {{ $m->debit_credit_status == 'Debit' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $m->debit_credit_status }} &#2547; ({{ bd_format($m->debit_credit) }})
                            </td>
                            <td class="px-4 py-3 text-left font-semibold">&#2547; {{ bd_format($m->grand_total) }}.00</td>
                            <td class="px-4 py-3 text-left font-semibold">
                                &#2547; {{ bd_format($total) }} .00
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $m->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <span onclick="window.location='{{ route('memo.status', $m->id) }}'"
                                    class="px-3 py-1 rounded text-white capitalize text-sm font-medium
                                {{ $m->memo_status == 'pending' ? 'bg-yellow-500' : 'bg-green-600' }}">
                                    {{ ucfirst($m->memo_status) }}
                                </span>

                                <span onclick="window.location='{{ route('memo.edit', $m->id) }}'"
                                    class="px-3 py-1 rounded text-white capitalize text-sm font-medium bg-green-600 cursor-pointer hover:bg-green-700">
                                    Edit
                                </span>
                                <span onclick="window.location='{{ route('memo.show', $m->id) }}'"
                                    class="px-3 py-1 rounded text-white capitalize text-sm font-medium bg-gray-600 cursor-pointer hover:bg-gray-700">
                                    View
                                </span>

                                <form class="inline-block" action="{{ route('memo.destroy', $m->id) }}" method="POST"
                                    onsubmit="return confirm('আপনি কি নিশ্চিত যে এই মেমো মুছে ফেলতে চান?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded shadow cursor-pointer"
                                        title="Delete">
                                        <i class="ri-delete-bin-6-line text-md"></i>
                                    </button>
                                </form>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(document).ready(function() {
            flatpickr("#created_at", {
                dateFormat: "d/m/Y",
                defaultDate: null
            });
        });
    </script>
@endpush
