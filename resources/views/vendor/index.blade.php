@extends('layouts.app')
@section('title', 'Vendor Management')
@section('content')
    @include('components.toast')
    <div class="w-full mb-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-4 border-b rounded-md mb-4">
            <div class="flex flex-col gap-1 w-full md:w-2/3">
                <h1 class="text-2xl font-bold text-gray-800">Vendor Management</h1>
                <p class="text-sm text-gray-500">Manage your and vendors their transactions efficiently</p>
            </div>

            <div class="flex flex-row gap-2 mt-3 md:mt-0 w-full md:w-auto items-start sm:items-center">
                <a href="{{ route('vendor.create') }}"
                    class="flex items-center gap-2 h-10 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-add-line text-lg"></i> Add Vendor
                </a>

                <button
                    class="flex items-center gap-2 h-10 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-md shadow font-medium transition-all duration-200">
                    <i class="ri-download-line"></i> Export
                </button>
            </div>
        </div>

        <form method="GET" action="{{ route('vendor.index') }}"
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2 sm:gap-4">
            <div class="flex flex-col sm:flex-row w-full sm:w-2/3 gap-2">
                <div class="relative w-full sm:w-1/2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vendors..."
                        class="w-full pl-10 pr-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150" />
                    <i
                        class="ri-search-line absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-base"></i>
                </div>
                <div class="relative w-full sm:w-1/2">
                    <select name="status"
                        class="w-full px-4 h-10 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-sm transition-all duration-150">
                        <option value="">All Status</option>
                        <option value="debit" {{ strtolower(request('status')) == 'debit' ? 'selected' : '' }}>Debit
                        </option>
                        <option value="credit" {{ strtolower(request('status')) == 'credit' ? 'selected' : '' }}>Credit
                        </option>
                    </select>
                </div>
                <button type="submit"
                    class="flex justify-center items-center px-4 py-2 h-10 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                    <i class="ri-search-line mr-1"></i> Search
                </button>
            </div>
            <a href="{{ route('vendor.index') }}"
                class="flex justify-center items-center px-4 py-2 h-10 md:w-auto w-full rounded-md bg-red-600 hover:bg-red-700 text-white font-medium transition-all duration-150 mt-2 sm:mt-0">
                Reset
            </a>
        </form>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full table-auto">
                <thead class="bg-blue-600 text-white text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Phone</th>
                        <th class="px-4 py-3 text-center">Amount</th>
                        <th class="px-4 py-3 text-center">Credit / Debit</th>
                        <th class="px-4 py-3 text-right pr-8">Status/Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                    @php
                        $colors = [
                            'from-green-400 via-blue-500 to-purple-600',
                            'from-red-400 via-pink-500 to-yellow-500',
                            'from-indigo-400 via-purple-500 to-pink-500',
                            'from-teal-400 via-green-500 to-blue-500',
                            'from-orange-400 via-red-500 to-yellow-500',
                        ];
                    @endphp

                    @foreach ($vendors as $index => $vendor)
                        <tr class="hover:bg-gray-100 transition-colors cursor-pointer">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                @php $randColor = $colors[array_rand($colors)]; @endphp
                                <span class="text-transparent text-sm bg-clip-text bg-gradient-to-r {{ $randColor }}">
                                    {{ $vendor->name }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 text-sm rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                                    {{ $vendor->phone }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    class="text-md {{ strtolower($vendor->status) === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    ৳ {{ number_format($vendor->amount) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if (strtolower($vendor->status) === 'credit')
                                    <span
                                        class="px-8 py-1 text-xs rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white shadow">
                                        Credit
                                    </span>
                                @else
                                    <span
                                        class="px-8 py-1 text-xs rounded-full bg-gradient-to-r from-red-400 to-red-600 text-white shadow">
                                        Debit
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end items-center gap-1">
                                    <div onclick="window.location.href='{{ route('vendor.purchase', ['name' => Str::slug($vendor->name), 'id' => $vendor->id]) }}'"
                                        class="bg-blue-600 text-white rounded px-4 flex justify-center items-center gap-0.5 h-8 cursor-pointer">
                                        <i class="ri-shopping-cart-2-line"></i>
                                        <span>Purchase</span>
                                    </div>
                                    <div onclick="window.location.href='{{ route('vendor.analysis', ['name' => Str::slug($vendor->name), 'id' => $vendor->id]) }}'"
                                        class="bg-indigo-600 text-white rounded px-4 flex justify-center items-center gap-0.5 h-8 cursor-pointer">
                                        <i class="ri-computer-line"></i>
                                        <span>Analysis</span>
                                    </div>
                                    <a href="{{ route('vendor.all.transaction', ['name' => Str::slug($vendor->name), 'id' => $vendor->id]) }}"
                                        class="inline-flex items-center justify-center w-10 h-8 bg-blue-500 hover:bg-blue-600 text-white rounded shadow"
                                        title="View">
                                        <i class="ri-eye-line text-lg"></i>
                                    </a>

                                    <a href="{{ route('vendor.edit', $vendor->id) }}"
                                        class="inline-flex items-center justify-center w-10 h-8 bg-green-500 hover:bg-green-600 text-white rounded shadow"
                                        title="Edit">
                                        <i class="ri-edit-2-line text-md"></i>
                                    </a>

                                    <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST"
                                        onsubmit="return confirm('আপনি কি নিশ্চিত যে এই গ্রাহকে মুছে ফেলতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-10 h-8 bg-red-500 hover:bg-red-600 text-white rounded shadow cursor-pointer"
                                            title="Delete">
                                            <i class="ri-delete-bin-6-line text-md"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($vendors->hasPages())
            <div class="mt-4 flex justify-end">
                @if ($vendors->onFirstPage())
                    <span class="px-4 py-2 mr-2 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                        Previous
                    </span>
                @else
                    <a href="{{ $vendors->previousPageUrl() }}"
                        class="px-4 py-2 mr-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Previous
                    </a>
                @endif

                @if ($vendors->hasMorePages())
                    <a href="{{ $vendors->nextPageUrl() }}"
                        class="px-4 py-2 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Next
                    </a>
                @else
                    <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-500 cursor-not-allowed">
                        Next
                    </span>
                @endif
            </div>
        @endif
    </div>
@endsection
