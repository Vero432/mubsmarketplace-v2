@extends('layouts.marketplace')

@section('title', 'Messages')

@section('content')

    <h1 class="text-2xl font-bold mb-4">Messages</h1>

    @forelse ($messages as $message)

        @php $received = $message->receiver_id === auth()->id(); @endphp

        <div class="bg-white rounded shadow p-4 mb-3 {{ $received ? '' : 'border-l-4 border-blue-300' }}">

            <div class="flex justify-between text-sm text-gray-500">
                <span>
                    @if ($received)
                        From <strong>{{ $message->sender->name }}</strong>
                    @else
                        To <strong>{{ $message->receiver->name }}</strong>
                    @endif
                    about
                    <a href="{{ route('listings.show', $message->listing) }}"
                       class="text-blue-700 hover:underline">{{ $message->listing->title }}</a>
                </span>
                <span>{{ $message->created_at->diffForHumans() }}</span>
            </div>

            @if ($received && $message->read_at === null)
                <span class="inline-block mt-1 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded">New</span>
            @endif

            {{-- {{ }} escapes the text, so nobody can inject HTML or scripts --}}
            <p class="mt-2 whitespace-pre-line">{{ $message->body }}</p>

            @if ($received)
                <form method="POST" action="{{ route('messages.store') }}" class="mt-3 flex gap-2">
                    @csrf
                    <input type="hidden" name="listing_id" value="{{ $message->listing_id }}">
                    <input type="hidden" name="receiver_id" value="{{ $message->sender_id }}">
                    <input type="text" name="body" placeholder="Reply..." required
                           class="flex-1 border rounded px-3 py-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Reply</button>
                </form>
            @endif
        </div>

    @empty
        <p class="text-gray-600">No messages yet.</p>
    @endforelse

@endsection