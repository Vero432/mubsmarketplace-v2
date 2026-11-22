<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account status | {{ config('app.name') }}</title>

    @include('layouts.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 px-4">

    <div class="w-full max-w-md bg-white rounded-lg shadow-md border-t-4 border-yellow-400 p-8 text-center">

        <x-brand size="h-12" class="text-blue-800 text-xl justify-center mb-4" />

      @if ($user->status === 'pending')

    @if (!$user->hasVerifiedEmail())
        <h1 class="text-xl font-bold">Verify your email address</h1>

        <p class="mt-3 text-gray-600 text-sm">
            Thank you for registering, {{ $user->name }}.
            Please check your MUBS email and click the verification link
            we sent you before your account can be approved.
        </p>
    @else
        <h1 class="text-xl font-bold">Your account is waiting for approval</h1>

        <p class="mt-3 text-gray-600 text-sm">
            Your email has been verified. An administrator is now checking
            your registration. You will receive an update once your account
            has been reviewed.
        </p>
    @endif
        @elseif ($user->status === 'rejected')
            <h1 class="text-xl font-bold text-red-700">Your registration was not approved</h1>
            @if ($user->status_note)
                <p class="mt-3 text-sm"><strong>Reason:</strong> {{ $user->status_note }}</p>
            @endif
            <p class="mt-3 text-gray-600 text-sm">Please contact the administrator if you think this is a mistake.</p>
        @else
            <h1 class="text-xl font-bold text-red-700">Your account has been suspended</h1>
            @if ($user->status_note)
                <p class="mt-3 text-sm"><strong>Reason:</strong> {{ $user->status_note }}</p>
            @endif
            <p class="mt-3 text-gray-600 text-sm">Please contact the administrator.</p>
        @endif

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="px-5 py-2 rounded bg-blue-700 text-white">Log out</button>
        </form>
    </div>
</body>
</html>