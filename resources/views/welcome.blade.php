<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <title>Welcome - Minibold</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('image/tel.webp') }}');">

    <!-- Container utama -->
    <div class="relative z-10 bg-white p-10 rounded-2xl shadow-xl max-w-md w-full text-center border border-gray-200">

        <!-- Judul -->
        <h1 class="text-2xl mb-5 text-gray-800">
            Welcome to
        </h1>

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="inline-block mb-2">
            <img src="{{ asset('image/minibolt-logo.png') }}" class="h-24 mx-auto" alt="Minibold Logo">
        </a>

        <!-- Subtext -->
        <p class="text-gray-600 mb-8">Explore miniBolt here.</p>

        <!-- Tombol Login -->
        <a href="{{ route('login') }}">
            <x-primary-button class="ms-3 bg-[#d46a28] hover:bg-[#bb551a] focus:ring-[#d46a28]">
                {{ __('Log in') }}
            </x-primary-button>
        </a>
<!--
        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >
                Register
            </a>
        @endif
-->
    </div>

</body>

</html>
