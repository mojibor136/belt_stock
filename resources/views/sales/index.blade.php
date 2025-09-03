@extends('layouts.app')
@section('title', 'Sales Reports')
@section('content')
    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b rounded-md mb-4">
            <div class="flex flex-col gap-1 w-full md:w-2/3">
                <h1 class="text-2xl font-bold text-gray-800">Sales Reports</h1>
                <p class="text-sm text-gray-500">Manage your sales and their transactions efficiently</p>
            </div>

            <div class="flex flex-row gap-2 mt-3 md:mt-0 w-full md:w-auto items-start sm:items-center">
                <a href=""
                    class="flex items-center gap-2 h-10 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-add-line text-lg"></i> Add Sales
                </a>

                <button
                    class="flex items-center gap-2 h-10 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-download-line"></i> Export
                </button>
            </div>
        </div>

        <!-- Filter -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2 sm:gap-4">
            <div class="flex flex-col sm:flex-row w-full sm:w-2/3 gap-2">
                <div class="relative w-full sm:w-1/2">
                    <input type="text" wire:model.defer="search" placeholder="Search sales..."
                        class="w-full pl-10 pr-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                    <i
                        class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-base"></i>
                </div>

                <div class="relative w-full sm:w-1/2">
                    <select wire:model.defer="status"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>

                <button wire:click="applyFilter"
                    class="flex justify-center items-center px-4 py-2 h-10 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                    <i class="ri-search-line mr-1"></i> Search
                </button>
            </div>

            <a href="{{ route('customer.index') }}"
                class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                Back
            </a>
        </div>

        <!-- Sales Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left">Brand</th>
                        <th class="px-4 py-3 text-center">Group</th>
                        <th class="px-4 py-3 text-center">Size</th>
                        <th class="px-4 py-3 text-center">Quantity</th>
                        <th class="px-4 py-3 text-center">Date</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @php
                        $sales = [
                            [
                                'customer' => 'Rahim Uddin',
                                'brand' => 'Samsung',
                                'group' => 'Electronics',
                                'size' => 'Large',
                                'qty' => 10,
                                'date' => '2025-08-01',
                            ],
                            [
                                'customer' => 'Karim Mia',
                                'brand' => 'Walton',
                                'group' => 'Home Appliance',
                                'size' => 'Medium',
                                'qty' => 5,
                                'date' => '2025-08-05',
                            ],
                            [
                                'customer' => 'Selina Begum',
                                'brand' => 'Bata',
                                'group' => 'Footwear',
                                'size' => '42',
                                'qty' => 3,
                                'date' => '2025-08-08',
                            ],
                            [
                                'customer' => 'Aziz Khan',
                                'brand' => 'Pran',
                                'group' => 'Food & Beverage',
                                'size' => '500ml',
                                'qty' => 20,
                                'date' => '2025-08-10',
                            ],
                        ];
                    @endphp

                    @foreach ($sales as $index => $sale)
                        <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-bold">{{ $sale['customer'] }}</td>
                            <td class="px-4 py-3">{{ $sale['brand'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $sale['group'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $sale['size'] }}</td>
                            <td class="px-4 py-3 text-center font-semibold">{{ $sale['qty'] }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $sale['date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
