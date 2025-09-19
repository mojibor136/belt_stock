@extends('layouts.app')
@section('title', 'General Setting')
@section('content')
    <div class="w-full flex flex-col gap-6 mb-20">

        <!-- Header -->
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 gap-2">
            <h2 class="text-2xl font-bold text-gray-800">General Settings</h2>
            <p class="text-gray-600 text-sm">Update your site information and basic settings</p>
        </div>

        <!-- Form Card -->
        <div class="w-full bg-white rounded shadow px-6 py-6">
            <form action="{{ route('general.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Fav Icon -->
                <div class="mt-4">
                    <label for="fav_icon" class="block text-md font-medium text-gray-700 mb-1.5">
                        Fav Icon
                    </label>
                    <input type="file" id="fav_icon" name="fav_icon"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                    @error('fav_icon')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Site Logo -->
                <div class="mt-4">
                    <label for="site_logo" class="block text-md font-medium text-gray-700 mb-1.5">
                        Site Logo
                    </label>
                    <input type="file" id="site_logo" name="site_logo"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                    @error('site_logo')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $shopName = old('shop_name', isset($setting->shop_name) ? implode(', ', $setting->shop_name) : '');

                    $shopAddress = old(
                        'shop_address',
                        isset($setting->shop_address) ? implode(', ', $setting->shop_address) : '',
                    );

                    $shopPhone = old(
                        'shop_phone',
                        isset($setting->shop_phone) ? implode(', ', $setting->shop_phone) : '',
                    );
                @endphp

                <!-- Shop Name -->
                <div class="mt-4">
                    <label for="shop_name" class="block text-md font-medium text-gray-700 mb-1.5">
                        Shop Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="shop_name" name="shop_name"
                        placeholder='Enter shop name as JSON e.g. ["Shop1","Shop2"]'
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700"
                        value="{{ $shopName }}">
                    @error('shop_name')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Shop Address -->
                <div class="mt-4">
                    <label for="shop_address" class="block text-md font-medium text-gray-700 mb-1.5">
                        Shop Address <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="shop_address" name="shop_address"
                        placeholder='Enter addresses as JSON e.g. ["Address1","Address2"]'
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700"
                        value="{{ $shopAddress }}">
                    @error('shop_address')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Shop Phone -->
                <div class="mt-4">
                    <label for="shop_phone" class="block text-md font-medium text-gray-700 mb-1.5">
                        Shop Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="shop_phone" name="shop_phone"
                        placeholder='Enter phone numbers as JSON e.g. ["01812345678","01887654321"]'
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700"
                        value="{{ $shopPhone }}">
                    @error('shop_phone')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
