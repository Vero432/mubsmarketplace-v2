@extends('layouts.marketplace')

@section('title', 'Home')

@section('content')
    <h1 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}</h1>
    <p>Listings will appear here.</p>
@endsection