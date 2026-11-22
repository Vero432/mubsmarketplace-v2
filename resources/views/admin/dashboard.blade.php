@extends('layouts.marketplace')

@section('title', 'Admin dashboard')

@section('content')

@include('admin._nav')

    <h1 class="text-2xl font-bold mb-4">Administrator dashboard</h1>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white rounded shadow p-4"><p class="text-sm text-gray-500">Users</p>
            <p class="text-2xl font-bold">{{ $userCount }}</p></div>
        <div class="bg-white rounded shadow p-4"><p class="text-sm text-gray-500">Waiting for approval</p>
            <p class="text-2xl font-bold">{{ $pendingUsers }}</p></div>
        <div class="bg-white rounded shadow p-4"><p class="text-sm text-gray-500">Listings</p>
            <p class="text-2xl font-bold">{{ $listingCount }}</p></div>
        <div class="bg-white rounded shadow p-4"><p class="text-sm text-gray-500">Open reports</p>
            <p class="text-2xl font-bold">{{ $openReports }}</p></div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.users') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Users</a>
        <a href="{{ route('admin.listings') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Listings</a>
        <a href="{{ route('admin.reports') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Reports</a>
        <a href="{{ route('admin.categories') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Categories</a>
    </div>

@endsection