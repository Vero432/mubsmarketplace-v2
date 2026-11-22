@props(['size' => 'h-10'])

@php
    $hasLogo = file_exists(public_path('images/mubs-logo.png'));
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 font-bold']) }}>
    @if ($hasLogo)
        {{-- the white background keeps the logo visible on dark or photo backgrounds --}}
        <img src="{{ asset('images/mubs-logo.png') }}" alt="MUBS logo"
             class="{{ $size }} w-auto bg-white rounded p-0.5">
    @endif
    <span>{{ config('app.name') }}</span>
</span>