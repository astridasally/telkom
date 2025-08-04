<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <title>Welcome - Minibold</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center relative bg-[#f5f6f8]">

    <!-- Container -->
    <div class="bg-white p-10 rounded-2xl shadow-xl max-w-md w-full text-center border border-gray-200">

        <!-- Judul -->
        <h1 class="text-2xl mb-5 text-gray-700" style="font-family: 'Inter', serif;">
            Welcome to
        </h1>

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="inline-block mb-6">
            <img src="{{ asset('image/minibolt-logo.png') }}" class="h-12 mx-auto" alt="Minibold Logo">
        </a>

        <!-- Subtext -->
        <p class="text-gray-600 mb-8">Your journey begins here.</p>

        <!-- Tombol Login -->
       
        <a href="{{ route('login') }}">
            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </a>


    </div>

</body>
</html>
