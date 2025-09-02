<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Login</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-200 bg-cover bg-center relative min-h-screen"
    style="background-image: url('{{ asset('image/mind.jpg') }}');">

    <!-- Semi Dark Overlay -->
    <div class="absolute inset-0 bg-gray-800 bg-opacity-40"></div>

    <!-- Login Card -->
    <div class="relative z-10 flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-100 border border-gray-300 p-8 rounded-2xl shadow-xl w-full max-w-md text-gray-800">
            <h3 class="text-center text-3xl font-bold mb-6 flex items-center justify-center gap-2">
                <i class="ri-shopping-cart-2-line text-blue-500 text-4xl"></i>
                Stock Login
            </h3>

            @if (session()->has('error'))
                <div class="bg-red-100 text-red-600 py-2 px-4 mb-4 rounded-md border border-red-300">
                    <span class="text-md"><i class="ri-error-warning-line mr-1"></i>{{ session()->get('error') }}</span>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Username -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold mb-1 text-gray-700">
                        <i class="ri-user-3-line mr-1"></i>Username
                    </label>
                    <input type="email" name="email" id="email" placeholder="Enter your email"
                        class="w-full px-4 py-2.5 rounded-md bg-gray-50 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        value="{{ old('email') }}">
                    @error('email')
                        <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold mb-1 text-gray-700">
                        <i class="ri-lock-2-line mr-1"></i>Password
                    </label>
                    <input type="password" name="password" id="password" placeholder="Enter your password"
                        class="w-full px-4 py-2.5 rounded-md bg-gray-50 text-gray-900 placeholder-gray-400 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 transition-all duration-300 py-2.5 rounded-lg font-semibold text-white flex items-center justify-center gap-2">
                    <i class="ri-login-box-line text-lg"></i>Log In
                </button>
            </form>
        </div>
    </div>

</body>

</html>
