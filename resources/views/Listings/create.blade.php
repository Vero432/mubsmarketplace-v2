@extends('layouts.marketplace')

@section('title', 'Post an item')

@section('content')

    <h1 class="text-2xl font-bold mb-4">Post an item or service</h1>

    {{-- x-data holds two values: the chosen category (cat) and the id of "Services" --}}
    <form method="POST" action="{{ route('listings.store') }}" enctype="multipart/form-data"
          x-data="{ cat: '{{ old('category_id') }}', services: '{{ $servicesId ?? 0 }}' }"
          class="bg-white rounded shadow p-5 max-w-2xl space-y-4">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2">
            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Category</label>
            {{-- x-model keeps "cat" in step with the dropdown --}}
            <select name="category_id" x-model="cat" class="w-full border rounded px-3 py-2">
                <option value="">Choose a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Price (UGX)</label>
            <input type="number" name="price" min="0" value="{{ old('price') }}" class="w-full border rounded px-3 py-2">
            @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Condition: hidden and not sent when the category is Services --}}
        <div x-show="cat != services">
            <label class="block font-semibold mb-1">Condition</label>
            <select name="condition" :disabled="cat == services" class="w-full border rounded px-3 py-2">
                @foreach (['new', 'like new', 'used'] as $option)
                    <option value="{{ $option }}" @selected(old('condition', 'used') == $option)>
                        {{ ucfirst($option) }}
                    </option>
                @endforeach
            </select>
            @error('condition') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <p x-show="cat == services" style="display:none" class="text-sm text-gray-500">
            Services do not need a condition.
        </p>

        <div>
            <label class="block font-semibold mb-1">Description</label>
            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Photo (optional, JPG or PNG, up to 4 MB)</label>
            <input type="file" name="image" accept="image/png, image/jpeg">
            @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded">Post</button>
            <a href="{{ route('listings.index') }}" class="px-5 py-2 border rounded">Cancel</a>
        </div>
    </form>

@endsection