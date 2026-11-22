@extends('layouts.marketplace')

@section('title', 'Manage users')

@section('content')

    @include('admin._nav')

    <h1 class="text-2xl font-bold mb-4">Users</h1>

    {{-- status tabs and search --}}
    <div class="flex flex-wrap gap-2 mb-4 text-sm items-center">
        <a href="{{ route('admin.users') }}" class="px-3 py-1 rounded border">All</a>
        <a href="{{ route('admin.users', ['status' => 'pending']) }}" class="px-3 py-1 rounded border bg-yellow-100">Pending ({{ $counts['pending'] }})</a>
        <a href="{{ route('admin.users', ['status' => 'active']) }}" class="px-3 py-1 rounded border">Active ({{ $counts['active'] }})</a>
        <a href="{{ route('admin.users', ['status' => 'rejected']) }}" class="px-3 py-1 rounded border">Rejected ({{ $counts['rejected'] }})</a>
        <a href="{{ route('admin.users', ['status' => 'suspended']) }}" class="px-3 py-1 rounded border">Suspended ({{ $counts['suspended'] }})</a>

        <form method="GET" class="flex ml-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name"
                   class="border rounded-l px-2 py-1">
            <button class="bg-blue-600 text-white px-3 rounded-r">Search</button>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">Phone</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Listings</th>
                    <th class="p-3">Email Verification</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="p-3">{{ $user->name }}<br>
                            <span class="text-xs text-gray-500">{{ $user->email }}</span></td>
                        <td class="p-3">{{ $user->phone ?? '-' }}</td>
                        <td class="p-3">{{ $user->role === 'admin' ? 'Admin' : ucfirst($user->account_type) }}</td>
                        <td class="p-3">{{ $user->listings_count }}</td>
                        <td class="p-3">
                            @if ($user->hasVerifiedEmail()) <span class="text-green-600">Verified</span>
                        @else
                    <span class="text-red-600">Not verified</span>
                @endif</td>

                        <td class="p-3">{{ ucfirst($user->status) }}</td>
                        <td class="p-3">
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.status', $user) }}"
                                      class="flex flex-wrap gap-2 items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="text" name="note" placeholder="Reason (reject or suspend)"
                                           class="border rounded px-2 py-1 text-xs w-44">

                                    @if ($user->status !== 'active')
                                        <button name="status" value="active" class="text-green-700 hover:underline">
                                            {{ $user->status === 'pending' ? 'Approve' : 'Reactivate' }}
                                        </button>
                                    @endif
                                    @if ($user->status === 'pending')
                                        <button name="status" value="rejected" class="text-red-600 hover:underline">Reject</button>
                                    @endif
                                    @if ($user->status === 'active')
                                        <button name="status" value="suspended" class="text-red-600 hover:underline">Suspend</button>
                                    @endif
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection