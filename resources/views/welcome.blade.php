@php
    $hasCampus  = file_exists(public_path('images/campus.jpg'));
    $categories = \App\Models\Category::orderBy('name')->get();
    $latest     = \App\Models\Listing::with('category')
                    ->where('status', 'available')->latest()->take(4)->get();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @include('layouts.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-white text-gray-800">

    {{-- ========== HERO ========== --}}
    <section class="relative text-white bg-blue-800"
             @if ($hasCampus)
                 style="background-image: url('{{ asset('images/campus.jpg') }}'); background-size: cover; background-position: center;"
             @endif>

        <div class="absolute inset-0"
             style="background: linear-gradient(90deg, rgba(23,37,84,0.93), rgba(30,64,175,0.6));"></div>

        <div class="relative max-w-6xl mx-auto px-4">

            {{-- top bar --}}
            <div class="flex items-center justify-between py-5">
                <x-brand size="h-11" class="text-white text-lg" />

                <div class="flex gap-3 text-sm">
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded border border-white/70 hover:bg-white/10">Log in</a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded bg-yellow-400 text-blue-900 font-semibold hover:bg-yellow-300">Register</a>
                </div>
            </div>

            {{-- headline --}}
            <div class="py-20 md:py-28 max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    Buy, sell and offer services at MUBS
                </h1>
                <p class="mt-5 text-lg text-blue-100">
                    One trusted place for the campus community, instead of scattered WhatsApp groups.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}"
                       class="px-6 py-3 rounded bg-yellow-400 text-blue-900 font-semibold hover:bg-yellow-300">
                        Get started
                    </a>
                    <a href="{{ route('login') }}"
                       class="px-6 py-3 rounded border border-white/70 hover:bg-white/10">
                        I already have an account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <main class="flex-1">

        {{-- ========== RECENT ITEMS ========== --}}
        @if ($latest->count())
            <section class="max-w-6xl mx-auto px-4 py-14">
                <div class="flex items-end justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Recently posted</h2>
                    <a href="{{ route('login') }}" class="text-sm text-blue-700 hover:underline">Log in to see more &rarr;</a>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($latest as $listing)
                        <a href="{{ route('login') }}"
                           class="bg-white rounded-lg shadow hover:shadow-lg overflow-hidden block border">
                            <div class="h-40 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                                @if ($listing->image)
                                    <img src="{{ asset('uploads/' . $listing->image) }}"
                                         class="h-40 w-full object-cover" alt="{{ $listing->title }}">
                                @else
                                    No photo
                                @endif
                            </div>
                            <div class="p-3">
                                <h3 class="font-semibold truncate">{{ $listing->title }}</h3>
                                <p class="text-blue-700 font-bold">UGX {{ number_format($listing->price) }}</p>
                                <p class="text-xs text-gray-500">{{ $listing->category->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ========== CATEGORIES ========== --}}
        @if ($categories->count())
            <section class="bg-gray-50 border-y">
                <div class="max-w-6xl mx-auto px-4 py-10">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Browse by category</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($categories as $category)
                            <a href="{{ route('login') }}"
                               class="px-4 py-2 rounded-full bg-white border text-sm hover:border-blue-700 hover:text-blue-700">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ========== HOW IT WORKS ========== --}}
        <section class="max-w-6xl mx-auto px-4 py-14">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-10">How it works</h2>

            <div class="grid gap-8 md:grid-cols-3 text-center">
                <div>
                    <div class="mx-auto w-14 h-14 rounded-full bg-blue-700 text-white text-2xl font-bold flex items-center justify-center">1</div>
                    <h3 class="mt-4 font-semibold text-lg">Join with your registration number</h3>
                    <p class="mt-2 text-sm text-gray-600">An administrator approves each account, so the community stays genuine.</p>
                </div>
                <div>
                    <div class="mx-auto w-14 h-14 rounded-full bg-blue-700 text-white text-2xl font-bold flex items-center justify-center">2</div>
                    <h3 class="mt-4 font-semibold text-lg">Post or find what you need</h3>
                    <p class="mt-2 text-sm text-gray-600">Search by item or category, and message sellers without sharing your number.</p>
                </div>
                <div>
                    <div class="mx-auto w-14 h-14 rounded-full bg-blue-700 text-white text-2xl font-bold flex items-center justify-center">3</div>
                    <h3 class="mt-4 font-semibold text-lg">Meet on campus and review</h3>
                    <p class="mt-2 text-sm text-gray-600">Trade in person, rate the seller, and report anything suspicious.</p>
                </div>
            </div>
        </section>

        {{-- ========== CALL TO ACTION ========== --}}
        <section class="bg-blue-700 text-white">
            <div class="max-w-6xl mx-auto px-4 py-12 text-center">
                <h2 class="text-2xl font-bold">Ready to start trading on campus?</h2>
                <a href="{{ route('register') }}"
                   class="inline-block mt-5 px-6 py-3 rounded bg-yellow-400 text-blue-900 font-semibold hover:bg-yellow-300">
                    Create your account
                </a>
            </div>
        </section>
    </main>

    {{-- ========== FOOTER (copyright only) ========== --}}
    <footer class="bg-white border-t-4 border-blue-700">
    <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-4">

        <a href="{{ url('/') }}">
            <x-brand size="h-8" class="text-blue-700" />
        </a>

        <nav class="flex flex-wrap justify-center gap-5 text-sm text-gray-600">
            <a href="{{ route('login') }}" class="hover:text-blue-700">Log in</a>
            <a href="{{ route('register') }}" class="hover:text-blue-700">Register</a>
            @if (Route::has('terms.show'))
                <a href="{{ route('terms.show') }}" class="hover:text-blue-700">Terms and Conditions</a>
            @endif
            @if (Route::has('policy.show'))
                <a href="{{ route('policy.show') }}" class="hover:text-blue-700">Privacy Policy</a>
            @endif
        </nav>
    </div>

    <div class="border-t">
        <p class="max-w-6xl mx-auto px-4 py-3 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</footer>
</body>
</html>