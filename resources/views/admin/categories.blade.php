@extends('layouts.marketplace')

@section('title', 'Manage categories')

@section('content')

@include('admin._nav')

    <h1 class="text-2xl font-bold mb-4">Categories</h1>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-2 mb-6 max-w-md">
        @csrf
        <input type="text" name="name" placeholder="New category name" value="{{ old('name') }}"
               class="flex-1 border rounded px-3 py-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
    </form>
    @error('name') <p class="text-sm text-red-600 mb-4">{{ $message }}</p> @enderror

    <div class="bg-white rounded shadow max-w-md">
        @foreach ($categories as $category)
            <div class="flex justify-between items-center p-3 border-b last:border-b-0">
                <span>{{ $category->name }}
                    <span class="text-xs text-gray-500">({{ $category->listings_count }} listings)</span></span>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                      onsubmit="return confirm('Delete this category?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline text-sm">Delete</button>
                </form>
            </div>
        @endforeach
    </div>

@endsection