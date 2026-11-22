<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @include('layouts.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-gray-100 text-gray-800">

    @include('layouts.header')

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 py-6">
        {{-- one-time message set by ->with('success', '...') --}}
@if (session('success'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
        {{ session('error') }}
    </div>
@endif
        @yield('content')
    </main>

    @include('layouts.footer')

    @livewireScripts
</body>
</html>