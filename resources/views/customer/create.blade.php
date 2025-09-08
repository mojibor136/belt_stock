@extends('layouts.app')
@section('title', 'Add New Customer')
@section('content')
    @include('components.toast')
    <div class="w-full flex flex-col gap-4 mb-20">
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 md:gap-1 gap-3">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Customer</h2>
                <a href="{{ route('customer.index') }}"
                    class="block md:hidden bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Customer
                </a>
            </div>
            <div class="flex justify-between items-center text-gray-600 text-sm">
                <p>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline">Home</a> / Customer
                    /
                    Create
                </p>
                <a href="{{ route('customer.index') }}"
                    class="hidden md:block bg-teal-500 text-white px-4 md:py-2 py-2.5 rounded text-sm font-medium hover:bg-teal-600 transition">
                    All Customer
                </a>
            </div>
        </div>

        <div class="w-full bg-white rounded shadow px-6">
            <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4 mt-6">
                    <label for="name" class="block text-gray-700 font-medium">Customer Name <span
                            class="text-red-500">*</span></label> <input type="text" name="name" id="name"
                        placeholder="Customer Name" class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700"
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-medium">Phone <span
                            class="text-red-500">*</span></label> <input type="text" name="phone" id="name"
                        placeholder="Phone" class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700"
                        value="{{ old('phone') }}">
                    @error('phone')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="amount" class="block text-gray-700 font-medium">Amount <span
                            class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="amount" id="amount" placeholder="Amount"
                        class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700" value="{{ old('amount') }}">
                    @error('amount')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">Email</span></label> <input type="text"
                        name="email" id="email" placeholder="Email"
                        class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700" value="{{ old('email') }}">
                    @error('email')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-medium">Address<span
                            class="text-red-500">*</span></label> <input type="text" name="address" id="address"
                        placeholder="Address" class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700"
                        value="{{ old('address') }}">
                    @error('address')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="transport" class="block text-gray-700 font-medium">Transport<span
                            class="text-red-500">*</span></label> <input type="text" name="transport" id="transport"
                        placeholder="Transport" class="w-full mt-2 p-2 border rounded border-gray-300 text-gray-700"
                        value="{{ old('transport') }}">
                    @error('transport')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-end mb-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
