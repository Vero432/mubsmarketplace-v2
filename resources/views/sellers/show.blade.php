@extends('layouts.marketplace')

@section('title', $user->name)

@section('content')

    <div class="bg-white rounded shadow p-5 mb-6">
        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
        <p class="text-sm text-gray-500">Member since {{ $user->created_at->format('F Y') }}</p>

        {{-- Phone and email are NOT shown here, on purpose (privacy) --}}

        <p class="mt-2">
            @if ($ratings->count())
                <strong>{{ number_format($average, 1) }} / 5</strong>
                from {{ $ratings->count() }} rating{{ $ratings->count() > 1 ? 's' : '' }}
            @else
                No ratings yet
            @endif
        </p>
    </div>

    <h2 class="text-xl font-semibold mb-3">Available items</h2>
    @if ($listings->count())
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            @foreach ($listings as $listing)
                <a href="{{ route('listings.show', $listing) }}"
                   class="bg-white rounded shadow hover:shadow-md p-3 block">
                    <h3 class="font-semibold truncate">{{ $listing->title }}</h3>
                    <p class="text-blue-700 font-bold">UGX {{ number_format($listing->price) }}</p>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-gray-600 mb-8">No items available right now.</p>
    @endif

    <h2 class="text-xl font-semibold mb-3">Ratings</h2>
    @forelse ($ratings as $rating)
        <div class="bg-white rounded shadow p-3 mb-2">
            <p class="text-sm"><strong>{{ $rating->rater->name }}</strong> gave {{ $rating->stars }} / 5</p>
            @if ($rating->comment)
                <p class="text-gray-700 whitespace-pre-line">{{ $rating->comment }}</p>
            @endif
        </div>
    @empty
        <p class="text-gray-600">No ratings yet.</p>
    @endforelse

@endsection