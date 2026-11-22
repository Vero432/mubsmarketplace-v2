{{-- Use the master layout (header + footer come automatically) --}}
@extends('layouts.marketplace')

@section('title', 'Browse listings')

@section('content')

    {{-- Page title + "Post an item" button --}}
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Browse listings</h1>
        @if (auth()->user()->account_type === 'seller' || auth()->user()->role === 'admin')
    <a href="{{ route('listings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Post an item</a>
@endif
    </div>

    {{-- Search and filter form. GET puts the search in the URL --}}
    <form method="GET" action="{{ route('listings.index') }}" class="flex flex-wrap gap-2 mb-6">

        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search items..."
               class="border rounded px-3 py-2 flex-1 min-w-[200px]">

        <select name="category" onchange="this.form.submit()" class="border rounded px-3 py-2">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                {{-- @selected keeps the chosen category after searching --}}
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Search</button>
    </form>

    {{-- Show the cards, or a message if nothing matches --}}
    @if ($listings->count())

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($listings as $listing)

                {{-- Each card is a link to the details page --}}
                <a href="{{ route('listings.show', $listing) }}"
                   class="bg-white rounded shadow hover:shadow-md overflow-hidden block">

                    {{-- Photo, or a grey box if there is no photo --}}
                    <div class="h-40 bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
                        @if ($listing->image)
                            <img src="{{ asset('uploads/' . $listing->image) }}"
                                 class="h-40 w-full object-cover" alt="{{ $listing->title }}">
                        @else
                            No photo
                        @endif
                    </div>

                    <div class="p-3">
                        <h2 class="font-semibold truncate">{{ $listing->title }}</h2>

                        {{-- number_format adds commas: 25000 becomes 25,000 --}}
                        <p class="text-blue-700 font-bold">UGX {{ number_format($listing->price) }}</p>

                        <p class="text-xs text-gray-500">
                            {{ $listing->category->name }} | {{ $listing->user->name }}
                        </p>

                        {{-- Colour depends on the status --}}
                        <span class="inline-block mt-2 text-xs px-2 py-1 rounded
                            {{ $listing->status == 'available' ? 'bg-green-100 text-green-800'
                               : ($listing->status == 'reserved' ? 'bg-yellow-100 text-yellow-800'
                               : 'bg-gray-200 text-gray-600') }}">
                            {{ ucfirst($listing->status) }}
                        </span>
                    </div>
                </a>

            @endforeach
        </div>

        {{-- Page numbers --}}
        <div class="mt-6">{{ $listings->links() }}</div>

    @else
        <p class="text-gray-600">No listings found.</p>
    @endif

@endsection