<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Message;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Listing $listing)
    {
        // Rules: not your own listing, listing must be sold,
        // you must have contacted the seller, and you can rate only once
        abort_unless(auth()->user()->account_type === 'buyer', 403);
        abort_if(auth()->id() === $listing->user_id, 403);
        abort_unless($listing->status === 'sold', 403);

        abort_unless(
            Message::where('listing_id', $listing->id)->where('sender_id', auth()->id())->exists(),
            403
        );

        abort_if(
            Rating::where('listing_id', $listing->id)->where('rater_id', auth()->id())->exists(),
            403
        );

        $data = $request->validate([
            'stars'   => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Rating::create([
            'listing_id' => $listing->id,
            'rater_id'   => auth()->id(),
            'seller_id'  => $listing->user_id,
            'stars'      => $data['stars'],
            'comment'    => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Thank you for your feedback.');
    }
}