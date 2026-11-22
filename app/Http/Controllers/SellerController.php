<?php

namespace App\Http\Controllers;

use App\Models\User;

class SellerController extends Controller
{
    public function show(User $user)
    {
        // only available listings, newest first
        $listings = $user->listings()->where('status', 'available')->latest()->get();

        // ratings with the name of the person who wrote each one
        $ratings = $user->ratingsReceived()->with('rater')->latest()->get();

        $average = $ratings->avg('stars');   // empty if there are no ratings yet

        return view('sellers.show', compact('user', 'listings', 'ratings', 'average'));
    }
}