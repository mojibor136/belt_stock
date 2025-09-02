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
    <div class="memo-container w-full bg-green-100/50 shadow-lg print:w-full print:h-full mb-4 print:mb-0">
        <div class="bg-gray-800 h-10 flex items-center border border-white px-4">
            <div class="flex items-center text-white gap-2 flex-1 justify-between">
                <span class="text-2xl font-semibold tracking-widest"><i>DAIMAMD</i></span>
                <span class="text-md mt-0.5 tracking-widest"><i>SMART V-BELT</i></span>
            </div>
            <div class="h-full w-1 bg-gray-200 mx-2"></div>
            <div class="flex items-center text-white gap-2 flex-1 justify-between">
                <span class="text-2xl font-semibold tracking-widest pl-2"><i>DIGITAL</i></span>
                <span class="text-md mt-0.5 tracking-widest"><i>STRONG V-BELT</i></span>
            </div>
        </div>

        <div class="flex justify-between items-center border border-white">
            <div class="bg-green-600 h-10 flex items-center text-yellow-300 flex-1 px-4 justify-between">
                <span class="text-2xl font-semibold tracking-widest"><i>MOON DELUX</i></span>
                <span></span>
            </div>
            <div class="bg-gray-800 h-10 flex items-center text-white flex-1 px-4 justify-between">
                <span class="text-2xl font-semibold tracking-widest"><i>HANGCHANG</i></span>
                <span></span>
            </div>
        </div>

        <div class="py-2 px-8">
            <div class="flex flex-col">
                <div class="flex items-center justify-center gap-2">
                    <div
                        class="w-16 h-12 border border-red-700 flex justify-center items-center bg-white font-bold text-4xl rounded">
                        <span class="text-blue-800">B</span> <span class="text-orange-600">S</span>
                    </div>
                    <div class="flex flex-col items-start justify-center">
                        <span class="text-blue-900 text-xl font-semibold tracking-widest">বেঙ্গল বেয়ারিং ও বেল্ট
                            স্টোর</span>
                        <span class="text-red-600 text-xl font-semibold tracking-widest">BENGAL BEARING & BELT
                            STORE</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-2 py-2 flex flex-col">
            <div class="flex justify-between items-center">
                <div class="w-full flex items-center gap-1">
                    <span class="text-md font-semibold text-gray-800">Bill No.B:</span>
                    <span class="text-gray-800 font-bold text-md">520</span>
                </div>
                <div class="w-full flex items-center justify-center">
                    <div class="bg-yellow-200 text-blue-900 px-4 py-1 font-semibold rounded-3xl text-sm">
                        MEMO / BILL
                    </div>
                </div>
                <div class="w-full flex justify-end items-center">
                    <span class="text-md font-medium text-gray-700">Date: 20-08-2025</span>
                </div>
            </div>

            <div class="flex flex-col gap-1 mt-2">
                <div class="flex h-8 items-center border border-gray-500 border-dotted bg-yellow-100 text-gray-700">
                    <div
                        class="w-20 h-full bg-orange-200 text-gray-800 flex justify-start px-2 items-center border-r border-gray-500 border-dotted">
                        Name:
                    </div>
                    <span class="ml-2">Md Mojibor Rahman</span>
                </div>
                <div class="flex h-8 items-center border border-gray-500 border-dotted bg-yellow-100 text-gray-700">
                    <div
                        class="w-20 h-full bg-orange-200 text-gray-800 flex justify-start px-2 items-center border-r border-gray-500 border-dotted">
                        Address:
                    </div>
                    <span class="ml-2">Dhaka,Nawabpur</span>
                </div>
            </div>

            <div class="mt-1 overflow-x-auto">
                <table class="w-full border-collapse border border-gray-500 border-dotted">
                    <thead class="bg-orange-200">
                        <tr class="text-gray-800 text-sm">
                            <th class="border border-gray-500 border-dotted px-2 py-2 text-left">Group</th>
                            <th class="border border-gray-500 border-dotted px-2 py-2 text-center">Description</th>
                            <th class="border border-gray-500 border-dotted px-2 py-2 text-center">Brand</th>
                            <th class="border border-gray-500 border-dotted px-2 py-2 text-center">Rate</th>
                            <th class="border border-gray-500 border-dotted px-2 py-2 text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800">
                        @php
                            $totalRows = 13;
                            $items = $data['items'];
                        @endphp

                        @for ($i = 0; $i < $totalRows; $i++)
                            @if (isset($items[$i]))
                                @php
                                    $item = $items[$i];
                                    $usePieceRate = $item['rate'] == 0 && isset($item['piece_rate']);
                                    $rateToShow = $usePieceRate ? $item['piece_rate'] . ' PS' : $item['rate'];

                                    $sizeChunks = array_chunk($item['sizes'], 6);
                                @endphp

                                @foreach ($sizeChunks as $chunkIndex => $chunk)
                                    <tr>
                                        @if ($chunkIndex == 0)
                                            <td class="border border-gray-500 border-dotted px-2 py-1 w-6 text-center">
                                                <strong class="text-lg">B-</strong>
                                            </td>
                                        @else
                                            <td class="border border-gray-500 border-dotted px-2 py-1 w-6 text-center">
                                                &nbsp;</td>
                                        @endif

                                        <td class="border border-gray-500 border-dotted px-2 py-1">
                                            <span class="font-semibold">
                                                @foreach ($chunk as $size)
                                                    <span class="mr-2">{{ $size['size'] }}X{{ $size['quantity'] }}</span>
                                                @endforeach
                                            </span>
                                        </td>

                                        @if ($chunkIndex == 0)
                                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-14">
                                                {{ $item['brand'] ?? 'HC' }}
                                            </td>
                                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-10">
                                                {{ $rateToShow }}
                                            </td>
                                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-20">
                                                &#2547;{{ number_format($item['item_total'], 2) }}
                                            </td>
                                        @else
                                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-14">
                                                &nbsp;</td>
                                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-10">
                                                &nbsp;</td>
                                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-20">
                                                &nbsp;</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="border border-gray-500 border-dotted px-2 py-1 w-6 text-center"></td>
                                    <td class="border border-gray-500 border-dotted px-2 py-1">&nbsp;</td>
                                    <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-14">&nbsp;</td>
                                    <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-10">&nbsp;</td>
                                    <td class="border border-gray-500 border-dotted px-2 py-1 text-center w-20">&nbsp;</td>
                                </tr>
                            @endif
                        @endfor
                        
                        <tr class="text-gray-800">
                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center" colspan="1">
                            </td>
                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center" colspan="1">
                            </td>
                            <td class="border border-gray-500 border-dotted px-2 py-1 text-left"
                                colspan="2">Previous  +
                            </td>
                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center" colspan="2">
                                &#2547;{{ number_format($data['grand_total'], 2) }}
                            </td>
                        </tr>

                        <tr class="text-gray-800">
                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center" colspan="1">BL
                            </td>
                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center" colspan="1">
                            </td>
                            <td class="border border-gray-500 bg-orange-200 border-dotted px-2 py-1 text-center"
                                colspan="2">Grand Total 
                            </td>
                            <td class="border border-gray-500 border-dotted px-2 py-1 text-center font-bold" colspan="2">
                                &#2547;{{ number_format($data['grand_total'], 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
