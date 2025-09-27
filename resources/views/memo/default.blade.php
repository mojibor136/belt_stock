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
    <div class="memo-container mb-6 pb-2 w-full bg-white shadow-lg print:w-full print:h-full print:mb-0">
        <div class="flex flex-col items-center px-6 py-2 border-b border-gray-300 bg-gray-50 text-center">
            <div class="flex items-center gap-2">
                @if (isset($firstThree[0]))
                    <span class="text-2xl font-bold text-gray-800">{{ $firstThree[0] }}</span>
                @endif
                @if (isset($firstThree[1]))
                    <span class="text-2xl font-bold text-gray-800">{{ $firstThree[1] }}</span>
                @endif
            </div>

            <!-- Address & Phone -->
            <p class="text-gray-600 mb-1">{{ $shopAddress }}</p>
            <p class="text-gray-600 mb-1">Phone: {{ $shopPhone }}</p>

            <!-- Company Description -->
            <p class="text-gray-500 text-sm max-w-2xl">
                {{ $setting->description }}
            </p>
        </div>

        <!-- Supplier & Customer -->
        <div class="bg-gray-50 px-4 py-2 text-md">
            <div class="flex justify-between">
                <div class="text-left text-neutral-600 flex flex-col gap-1.5">
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
                            <th class="px-2 py-2 text-left border border-gray-300">Group</th>
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
                                $rateToShow = $usePieceRate ? $item['piece_rate'] . ' ' : $item['rate'];
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
                                        <td class="border border-gray-300 px-2 py-1 text-center w-16">
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
                            <td class="border border-gray-300 px-2 py-0.5 text-center" colspan="1"></td>
                            <td class="border border-gray-300 px-2 py-0.5 text-center" colspan="1"></td>
                            <td class="border border-gray-500 capitalize border-dotted px-1 py-0.5 text-left"
                                colspan="2">
                                <span class="font-medium {{ $colorClass }}">
                                    {{ $short }}
                                    @if (!is_null($amount))
                                        ৳{{ number_format((float) $amount, 2) }}
                                    @endif
                                </span>
                            </td>
                            <td class="border border-gray-300 px-2 py-0.5 text-left" colspan="2">
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
                            <td class="border border-gray-300 px-2 py-0.5 text-center" colspan="1">BL</td>
                            <td class="border border-gray-300 px-2 py-0.5 text-center" colspan="1"></td>
                            <td class="border border-gray-300 bg-orange-200 px-1 py-0.5 text-left" colspan="2">Grand
                                Total.</td>
                            <td class="border border-gray-300 px-2 py-0.5 text-center font-bold" colspan="2">
                                ৳{{ number_format(abs($grandTotal), 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('autoPrint'))
                window.print();
            @endif
        });
    </script>
@endsection
