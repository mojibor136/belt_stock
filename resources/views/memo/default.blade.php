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
    <div class="memo-container w-full bg-white shadow-lg print:w-full print:h-full print:mb-0">
        <div class="flex flex-col items-center px-6 py-2 border-b border-gray-300 bg-gray-50 text-center">
            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold text-gray-800">ইসমাইল & ব্রাদার্স</span>
                |
                <span class="text-2xl font-bold text-gray-800">Ismail & Brothers</span>
            </div>

            <!-- Address & Phone -->
            <p class="text-gray-600">123 Street Name, City, Country</p>
            <p class="text-gray-600">Phone: +880 1234 567890</p>

            <!-- Company Description -->
            <p class="text-gray-500 text-sm max-w-2xl">
                আমাদের কোম্পানি বিভিন্ন ধরনের মানসম্পন্ন প্রোডাক্ট সরবরাহ করে।
                আমরা গ্রাহকের চাহিদা অনুযায়ী সেরা পরিষেবা প্রদান করি এবং সর্বদা
            </p>
        </div>

        <!-- Supplier & Customer -->
        <div class="bg-gray-50 px-4 py-2 text-md">
            <div class="flex justify-between">
                <div class="text-left text-neutral-600 flex flex-col gap-0.5">
                    <p>Name: {{ $data['customer_name'] }}</p>
                    <p>Address: {{ $data['customer_address'] }}</p>
                </div>
                <div class="text-left text-neutral-600 flex flex-col gap-1">
                    <p>Date: {{ date('d m Y', strtotime($memo->updated_at)) }}
                    </p>
                    <p>Invoice ID: {{ $memo->memo_no }}</p>
                </div>
            </div>
        </div>
        <div class="px-2 py-0 flex flex-col">
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
