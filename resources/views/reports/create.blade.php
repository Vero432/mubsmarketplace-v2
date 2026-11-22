@extends('layouts.marketplace')

@section('title', 'Report listing')

@section('content')

    <h1 class="text-2xl font-bold mb-1">Report a listing</h1>
    <p class="text-gray-600 mb-4">Listing: <strong>{{ $listing->title }}</strong></p>

    <form method="POST" action="{{ route('reports.store', $listing) }}"
          class="bg-white rounded shadow p-5 max-w-xl space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Reason</label>
            <select name="reason" class="w-full border rounded px-3 py-2">
                <option value="">Choose a reason</option>
                @foreach ($reasons as $reason)
                    <option value="{{ $reason }}" @selected(old('reason') == $reason)>{{ $reason }}</option>
                @endforeach
            </select>
            @error('reason') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Details (optional)</label>
            <textarea name="details" rows="4" class="w-full border rounded px-3 py-2"
                      placeholder="Tell the administrator what happened">{{ old('details') }}</textarea>
            @error('details') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded">Send report</button>
            <a href="{{ route('listings.show', $listing) }}" class="px-5 py-2 border rounded">Cancel</a>
        </div>
    </form>

@endsection