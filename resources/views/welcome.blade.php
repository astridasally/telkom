<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Minibold</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-900 text-black flex items-center justify-center min-h-screen">
    <div class="text-center p-6 bg-gray-300 bg-opacity-90 rounded-lg shadow-xl max-w-md mx-auto">
        <h1 class="text-5xl font-bold tracking-tight mb-8">
            Welcome to 

            <div class="shrink-0 flex items-center justify-center"> <a href="{{ route('dashboard') }}">
        <img src="{{ asset('image/minibolt-logo.png') }}" alt="Minibold Logo" class="block h-9 w-auto">
    </a>
</div>
        </h1>

        <p class="text-lg text-gray-20 mb-8">
            Your journey begins here.
        </p>

        <a href="{{ route('login') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
            Login
        </a>

        {{-- Jika Anda memiliki rute registrasi juga, Anda bisa menambahkannya --}}
        {{-- <a href="{{ route('register') }}" class="ml-4 inline-block text-gray-300 hover:text-white font-bold py-3 px-8 rounded-lg transition duration-300 ease-in-out">
            Register
        </a> --}}
    </div>
</body>
</html>