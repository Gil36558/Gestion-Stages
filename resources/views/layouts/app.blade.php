<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PlateformeStages') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    @if(!request()->is('login', 'register', 'password/*'))
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="container mx-auto px-4 py-8">
            @yield('content')
        </main>
    @else
        <!-- Pour les pages d'authentification -->
        @yield('content')
    @endif

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>