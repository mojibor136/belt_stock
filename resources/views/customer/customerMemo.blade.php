@extends('layouts.app')
@section('title', 'Customer Memo')
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
    <div class="bg-white w-full mb-6 flex flex-col">
        <div class="py-6 px-4 mb-4 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 rounded">
            <div class="grid grid-cols-1 md:grid-cols-3 items-center text-center md:text-left gap-4">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <span class="text-gray-700 text-md">
                        Phone: <span class="font-medium">{{ $customer->phone }}</span>
                    </span>
                    <p class="font-medium text-md text-gray-700">Address: {{ $customer->address }}</p>
                    <p class="font-medium text-md text-gray-700">Subject: Memo</p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="text-2xl font-extrabold text-green-700">{{ $customer->name }}</span>
                    <img src="{{ asset($setting->site_logo) }}" alt="Logo" class="w-44 h-auto" />
                </div>

                <div class="flex flex-col items-center md:items-end gap-2 text-gray-700">
                    <p class="font-medium text-md">Email: {{ $customer->email }}</p>
                    <p class="font-medium text-md text-gray-700">Transport: {{ $customer->transport }}</p>
                    <p class="font-medium text-MD">Customer ID: #1023</p>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('customer.memo', [$customer->id, Str::slug($customer->name)]) }}"
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

            <a href="{{ route('customer.memo', [$customer->id, Str::slug($customer->name)]) }}"
                class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                Reset
            </a>
        </form>

        <div class="bg-white rounded shadow">
            <div class="overflow-x-auto">
                <div class="overflow-x-auto rounded">
                    <table class="min-w-full table-auto">
                        <thead class="bg-blue-600 text-white text-sm">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
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
                                return preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $otherNumbers) .
                                    $lastThree .
                                    $afterPoint;
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
                                    <td class="px-4 py-3">{{ $m->memo_no }}</td>
                                    <td class="px-4 py-3 text-left">
                                        <div class="inline-flex items-center space-x-2">
                                            <span
                                                class="px-3 py-1 rounded-full text-xs capitalize
                                        {{ $m->debit_credit_status == 'debit' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $m->debit_credit_status }} &#2547; {{ bd_format($m->debit_credit) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-left text-gray-700">&#2547;
                                        {{ bd_format($m->grand_total) }}.00</td>
                                    <td class="px-4 py-3 text-left text-gray-700">
                                        &#2547; {{ bd_format($total) }} .00
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700">{{ $m->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-center space-x-2">
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
