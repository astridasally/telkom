<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome - Minibold</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center relative">

    <div class="fixed inset-0 bg-gradient-to-br from-blue-100 to-blue-300 -z-10"></div>

    <div class="bg-white p-10 rounded-2xl shadow-xl max-w-md w-full text-center border border-blue-200">
        
        <h1 class="text-4xl font-extrabold mb-4 text-gray-800">Welcome to</h1>

        <a href="{{ route('dashboard') }}" class="inline-block mb-6">
            <img src="{{ asset('image/minibolt-logo.png') }}" class="h-12 mx-auto" alt="Minibold Logo">
        </a>

        <p class="text-gray-600 mb-8">Your journey begins here.</p>

        <a href="{{ route('login') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition transform hover:scale-105 inline-block">
           Login
        </a>

    </div>
</body>
</html>