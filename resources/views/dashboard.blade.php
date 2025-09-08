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
            <a href="#" class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-green-600 bg-green-100 icon-box">
                    <i class="ri-user-3-fill"></i>
                </div>
                <div class="text-2xl font-bold text-green-600"><i class="ri-hashtag"></i>{{ $totalCustomer }}</div>
                <div class="text-sm text-gray-500 capitalize">Total Customer</div>
                <div class="w-full h-2 rounded-md bg-green-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-green-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Customer Debit -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-red-600 bg-red-100 icon-box">
                    <i class="ri-wallet-3-fill"></i>
                </div>
                <div class="text-2xl font-bold text-red-600">&#2547;{{ $customerDebit }}</div>
                <div class="text-sm text-gray-500 capitalize">Customer Debit</div>
                <div class="w-full h-2 rounded-md bg-red-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-red-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Total Vendor -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-blue-600 bg-blue-100 icon-box">
                    <i class="ri-store-2-fill"></i>
                </div>
                <div class="text-2xl font-bold text-blue-600"><i class="ri-hashtag"></i>{{ $totalVendor }}</div>
                <div class="text-sm text-gray-500 capitalize">Total Vendor</div>
                <div class="w-full h-2 rounded-md bg-blue-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-blue-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Vendor Credit -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-yellow-600 bg-yellow-100 icon-box">
                    <i class="ri-bank-card-fill"></i>
                </div>
                <div class="text-2xl font-bold text-yellow-600">&#2547;{{ $vendorCredit }}</div>
                <div class="text-sm text-gray-500 capitalize">Vendor Credit</div>
                <div class="w-full h-2 rounded-md bg-yellow-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-yellow-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>
            <!-- Daily Sales -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-purple-600 bg-purple-100 icon-box">
                    <i class="ri-shopping-cart-fill"></i>
                </div>
                <div class="text-2xl font-bold text-purple-600">
                    &#2547;{{ array_sum($data['daily']['sales'] ?? [0]) }}
                </div>
                <div class="text-sm text-gray-500 capitalize">Daily Sales</div>
                <div class="w-full h-2 rounded-md bg-purple-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-purple-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Monthly Sales -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-pink-600 bg-pink-100 icon-box">
                    <i class="ri-calendar-fill"></i>
                </div>
                <div class="text-2xl font-bold text-pink-600">
                    &#2547;{{ array_sum($data['monthly']['sales'] ?? [0]) }}
                </div>
                <div class="text-sm text-gray-500 capitalize">Monthly Sales</div>
                <div class="w-full h-2 rounded-md bg-pink-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-pink-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Yearly Sales -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-indigo-600 bg-indigo-100 icon-box">
                    <i class="ri-calendar-line"></i>
                </div>
                <div class="text-2xl font-bold text-indigo-600">
                    &#2547;{{ array_sum($data['yearly']['sales'] ?? [0]) }}
                </div>
                <div class="text-sm text-gray-500 capitalize">Yearly Sales</div>
                <div class="w-full h-2 rounded-md bg-indigo-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-indigo-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Daily Profit -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-emerald-600 bg-emerald-100 icon-box">
                    <i class="ri-shopping-cart-fill"></i>
                </div>
                <div class="text-2xl font-bold text-emerald-600">
                    &#2547;{{ array_sum($data['daily']['profit'] ?? [0]) }}
                </div>
                <div class="text-sm text-gray-500 capitalize">Daily Profit</div>
                <div class="w-full h-2 rounded-md bg-emerald-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-emerald-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Monthly Profit -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-yellow-600 bg-yellow-100 icon-box">
                    <i class="ri-calendar-fill"></i>
                </div>
                <div class="text-2xl font-bold text-yellow-600">
                    &#2547;{{ array_sum($data['monthly']['profit'] ?? [0]) }}
                </div>
                <div class="text-sm text-gray-500 capitalize">Monthly Profit</div>
                <div class="w-full h-2 rounded-md bg-yellow-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-yellow-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Yearly Profit -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-orange-600 bg-orange-100 icon-box">
                    <i class="ri-calendar-line"></i>
                </div>
                <div class="text-2xl font-bold text-orange-600">
                    &#2547;{{ array_sum($data['yearly']['profit'] ?? [0]) }}
                </div>
                <div class="text-sm text-gray-500 capitalize">Yearly Profit</div>
                <div class="w-full h-2 rounded-md bg-orange-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-orange-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Total Brand -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-pink-600 bg-pink-100 icon-box">
                    <i class="ri-gift-fill"></i>
                </div>
                <div class="text-2xl font-bold text-pink-600"><i class="ri-hashtag"></i>{{ $totalBrand }}</div>
                <div class="text-sm text-gray-500 capitalize">Total Brand</div>
                <div class="w-full h-2 rounded-md bg-pink-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-pink-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Total Group -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-rose-600 bg-rose-100 icon-box">
                    <i class="ri-stack-fill"></i>
                </div>
                <div class="text-2xl font-bold text-rose-600"><i class="ri-hashtag"></i>{{ $totalGroup }}</div>
                <div class="text-sm text-gray-500 capitalize">Total Group</div>
                <div class="w-full h-2 rounded-md bg-rose-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-rose-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Total Size -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-indigo-600 bg-indigo-100 icon-box">
                    <i class="ri-layout-fill"></i>
                </div>
                <div class="text-2xl font-bold text-indigo-600"><i class="ri-hashtag"></i>{{ $totalSize }}</div>
                <div class="text-sm text-gray-500 capitalize">Total Size</div>
                <div class="w-full h-2 rounded-md bg-indigo-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-indigo-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Total Stock -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-teal-600 bg-teal-100 icon-box">
                    <i class="ri-stack-line"></i>
                </div>
                <div class="text-2xl font-bold text-teal-600"><i class="ri-hashtag"></i>{{ $totalStock }}</div>
                <div class="text-sm text-gray-500 capitalize">Total Stock</div>
                <div class="w-full h-2 rounded-md bg-teal-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-teal-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

            <!-- Exhausted Stock -->
            <a href="#"
                class="relative bg-white p-5 rounded-md shadow hover:-translate-y-1 transition-transform block">
                <div class="absolute top-4 right-4 rounded-full text-red-600 bg-red-100 icon-box">
                    <i class="ri-alert-line"></i>
                </div>
                <div class="text-2xl font-bold text-red-600"><i class="ri-hashtag"></i>{{ $exhaustedStock }}</div>
                <div class="text-sm text-gray-500 capitalize">Exhausted Stock</div>
                <div class="w-full h-2 rounded-md bg-red-600 mt-3 bg-opacity-30">
                    <div class="h-2 rounded-md bg-red-600 progress-bar-inner" style="--progress-width: 100%;"></div>
                </div>
            </a>

        </div>
    </div>
@endsection
