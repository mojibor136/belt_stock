@extends('layouts.app')
@section('title', 'Account Setting')
@section('content')
    <div class="w-full flex flex-col gap-6 mb-20">

        <!-- Header -->
        <div class="flex flex-col bg-white shadow rounded md:p-6 p-4 gap-2">
            <h2 class="text-2xl font-bold text-gray-800">Account Settings</h2>
            <p class="text-gray-600 text-sm">Update your personal information and account preferences</p>
        </div>

        <!-- Form Card -->
        <div class="w-full bg-white rounded shadow px-6 py-6">
            <form action="{{ route('account.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mt-4">
                    <label for="name" class="block text-md font-medium text-gray-700 mb-1.5">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" placeholder="Enter your name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700"
                        value="{{ old('name', auth()->user()->name) }}">
                    @error('name')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <label for="email" class="block text-md font-medium text-gray-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" placeholder="Enter your email"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700"
                        value="{{ old('email', auth()->user()->email) }}">
                    @error('email')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-md font-medium text-gray-700 mb-1.5">
                        Password
                    </label>
                    <input type="password" id="password" name="password" placeholder="Enter new password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                    @error('password')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-md font-medium text-gray-700 mb-1.5">
                        Confirm Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Confirm new password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                </div>

                <!-- Profile Image -->
                <div class="mt-4">
                    <label for="image" class="block text-md font-medium text-gray-700 mb-1.5">
                        Profile Image
                    </label>
                    <input type="file" id="image" name="image"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-md focus:ring-1 focus:ring-blue-600 focus:outline-none text-gray-700">
                    @error('image')
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
