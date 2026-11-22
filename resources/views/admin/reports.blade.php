@extends('layouts.marketplace')

@section('title', 'Manage reports')

@section('content')

@include('admin._nav')
    <h1 class="text-2xl font-bold mb-4">Reports</h1>

    @forelse ($reports as $report)
        <div class="bg-white rounded shadow p-4 mb-3">

            <p class="text-sm text-gray-500">
                From <strong>{{ $report->reporter->name }}</strong>
                about
                @if ($report->listing)
                    <a href="{{ route('listings.show', $report->listing) }}"
                       class="text-blue-700 hover:underline">{{ $report->listing->title }}</a>
                @else
                    a removed listing
                @endif
                @if ($report->reportedUser)
                    (seller: {{ $report->reportedUser->name }})
                @endif
                , {{ $report->created_at->diffForHumans() }}
            </p>

            <p class="mt-1"><strong>{{ $report->reason }}</strong></p>
            @if ($report->details)
                <p class="text-gray-700 whitespace-pre-line">{{ $report->details }}</p>
            @endif

            <form method="POST" action="{{ route('admin.reports.update', $report) }}"
                  class="mt-3 flex flex-wrap gap-2 items-start">
                @csrf
                @method('PATCH')

                <select name="status" class="border rounded px-2 py-2">
                    @foreach (['open', 'reviewing', 'resolved', 'dismissed'] as $s)
                        <option value="{{ $s }}" @selected($report->status == $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>

                <textarea name="admin_note" rows="2" placeholder="Note for the reporter (optional)"
                          class="flex-1 min-w-[200px] border rounded px-3 py-2">{{ $report->admin_note }}</textarea>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
            </form>
        </div>
    @empty
        <p class="text-gray-600">No reports.</p>
    @endforelse

@endsection