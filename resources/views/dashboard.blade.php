@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
    <style>
        .category-tag {
            transition: all 0.3s ease;
        }

        @keyframes growWidth {
            from {
                width: 0;
            }

            to {
                width: var(--progress-width);
            }
        }

        .progress-bar-inner {
            width: 0;
            animation: growWidth 1.5s ease-out forwards;
        }

        .icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
    </style>

    <div class="flex flex-col">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-3">

            <!-- Total Customer -->
            <a href="#"
                class="relative bg-gradient-to-r from-green-500 to-green-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-user-3-fill"></i>
                </div>
                <div class="text-2xl font-bold"><i class="ri-hashtag"></i>{{ number_format($totalCustomer, 2) }}</div>
                <div class="text-base capitalize">Total Customer</div>
            </a>

            <!-- Customer Debit -->
            <a href="#"
                class="relative bg-gradient-to-r from-red-500 to-red-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-wallet-3-fill"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format($customerDebit, 2) }}</div>
                <div class="text-base capitalize">Customer Debit</div>
            </a>

            <!-- Customer Credit -->
            <a href="#"
                class="relative bg-gradient-to-r from-orange-500 to-orange-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-wallet-3-fill"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format($customerCredit, 2) }}</div>
                <div class="text-base capitalize">Customer Credit</div>
            </a>

            <!-- Total Vendor -->
            <a href="#"
                class="relative bg-gradient-to-r from-blue-500 to-blue-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-store-2-fill"></i>
                </div>
                <div class="text-2xl font-bold"><i class="ri-hashtag"></i>{{ number_format($totalVendor, 2) }}</div>
                <div class="text-base capitalize">Total Vendor</div>
            </a>

            <!-- Vendor Credit -->
            <a href="#"
                class="relative bg-gradient-to-r from-yellow-500 to-yellow-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-bank-card-fill"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format($vendorCredit, 2) }}</div>
                <div class="text-base capitalize">Vendor Credit</div>
            </a>

            <!-- Vendor Debit -->
            <a href="#"
                class="relative bg-gradient-to-r from-teal-500 to-teal-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-bank-card-fill"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format($vendorDebit, 2) }}</div>
                <div class="text-base capitalize">Vendor Debit</div>
            </a>

            <!-- Daily Sales -->
            <a href="#"
                class="relative bg-gradient-to-r from-purple-500 to-purple-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-shopping-cart-fill"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format(array_sum($data['daily']['sales'] ?? [0]), 2) }}
                </div>
                <div class="text-base capitalize">Daily Sales</div>
            </a>

            <!-- Monthly Sales -->
            <a href="#"
                class="relative bg-gradient-to-r from-pink-500 to-pink-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-calendar-fill"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format(array_sum($data['monthly']['sales'] ?? [0]), 2) }}
                </div>
                <div class="text-base capitalize">Monthly Sales</div>
            </a>

            <!-- Yearly Sales -->
            <a href="#"
                class="relative bg-gradient-to-r from-indigo-500 to-indigo-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-calendar-line"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format(array_sum($data['yearly']['sales'] ?? [0]), 2) }}
                </div>
                <div class="text-base capitalize">Yearly Sales</div>
            </a>

            <!-- Daily Profit -->
            <a href="#"
                class="relative bg-gradient-to-r from-emerald-500 to-emerald-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-shopping-cart-fill"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format(array_sum($data['daily']['profit'] ?? [0]), 2) }}
                </div>
                <div class="text-base capitalize">Daily Profit</div>
            </a>

            <!-- Monthly Profit -->
            <a href="#"
                class="relative bg-gradient-to-r from-cyan-500 to-cyan-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-calendar-fill"></i>
                </div>
                <div class="text-2xl font-bold">
                    &#2547;{{ number_format(array_sum($data['monthly']['profit'] ?? [0]), 2) }}
                </div>
                <div class="text-base capitalize">Monthly Profit</div>
            </a>

            <!-- Yearly Profit -->
            <a href="#"
                class="relative bg-gradient-to-r from-orange-500 to-orange-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-calendar-line"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format(array_sum($data['yearly']['profit'] ?? [0]), 2) }}
                </div>
                <div class="text-base capitalize">Yearly Profit</div>
            </a>

            <!-- Total Brand -->
            <a href="#"
                class="relative bg-gradient-to-r from-fuchsia-500 to-fuchsia-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-gift-fill"></i>
                </div>
                <div class="text-2xl font-bold"><i class="ri-hashtag"></i>{{ number_format($totalBrand, 2) }}</div>
                <div class="text-base capitalize">Total Brand</div>
            </a>

            <!-- Total Group -->
            <a href="#"
                class="relative bg-gradient-to-r from-rose-500 to-rose-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-stack-fill"></i>
                </div>
                <div class="text-2xl font-bold"><i class="ri-hashtag"></i>{{ number_format($totalGroup, 2) }}</div>
                <div class="text-base capitalize">Total Group</div>
            </a>

            <!-- Total Size -->
            <a href="#"
                class="relative bg-gradient-to-r from-sky-500 to-sky-700 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-layout-fill"></i>
                </div>
                <div class="text-2xl font-bold"><i class="ri-hashtag"></i>{{ number_format($totalSize, 2) }}</div>
                <div class="text-base capitalize">Total Size</div>
            </a>

            <!-- Total Stock -->
            <a href="#"
                class="relative bg-gradient-to-r from-indigo-400 to-indigo-600 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-stack-line"></i>
                </div>
                <div class="text-2xl font-bold"><i class="ri-hashtag"></i>{{ number_format($totalStock, 2) }}</div>
                <div class="text-base capitalize">Total Stock</div>
            </a>

            <!-- Exhausted Stock -->
            <a href="#"
                class="relative bg-gradient-to-r from-red-600 to-red-800 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-alert-line"></i>
                </div>
                <div class="text-2xl font-bold"><i class="ri-hashtag"></i>{{ number_format($exhaustedStock, 2) }}</div>
                <div class="text-base capitalize">Exhausted Stock</div>
            </a>

            <!-- Stock Value -->
            <a href="#"
                class="relative bg-gradient-to-r from-emerald-500 to-teal-600 p-5 rounded-md shadow hover:-translate-y-1 hover:shadow-lg transition-transform block text-white">
                <div class="absolute top-4 right-4 rounded-full bg-white bg-opacity-20 icon-box">
                    <i class="ri-money-dollar-circle-fill"></i>
                </div>
                <div class="text-2xl font-bold">&#2547;{{ number_format($stockValue, 2) }}</div>
                <div class="text-base capitalize">Stock Value</div>
            </a>

        </div>
    </div>
@endsection
