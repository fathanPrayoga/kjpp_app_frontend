<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.Laravel = { user: @json(Auth::user()) };</script>
    <title>KJPP App</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] font-['Inter'] antialiased">
    <div class="min-h-screen">
        @include('partials.header')

        @php $isDashboard = request()->routeIs('dashboard'); @endphp
        <main class="{{ $isDashboard ? 'pt-24 pb-6 h-full overflow-hidden' : 'pt-24 pb-6 min-h-screen overflow-auto' }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>