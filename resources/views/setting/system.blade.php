@extends('layouts.app')
@section('title', 'System Setting')
@section('content')
    <div class="w-full flex flex-col gap-6 mb-20">

        <!-- Header -->
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 gap-2">
            <h2 class="text-2xl font-bold text-gray-800">System Settings</h2>
            <p class="text-gray-600 text-sm">Manage system permissions and access controls</p>
        </div>

        <!-- Form Card -->
        <div class="w-full bg-white rounded shadow px-6 py-6">
            <form action="{{ route('system.store') }}" method="POST">
                @csrf

                <!-- Invoice Permission -->
                <div class="mt-4 flex items-center gap-3">
                    <input type="checkbox" id="invoice" name="invoice" value="1"
                        {{ $setting && $setting->invoice ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="invoice" class="text-md font-medium text-gray-700">Default Invoice</label>
                </div>

                <!-- Vendor Stock Permission -->
                <div class="mt-4 flex items-center gap-3">
                    <input type="checkbox" id="vendor_stock" name="vendor_stock" value="1"
                        {{ $setting && $setting->vendor_stock ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="vendor_stock" class="text-md font-medium text-gray-700">Vendor of Stock</label>
                </div>

                <!-- Memo Status Default-->
                <div class="mt-4 flex items-center gap-3">
                    <input type="checkbox" id="memo_status" name="memo_status" value="1"
                        {{ $setting && $setting->memo_status ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="memo_status" class="text-md font-medium text-gray-700">Memo Status Default</label>
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
