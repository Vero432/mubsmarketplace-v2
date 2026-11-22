@extends('layouts.marketplace')

@section('title', 'My listings')

@section('content')

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">My listings</h1>
        <a href="{{ route('listings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
            + Post an item
        </a>
    </div>

    @if ($listings->count())
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3">Item</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($listings as $listing)
    @php $underReview = $listing->isUnderReview(); @endphp

    <tr class="border-t">
        <td class="p-3">
            <a href="{{ route('listings.show', $listing) }}" class="text-blue-700 hover:underline">{{ $listing->title }}</a>
        </td>
        <td class="p-3">UGX {{ number_format($listing->price) }}</td>

        <td class="p-3">
            @if ($underReview)
                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded">Under review</span>
                <p class="text-xs text-gray-500 mt-1">{{ ucfirst($listing->status) }}</p>
            @else
                <form method="POST" action="{{ route('listings.status', $listing) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="border rounded px-2 py-1" onchange="this.form.submit()">
                        @foreach (['available', 'reserved', 'sold'] as $s)
                            <option value="{{ $s }}" @selected($listing->status == $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </form>
            @endif
        </td>

        <td class="p-3">
            @if ($underReview)
                <span class="text-xs text-gray-500">Locked until an administrator finishes the review</span>
            @else
                <div class="flex gap-4 items-center">
                    <a href="{{ route('listings.edit', $listing) }}" class="text-blue-700 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('listings.destroy', $listing) }}"
                          onsubmit="return confirm('Delete this listing?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            @endif
        </td>
    </tr>
@endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600">You have not posted anything yet.</p>
    @endif

@endsection