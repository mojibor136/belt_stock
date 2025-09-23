@extends('layouts.app')
@section('title', 'Memo Details')
@section('content')
    <style>
        @page {
            size: A5;
            margin: 0;
        }

        @media print {
            body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }

            .memo-container {
                box-shadow: none;
            }
        }
    </style>
    <div class="memo-container w-full bg-white shadow-lg print:w-full print:h-full mb-4 print:mb-0">
        <!-- Header -->
        <div class="flex justify-between items-start p-6 border-b border-gray-300 bg-gray-50">
            <!-- Brand Name with Remixicon -->
            <div class="flex flex-col gap-1 items-start">
                <span class="text-2xl font-medium text-gray-600">ইসমাইল & ব্রাদার্স</span>
                <span class="text-2xl font-bold text-gray-600">Ismail & Brothers</span>
            </div>

            <!-- Invoice Info -->
            <div class="text-md flex space-x-6">
                <div class="flex flex-col text-right gap-1">
                    <p class="text-gray-500 font-medium">Invoice Date</p>
                    <p class="text-gray-800 font-semibold">April 26, 2023</p>
                </div>
                <div class="flex flex-col text-right gap-1">
                    <p class="text-gray-500 font-medium">Invoice ID</p>
                    <p class="text-gray-800 font-semibold">BRA-00335</p>
                </div>
            </div>
        </div>

        <!-- Supplier & Customer -->
        <div class="bg-gray-50 px-4 py-3 text-md">
            <div class="flex justify-between">
                <div class="text-neutral-600 flex flex-col gap-1">
                    <p class="font-bold">Supplier Company INC</p>
                    <p>Number: 23456789</p>
                    <p>United States</p>
                </div>
                <div class="text-right text-neutral-600 flex flex-col gap-1">
                    <p class="font-bold">Customer Company</p>
                    <p>Number: 123456789</p>
                    <p>9552 Vandervort Spurs</p>
                </div>
            </div>
        </div>
        <div class="px-2 py-2 flex flex-col">
            <div class="mt-1 overflow-x-auto text-neutral-700">
                <table class="w-full border-collapse">
                    <thead class="bg-blue-400 border border-blue-400">
                        <tr class="text-white text-sm">
                            <th class="px-2 py-2.5 text-left border border-gray-300">Group</th>
                            <th class="px-2 py-2 text-center border border-gray-300">Description</th>
                            <th class="px-2 py-2 text-center border border-gray-300">Brand</th>
                            <th class="px-2 py-2 text-center border border-gray-300">Rate</th>
                            <th class="px-2 py-2 text-center border border-gray-300">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-neutral-700">
                        @php
                            $maxDataRows = 14;
                            $rowsUsed = 0;
                            $grandTotal = 0;
                            $debitAmount = $data['debit'] ?? 0;
                            $customerStatus = strtolower($data['customer_status'] ?? '');
                        @endphp

                        @foreach ($data['items'] as $item)
                            @php
                                $grandTotal += $item['item_total'] ?? 0;
                                $usePieceRate = $item['rate'] == 0 && isset($item['piece_rate']);
                                $rateToShow = $usePieceRate ? $item['piece_rate'] . ' PS' : $item['rate'];
                                $sizeChunks = array_chunk($item['sizes'], 5);
                            @endphp

                            @foreach ($sizeChunks as $chunkIndex => $chunk)
                                @if ($rowsUsed >= $maxDataRows)
                                    @break

                                    2
                                @endif
                                <tr>
                                    <td class="border border-gray-300 px-2 py-1 w-6 text-center">
                                        @if ($chunkIndex == 0)
                                            <span class="text-lg">{{ $item['group_name'] ?? 'Unknown' }}-</span>
                                        @else
                                            &nbsp;
                                        @endif
                                    </td>

                                    <td class="border border-gray-300 px-2 py-1">
                                        <span>
                                            @foreach ($chunk as $size)
                                                <span class="mr-2">{{ $size['size'] }}X{{ $size['quantity'] }}</span>
                                            @endforeach
                                        </span>
                                    </td>

                                    @if ($chunkIndex == 0)
                                        <td class="border border-gray-300 px-2 py-1 text-center w-14">
                                            {{ $item['brand_name'] ?? 'HC' }}
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-center w-10">
                                            {{ $rateToShow }}
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-center w-20">
                                            &#2547;{{ number_format($item['item_total'], 2) }}
                                        </td>
                                    @else
                                        <td class="border border-gray-300 px-2 py-1 text-center w-14">&nbsp;
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-center w-10">&nbsp;
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-center w-20">&nbsp;
                                        </td>
                                    @endif
                                </tr>
                                @php $rowsUsed++; @endphp
                            @endforeach
                        @endforeach

                        @for ($i = $rowsUsed; $i < $maxDataRows; $i++)
                            <tr>
                                <td class="border border-gray-300 px-2 py-1 w-6 text-center"></td>
                                <td class="border border-gray-300 px-2 py-1">&nbsp;</td>
                                <td class="border border-gray-300 px-2 py-1 text-center w-14">&nbsp;</td>
                                <td class="border border-gray-300 px-2 py-1 text-center w-10">&nbsp;</td>
                                <td class="border border-gray-300 px-2 py-1 text-center w-20">&nbsp;</td>
                            </tr>
                        @endfor

                        @php
                            $grandTotal = 0;
                            foreach ($data['items'] as $item) {
                                $grandTotal += $item['item_total'] ?? 0;
                            }

                            if (isset($data['debit_credit_status']) && isset($data['debit_credit'])) {
                                if ($data['debit_credit_status'] === 'debit') {
                                    $grandTotal += $data['debit_credit'];
                                } elseif ($data['debit_credit_status'] === 'credit') {
                                    $grandTotal -= $data['debit_credit'];
                                }
                            }
                        @endphp
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
