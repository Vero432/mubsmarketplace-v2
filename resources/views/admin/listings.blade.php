@extends('layouts.marketplace')

@section('title', 'Manage listings')

@section('content')

@include('admin._nav')

    <h1 class="text-2xl font-bold mb-4">Listings</h1>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3">Item</th>
                    <th class="p-3">Seller</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listings as $listing)
                    <tr class="border-t">
                        <td class="p-3">
                            <a href="{{ route('listings.show', $listing) }}"
                               class="text-blue-700 hover:underline">{{ $listing->title }}</a>
                        </td>
                        <td class="p-3">{{ $listing->user->name }}</td>
                        <td class="p-3">{{ $listing->category->name }}</td>
                        <td class="p-3">{{ ucfirst($listing->status) }}</td>
                        <td class="p-3">
                            <form method="POST" action="{{ route('admin.listings.destroy', $listing) }}"
                                  onsubmit="return confirm('Remove this listing?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection