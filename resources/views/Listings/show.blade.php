@extends('layouts.marketplace')

@section('title', $listing->title)

@section('content')

    {{-- Link back to Browse --}}
    <a href="{{ route('listings.index') }}" class="text-sm text-blue-700 hover:underline">
        &larr; Back to listings
    </a>

    <div class="bg-white rounded shadow mt-3 overflow-hidden md:flex">

        {{-- Left: photo --}}
        <div class="md:w-1/2 bg-gray-200 flex items-center justify-center min-h-[250px]">
            @if ($listing->image)
                <img src="{{ asset('uploads/' . $listing->image) }}"
                     class="w-full h-full object-cover" alt="{{ $listing->title }}">
            @else
                <span class="text-gray-500">No photo</span>
            @endif
        </div>

        {{-- Right: details --}}
        <div class="md:w-1/2 p-5">

            <h1 class="text-2xl font-bold">{{ $listing->title }}</h1>

            <p class="text-2xl text-blue-700 font-bold mt-1">
                UGX {{ number_format($listing->price) }}
            </p>

            {{-- Status badge: green, yellow or grey depending on the status --}}
            <span class="inline-block mt-2 text-sm px-2 py-1 rounded
                {{ $listing->status == 'available' ? 'bg-green-100 text-green-800'
                   : ($listing->status == 'reserved' ? 'bg-yellow-100 text-yellow-800'
                   : 'bg-gray-200 text-gray-600') }}">
                {{ ucfirst($listing->status) }}
            </span>

            <p class="mt-4 whitespace-pre-line">{{ $listing->description }}</p>

            <ul class="mt-4 text-sm text-gray-600 space-y-1">
                <li><strong>Category:</strong> {{ $listing->category->name }}</li>
               @if ($listing->condition !== 'not applicable')
    <li><strong>Condition:</strong> {{ ucfirst($listing->condition) }}</li>
@else
    <li><strong>Type:</strong> Service</li>
@endif
                {{-- diffForHumans turns the date into "2 days ago" --}}
                <li><strong>Posted:</strong> {{ $listing->created_at->diffForHumans() }}</li>
                <li><strong>Seller:</strong>
    <a href="{{ route('sellers.show', $listing->user) }}"
       class="text-blue-700 hover:underline">{{ $listing->user->name }}</a>
</li>
            </ul>

            {{-- Seller's phone number is NOT shown here, on purpose (privacy) --}}

           <div class="mt-6">

   <p class="text-sm text-gray-600">This is your listing.</p>

@if ($listing->isUnderReview())
    <p class="mt-2 text-sm text-red-700">
        This listing has been reported and is under review by an administrator. You cannot edit or delete it
        until the review is finished.
    </p>
@else
    <a href="{{ route('listings.edit', $listing) }}" class="text-sm text-blue-700 hover:underline">Edit this listing</a>
@endif
        <form method="POST" action="{{ route('messages.store') }}" class="space-y-2">
            @csrf
            <input type="hidden" name="listing_id" value="{{ $listing->id }}">

            <label class="block font-semibold">Contact the seller</label>
            <textarea name="body" rows="3" placeholder="Hello, is this still available?"
                      class="w-full border rounded px-3 py-2">{{ old('body') }}</textarea>
            @error('body') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Send message</button>
        </form>

        {{-- Step 3 connects this link --}}
        <a href="{{ route('reports.create', $listing) }}" class="inline-block mt-3 text-sm text-red-600 hover:underline">Report this listing</a>

        @if ($canRate)
    <form method="POST" action="{{ route('ratings.store', $listing) }}"
          class="mt-5 border-t pt-4 space-y-2">
        @csrf
        <label class="block font-semibold">Rate this seller</label>
        <select name="stars" class="border rounded px-3 py-2">
            @foreach ([5, 4, 3, 2, 1] as $n)
                <option value="{{ $n }}">{{ $n }} star{{ $n > 1 ? 's' : '' }}</option>
            @endforeach
        </select>
        <textarea name="comment" rows="2" placeholder="Optional comment"
                  class="w-full border rounded px-3 py-2"></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit rating</button>
    </form>
@endif

</div>
        </div>
    </div>

@endsection