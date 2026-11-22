@extends('layouts.marketplace')

@section('title', 'My reports')

@section('content')

    <h1 class="text-2xl font-bold mb-4">My reports</h1>

    @forelse ($reports as $report)
        <div class="bg-white rounded shadow p-4 mb-3">
            <div class="flex justify-between text-sm">
                <span>
                    <strong>{{ $report->reason }}</strong>
                    @if ($report->listing)
                        on <a href="{{ route('listings.show', $report->listing) }}"
                              class="text-blue-700 hover:underline">{{ $report->listing->title }}</a>
                    @else
                        (listing removed)
                    @endif
                </span>
                <span class="px-2 py-0.5 rounded text-xs
                    {{ $report->status == 'resolved' ? 'bg-green-100 text-green-800'
                       : ($report->status == 'dismissed' ? 'bg-gray-200 text-gray-700'
                       : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($report->status) }}
                </span>
            </div>

            @if ($report->details)
                <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $report->details }}</p>
            @endif

            @if ($report->admin_note)
                <p class="mt-2 text-sm bg-gray-50 p-2 rounded">
                    <strong>Administrator note:</strong> {{ $report->admin_note }}
                </p>
            @endif

            <p class="mt-2 text-xs text-gray-500">Sent {{ $report->created_at->diffForHumans() }}</p>
        </div>
    @empty
        <p class="text-gray-600">You have not reported anything.</p>
    @endforelse

@endsection