<footer class="bg-white border-t-4 border-blue-700 mt-10">

    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-4">

        <a href="{{ route('listings.index') }}">
            <x-brand size="h-8" class="text-blue-700" />
        </a>

        <nav class="flex flex-wrap justify-center gap-5 text-sm text-gray-600">
            <a href="{{ route('listings.index') }}" class="hover:text-blue-700">Browse</a>
            @if (Auth::user()-> account_type === 'seller' || Auth::user()->role === 'admin')
            <a href="{{ route('listings.create') }}" class="hover:text-blue-700">Post an item</a>
            <a href="{{ route('listings.mine') }}" class="hover:text-blue-700">My listings</a>
            @endif
            <a href="{{ route('messages.index') }}" class="hover:text-blue-700">Messages</a>
            <a href="{{ route('guidelines') }}" class="hover:text-blue-700">Guidelines</a>
            <a href="{{ route('terms.show') }}" class="hover:text-blue-700">Terms</a>
<a href="{{ route('policy.show') }}" class="hover:text-blue-700">Privacy Policy</a>
        </nav>
    </div>

    <div class="border-t">
        <p class="max-w-7xl mx-auto px-4 py-3 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</footer>