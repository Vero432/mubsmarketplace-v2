@php
    $hasCampus = file_exists(public_path('images/campus.jpg'));
@endphp

<div class="min-h-screen grid md:grid-cols-2">

    {{-- Left: photo panel (hidden on phones) --}}
    <div class="hidden md:flex relative flex-col justify-between text-white p-10 bg-blue-800"
         @if ($hasCampus)
             style="background-image: url('{{ asset('images/campus.jpg') }}'); background-size: cover; background-position: center;"
         @endif>

        {{-- dark blue layer so the white text is easy to read on any photo --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(to top, rgba(23,37,84,0.92), rgba(30,64,175,0.55));"></div>

        <div class="relative">
            <a href="/"><x-brand size="h-12" class="text-white text-2xl" /></a>
        </div>

        <div class="relative">
            <h1 class="text-4xl font-bold leading-tight">
                Buy, sell and offer services<br>within the MUBS community.
            </h1>
            <ul class="mt-6 space-y-3 text-blue-100">
                <li class="flex gap-2"><span class="text-yellow-300">&#10003;</span> For MUBS members only</li>
                <li class="flex gap-2"><span class="text-yellow-300">&#10003;</span> Message sellers without sharing your number</li>
                <li class="flex gap-2"><span class="text-yellow-300">&#10003;</span> Report problems and follow the outcome</li>
            </ul>
        </div>
    </div>

    {{-- Right: the form --}}
    <div class="flex flex-col items-center justify-center bg-gray-50 px-6 py-10">

        {{-- logo shows here on phones, where the photo panel is hidden --}}
        <div class="md:hidden">{{ $logo }}</div>

        <div class="w-full max-w-md mt-6 md:mt-0 bg-white shadow-lg rounded-lg px-7 py-8 border-t-4 border-yellow-400">

            <h2 class="text-2xl font-bold text-gray-900">
                @if (request()->routeIs('register'))
                    Create your account
                @elseif (request()->routeIs('login'))
                    Welcome back
                @else
                    Account
                @endif
            </h2>
            <p class="text-sm text-gray-500 mb-5">
                @if (request()->routeIs('register'))
                    Use your MUBS email to join.
                @elseif (request()->routeIs('login'))
                    Log in to browse, post and message.
                @endif
            </p>

            {{ $slot }}
        </div>

        <a href="/" class="mt-6 text-sm text-gray-500 hover:text-blue-700">&larr; Back to home</a>
    </div>
</div>