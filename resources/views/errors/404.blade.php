@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="flex items-center justify-center h-full px-4">
        <div class="text-center">
            <h1 class="text-7xl font-extrabold text-red-500 drop-shadow-md">404</h1>

            <h2 class="text-2xl font-semibold text-gray-800 mt-4">
                Oops! The page you’re looking for doesn’t exist.
            </h2>

            <p class="text-gray-600 mt-3 max-w-md mx-auto">
                It looks like the page you tried to reach is not available. <br>
                This might be because the link is broken, the page was removed, or it never existed.
            </p>

            <div class="mt-6 flex items-center justify-center gap-4">
                <a href="{{ url('/') }}"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                    ⬅ Back to Home
                </a>
                <a href="{{ url()->previous() }}"
                    class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg shadow hover:bg-gray-300 transition">
                    🔄 Go Back
                </a>
            </div>

            <p class="mt-6 text-sm text-gray-500">
                Need help? <a href="{{ url('/contact') }}" class="text-blue-600 hover:underline">Contact Support</a>
            </p>
        </div>
    </div>
@endsection
