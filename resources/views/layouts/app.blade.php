<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MUBS marketplace') }}</title>

    @include('layouts.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-gray-100 text-gray-800 antialiased">

    <x-banner />

    @include('layouts.header')

    {{-- optional page heading, used by Jetstream pages --}}
    @if (isset($header))
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto py-5 px-4">
                {{ $header }}
            </div>
        </div>
    @endif

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 py-6">
        {{ $slot }}
    </main>

    @stack('modals')

    @include('layouts.footer')

    @livewireScripts
</body>
</html>