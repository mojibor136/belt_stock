@extends('layouts.app')
@section('title', 'Customer Analysis')
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

    <div class="flex flex-col">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-3">
            <a href="#"
                class="bg-gradient-to-r from-blue-500 to-indigo-700 backdrop-blur-sm px-6 py-6 rounded-md shadow-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-300 block">
                <div class="flex justify-between items-center">
                    <div class="flex items-center justify-center gap-4">
                        <div
                            class="w-11 h-11 rounded-lg flex justify-center items-center bg-white/10 backdrop-blur-xl border border-white/30 shadow-inner">
                            <i class="ri-bank-card-fill text-2xl text-white"></i>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-lg font-semibold text-white">Total Invoice</span>
                            <span class="text-sm text-gray-100">&#2547; 66,666</span>
                        </div>
                    </div>
                </div>
            </a>

            <a href="#"
                class="bg-gradient-to-r from-green-500 to-green-700 backdrop-blur-sm px-6 py-6 rounded-md shadow-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-300 block">
                <div class="flex justify-between items-center">
                    <div class="flex items-center justify-center gap-4">
                        <div
                            class="w-11 h-11 rounded-lg flex justify-center items-center bg-white/10 backdrop-blur-xl border border-white/30 shadow-inner">
                            <i class="ri-wallet-3-fill text-2xl text-white"></i>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-lg font-semibold text-white">Total Payment</span>
                            <span class="text-sm text-gray-100">&#2547; 7,666</span>
                        </div>
                    </div>
                </div>
            </a>

            <a href="#"
                class="bg-gradient-to-r from-yellow-400 to-orange-500 backdrop-blur-sm px-6 py-6 rounded-md shadow-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-300 block">
                <div class="flex justify-between items-center">
                    <div class="flex items-center justify-center gap-4">
                        <div
                            class="w-11 h-11 rounded-lg flex justify-center items-center bg-white/10 backdrop-blur-xl border border-white/30 shadow-inner">
                            <i class="ri-shopping-bag-3-fill text-2xl text-white"></i>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-lg font-semibold text-white">Total Purchase</span>
                            <span class="text-sm text-gray-100">&#2547; 6,666</span>
                        </div>
                    </div>
                </div>
            </a>

            <a href="#"
                class="bg-gradient-to-r from-teal-500 to-cyan-600 backdrop-blur-sm px-6 py-6 rounded-md shadow-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-300 block">
                <div class="flex justify-between items-center">
                    <div class="flex items-center justify-center gap-4">
                        <div
                            class="w-11 h-11 rounded-lg flex justify-center items-center bg-white/10 backdrop-blur-xl border border-white/30 shadow-inner">
                            <i class="ri-bar-chart-2-fill text-2xl text-white"></i>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-lg font-semibold text-white">Total Profit</span>
                            <span class="text-sm text-gray-100">&#2547; 6,000</span>
                        </div>
                    </div>
                </div>
            </a>

            <a href="#"
                class="bg-gradient-to-r from-red-500 to-pink-600 backdrop-blur-sm px-6 py-6 rounded-md shadow-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-300 block">
                <div class="flex justify-between items-center">
                    <div class="flex items-center justify-center gap-4">
                        <div
                            class="w-11 h-11 rounded-lg flex justify-center items-center bg-white/10 backdrop-blur-xl border border-white/30 shadow-inner">
                            <i class="ri-money-dollar-circle-fill text-2xl text-white"></i>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-lg font-semibold text-white">Total Memo</span>
                            <span class="text-sm text-gray-100">&#2547; 666,666</span>
                        </div>
                    </div>
                </div>
            </a>

            <a href="#"
                class="bg-gradient-to-r from-purple-500 to-fuchsia-600 backdrop-blur-sm px-6 py-6 rounded-md shadow-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-300 block">
                <div class="flex justify-between items-center">
                    <div class="flex items-center justify-center gap-4">
                        <div
                            class="w-11 h-11 rounded-lg flex justify-center items-center bg-white/10 backdrop-blur-xl border border-white/30 shadow-inner">
                            <i class="ri-exchange-dollar-fill text-2xl text-white"></i>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-lg font-semibold text-white">Total Transaction</span>
                            <span class="text-sm text-gray-100">666,660 Transactions</span>
                        </div>
                    </div>
                </div>
            </a>

            <a href="#"
                class="bg-gradient-to-r from-indigo-500 to-sky-600 px-6 py-6 rounded-md shadow-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-300 block">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-lg flex justify-center items-center bg-white/10 border border-white/30">
                        <i class="ri-bank-fill text-2xl text-white"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold text-white">Current Balance</span>
                        <span class="text-sm text-gray-100">&#2547; 80,000</span>
                    </div>
                </div>
            </a>

            <a href="#"
                class="bg-gradient-to-r from-gray-500 to-gray-700 px-6 py-6 rounded-md shadow-lg hover:-translate-y-1 hover:scale-105 transition-transform duration-300 block">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-lg flex justify-center items-center bg-white/10 border border-white/30">
                        <i class="ri-information-fill text-2xl text-white"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-semibold text-white">Balance Status</span>
                        <span class="text-sm text-gray-100">Debit</span>
                    </div>
                </div>
            </a>
        </div>
        <form method="GET"
            action="{{ route('customer.analysis', ['name' => Str::slug($customer->name), 'id' => $customer->id]) }}"
            class="flex md:flex-row flex-col items-center gap-2 mt-2">
            <input type="date" id="created_at" name="date" value="{{ request('date') }}" placeholder="dd/mm/yyyy"
                class="w-full px-4 h-12 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-md transition-all duration-150" />
            <i class="ri-arrow-left-right-line text-lg text-gray-700 hidden md:block"></i>
            <input type="date" id="created_at" name="date" value="{{ request('date') }}" placeholder="dd/mm/yyyy"
                class="w-full px-4 h-12 text-gray-700 rounded-md border border-gray-300 focus:ring-1 focus:ring-blue-600 focus:outline-none text-md transition-all duration-150" />
            <button type="submit"
                class="flex items-center justify-center gap-2 text-white bg-blue-600 rounded-md px-6 h-12 w-full md:w-fit hover:bg-blue-700 transition duration-200">
                <i class="ri-bar-chart-line"></i>
                Analysis
            </button>
        </form>

        <div class="mt-6 mb-6">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gradient-to-r from-blue-600 to-indigo-700">
                    <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                        <i class="ri-user-3-fill text-2xl"></i>
                        Customer Details
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                <i class="ri-user-fill text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Customer Name</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $customer->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-green-100 text-green-600">
                                <i class="ri-phone-fill text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Phone</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $customer->phone ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 flex items-center justify-center rounded-lg bg-yellow-100 text-yellow-600">
                                <i class="ri-mail-fill text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Email</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $customer->email ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 flex items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                                <i class="ri-map-pin-fill text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Address</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $customer->address ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-red-100 text-red-600">
                                <i class="ri-bank-card-fill text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Current Balance</p>
                                <p class="text-lg font-semibold text-gray-800">&#2547;
                                    {{ number_format($customer->amount ?? 0, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <i class="ri-information-fill text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Status</p>
                                <p
                                    class="text-sm font-semibold capitalize {{ $customer->status == 'Active' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $customer->status ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
