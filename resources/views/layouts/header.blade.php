@php
    // number of unread messages for the red badge
    $unread = \App\Models\Message::where('receiver_id', Auth::id())->whereNull('read_at')->count();

    // style helpers so the links below stay short
    $link   = 'px-3 py-2 rounded text-sm font-medium';
    $active = 'bg-blue-50 text-blue-700';
    $normal = 'text-gray-600 hover:bg-gray-100';
@endphp

{{-- x-data is Alpine.js: "menu" and "account" remember whether each menu is open --}}
<header class="bg-white shadow-sm sticky top-0 z-40" x-data="{ menu: false, account: false }">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between gap-4 h-16">

            {{-- Brand --}}
            <a href="{{ route('listings.index') }}" class="shrink-0">
    <x-brand size="h-9" class="text-xl text-blue-700" />
</a>

            {{-- Search box (computers) --}}
            <form method="GET" action="{{ route('listings.index') }}" class="hidden md:flex flex-1 max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search items..."
                       class="w-full border border-r-0 rounded-l px-3 py-1.5 text-sm">
                <button type="submit" class="bg-blue-600 text-white px-4 rounded-r text-sm">Search</button>
            </form>

            {{-- Links (computers) --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('listings.index') }}"
                   class="{{ $link }} {{ request()->routeIs('listings.index') ? $active : $normal }}">Browse</a>

            @if (Auth::user()->account_type === 'seller' || Auth::user()->role === 'admin')
                <a href="{{ route('listings.mine') }}"
                   class="{{ $link }} {{ request()->routeIs('listings.mine') ? $active : $normal }}">My listings</a>
                   @endif

                <a href="{{ route('messages.index') }}"
                   class="{{ $link }} {{ request()->routeIs('messages.*') ? $active : $normal }}">
                    Messages
                    @if ($unread)
                        <span class="ml-1 bg-red-600 text-white text-xs rounded-full px-2 py-0.5">{{ $unread }}</span>
                    @endif
                </a>

            @if (Auth::user()->account_type === 'seller' || Auth::user()->role === 'admin')
                <a href="{{ route('listings.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium ml-2">+ Post item</a>
                   @endif

                {{-- Account menu --}}
                <div class="relative ml-2">
                    <button type="button" @click="account = !account" @click.outside="account = false"
                            class="flex items-center gap-1 text-sm text-gray-700 px-2 py-2 rounded hover:bg-gray-100">
                        {{ Auth::user()->name }} <span>&#9662;</span>
                    </button>

                    <div x-show="account" style="display:none"
                         class="absolute right-0 mt-1 w-48 bg-white border rounded shadow py-1 text-sm">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-100">My profile</a>
                        <a href="{{ route('reports.mine') }}" class="block px-4 py-2 hover:bg-gray-100">My reports</a>
                        <a href="{{ route('guidelines') }}" class="block px-4 py-2 hover:bg-gray-100">Guidelines</a>

                        {{-- only administrators see this --}}
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="block px-4 py-2 font-semibold text-blue-700 hover:bg-gray-100">Admin</a>
                        @endif



                        @if (Auth::user()->role !== 'admin')
    <form method="POST" action="{{ route('account.switch') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
            Switch to {{ Auth::user()->account_type === 'seller' ? 'buyer' : 'seller' }} account
        </button>
    </form>
@endif




                        <form method="POST" action="{{ route('logout') }}" class="border-t mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            {{-- Menu button (phones) --}}
            <button type="button" @click="menu = !menu" aria-label="Menu"
                    class="md:hidden text-2xl px-2 text-gray-700">&#9776;</button>
        </div>

        {{-- Menu (phones) --}}
        <div x-show="menu" style="display:none" class="md:hidden pb-4 space-y-1 text-sm">

            <form method="GET" action="{{ route('listings.index') }}" class="flex mb-2">
                <input type="text" name="search" placeholder="Search items..."
                       class="w-full border border-r-0 rounded-l px-3 py-2">
                <button type="submit" class="bg-blue-600 text-white px-4 rounded-r">Search</button>
            </form>

            <a href="{{ route('listings.index') }}" class="block {{ $link }} {{ $normal }}">Browse</a>
            @if (Auth::user()->account_type === 'seller' || Auth::user()->role === 'admin')
            <a href="{{ route('listings.create') }}" class="block {{ $link }} {{ $normal }}">Post an item</a>
            <a href="{{ route('listings.mine') }}" class="block {{ $link }} {{ $normal }}">My listings</a>
            @endif
            <a href="{{ route('messages.index') }}" class="block {{ $link }} {{ $normal }}">
                Messages @if ($unread) ({{ $unread }}) @endif
            </a>
            <a href="{{ route('reports.mine') }}" class="block {{ $link }} {{ $normal }}">My reports</a>
            <a href="{{ route('guidelines') }}" class="block {{ $link }} {{ $normal }}">Guidelines</a>
            <a href="{{ route('profile.show') }}" class="block {{ $link }} {{ $normal }}">My profile</a>

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block {{ $link }} font-semibold text-blue-700">Admin</a>
            @endif


            @if (Auth::user()->role !== 'admin')
    <form method="POST" action="{{ route('account.switch') }}">
        @csrf
        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
            Switch to {{ Auth::user()->account_type === 'seller' ? 'buyer' : 'seller' }} account
        </button>
    </form>
@endif
        
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left {{ $link }} text-red-600">Log out</button>
            </form>
        </div>
    </div>
</header>