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

            .print-btn {
                display: none !important;
            }
        }

        .print-btn {
            position: fixed;
            right: 32px;
            bottom: 32px;
            z-index: 50;
            background: #2563eb;
            color: #fff;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            transition: background 0.2s;
        }

        .print-btn:hover {
            background: #1d4ed8;
        }

        .print-btn svg {
            width: 28px;
            height: 28px;
        }
    </style>

    <button class="print-btn" onclick="window.print()" title="Print">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M7 17v2a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="2" />
            <rect x="3" y="7" width="18" height="8" rx="2" stroke="currentColor" stroke-width="2" />
            <path d="M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="2" />
            <circle cx="17" cy="12" r="1" fill="currentColor" />
        </svg>
    </button>

    @php
        $shopNames = is_array($setting->shop_name) ? $setting->shop_name : [];
        $firstThree = array_slice($shopNames, 0, 2);

        $shopName = old('shop_name', implode(', ', $firstThree));

        $shopAddress = old('shop_address', isset($setting->shop_address) ? implode(', ', $setting->shop_address) : '');

        $shopPhone = old('shop_phone', isset($setting->shop_phone) ? implode(', ', $setting->shop_phone) : '');
    @endphp

    <div class="memo-container w-full bg-green-100/50 shadow-lg print:w-full print:h-full mb-4 print:mb-0">
        <div class="flex justify-between items-center border border-white">
            <div class="bg-green-600 h-8 flex items-center text-yellow-300 flex-1 px-4 justify-between">
                <span class="text-xl font-semibold tracking-widest"><i>MOON DELUX</i></span>
                <span></span>
            </div>
            <div class="bg-gray-800 h-8 flex items-center text-white flex-1 px-4 justify-between">
                <span class="text-xl font-semibold tracking-widest"><i>HANGCHANG</i></span>
                <span></span>
            </div>
        </div>

        <div class="py-2 px-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center justify-center gap-2">
                    <div
                        class="w-16 h-12 border border-red-700 flex justify-center items-center bg-white font-bold text-4xl rounded">
                        <span class="text-blue-800">B</span> <span class="text-orange-600">S</span>
                    </div>
                    <div class="flex flex-col items-start justify-center">
                        <span class="text-blue-900 text-xl font-semibold tracking-widest">বেঙ্গল বেয়ারিং ও বেল্ট
                            স্টোর</span>
                        <span class="text-red-600 text-xl font-semibold tracking-widest">BENGAL BEARING & BELT STORE</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-center text-md text-gray-700">
                <span class="max-w-md text-center">Address: {{ $shopAddress }}</span>
                <span>Phone: {{ $shopPhone }}</span>
            </div>
        </div>

        <div class="px-2 py-2 flex flex-col">
            <div class="flex justify-between items-center">
                <div class="w-full flex items-center gap-1">
                    <span class="text-md text-gray-800">Bill No.B:</span>
                    <span class="text-gray-700 font-bold text-md">{{ $memo->memo_no }}</span>
                </div>
                <div class="w-full flex items-center justify-center">
                    <div class="bg-yellow-200 text-blue-900 px-4 py-0.5 rounded-3xl text-sm">
                        MEMO / BILL
                    </div>
                </div>
                <div class="w-full flex justify-end items-center">
                    <span class="text-md font-medium text-gray-700">Date:
                        {{ date('d m Y', strtotime($memo->updated_at)) }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-1 mt-2">
                <div class="flex h-[30px] items-center border border-gray-500 border-dotted bg-yellow-100 text-gray-700">
                    <div
                        class="w-20 h-full bg-orange-200 text-gray-800 flex justify-start px-2 items-center border-r border-gray-500 border-dotted">
                        Name:
                    </div>
                    <span class="ml-2">{{ $data['customer_name'] ?? '' }}</span>
                </div>
                <div class="flex h-[30px] items-center border border-gray-500 border-dotted bg-yellow-100 text-gray-700">
                    <div
                        class="w-20 h-full bg-orange-200 text-gray-800 flex justify-start px-2 items-center border-r border-gray-500 border-dotted">
                        Address:
                    </div>
                    <span class="ml-2">{{ $data['customer_address'] ?? '' }}</span>
                </div>
            </div>

            <div class="mt-1 overflow-x-auto">
                <table class="w-full border-collapse border border-gray-500 border-dotted">
                    <thead class="bg-orange-200">
                        <tr class="text-gray-800 text-sm">
                            <th class="border border-gray-500 border-dotted px-2 py-1 text-left">Group</th>
                            <th class="border border-gray-500 border-dotted px-2 py-1 text-center">Description</th>
                            <th class="border border-gray-500 border-dotted px-2 py-1 text-center">Brand</th>
                            <th class="border border-gray-500 border-dotted px-2 py-1 text-center">Rate</th>
                            <th class="border border-gray-500 border-dotted px-2 py-1 text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
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
                                    <td class="border border-gray-500 border-dotted px-2 py-0.5 w-6 text-center">
                                        @if ($chunkIndex == 0)
                                            <span class="text-lg">{{ $item['group_name'] ?? 'Unknown' }}-</span>
                                        @else
                                            &nbsp;
                                        @endif
                                    </td>

                                    <td class="border border-gray-500 border-dotted px-2 py-0.5">
                                        <span>
                                            @foreach ($chunk as $size)
                                                <span class="mr-2">{{ $size['size'] }}X{{ $size['quantity'] }}</span>
                                            @endforeach
                                        </span>
                                    </td>

                                    @if ($chunkIndex == 0)
                                        <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-14">
                                            {{ $item['brand_name'] ?? 'HC' }}
                                        </td>
                                        <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-10">
                                            {{ $rateToShow }}
                                        </td>
                                        <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-20">
                                            &#2547;{{ number_format($item['item_total'], 2) }}
                                        </td>
                                    @else
                                        <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-14">&nbsp;
                                        </td>
                                        <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-10">&nbsp;
                                        </td>
                                        <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-20">&nbsp;
                                        </td>
                                    @endif
                                </tr>
                                @php $rowsUsed++; @endphp
                            @endforeach
                        @endforeach

                        @for ($i = $rowsUsed; $i < $maxDataRows; $i++)
                            <tr>
                                <td class="border border-gray-500 border-dotted px-2 py-0.5 w-6 text-center"></td>
                                <td class="border border-gray-500 border-dotted px-2 py-0.5">&nbsp;</td>
                                <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-14">&nbsp;</td>
                                <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-10">&nbsp;</td>
                                <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center w-20">&nbsp;</td>
                            </tr>
                        @endfor

                        @php
                            $statusRaw = $data['debit_credit_status'] ?? '';
                            $s = strtolower(trim($statusRaw));
                            if (in_array($s, ['debit', 'dr', 'd'])) {
                                $short = 'Customer Dr.';
                                $colorClass = 'text-red-600';
                            } elseif (in_array($s, ['credit', 'cr', 'c'])) {
                                $short = 'Customer Cr.';
                                $colorClass = 'text-green-600';
                            } else {
                                $short = '';
                                $colorClass = 'text-gray-600';
                            }
                            $amount = $data['amount'] ?? null;
                        @endphp

                        <tr class="text-gray-800">
                            <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center" colspan="1"></td>
                            <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center" colspan="1"></td>
                            <td class="border border-gray-500 capitalize border-dotted px-1 py-0.5 text-left"
                                colspan="2">
                                <span class="font-medium {{ $colorClass }}">
                                    {{ $short }}
                                    @if (!is_null($amount))
                                        ৳{{ number_format((float) $amount, 2) }}
                                    @endif
                                </span>
                            </td>
                            <td class="border border-gray-500 border-dotted px-2 py-0.5 text-left" colspan="2">
                                &#2547;{{ number_format($data['debit_credit'], 2) }}
                            </td>
                        </tr>

                        @php
                            $grandTotal = 0;

                            foreach ($data['items'] as $item) {
                                $grandTotal += (float) ($item['item_total'] ?? 0);
                            }

                            if (!empty($data['debit_credit_status']) && isset($data['debit_credit'])) {
                                $debitCredit = (float) $data['debit_credit'];

                                if (strtolower($data['debit_credit_status']) === 'debit') {
                                    $grandTotal += $debitCredit;
                                } elseif (strtolower($data['debit_credit_status']) === 'credit') {
                                    $grandTotal -= $debitCredit;
                                }
                            }
                        @endphp

                        <tr class="text-gray-800">
                            <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center" colspan="1">BL</td>
                            <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center" colspan="1"></td>
                            <td class="border border-gray-500 bg-orange-200 border-dotted px-1 py-0.5 text-left"
                                colspan="2">Grand Total.</td>
                            <td class="border border-gray-500 border-dotted px-2 py-0.5 text-center font-bold"
                                colspan="2">
                                ৳{{ number_format(abs($grandTotal), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
