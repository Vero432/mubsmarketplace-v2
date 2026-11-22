<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // Inbox: messages I received and messages I sent, newest first
    public function index()
    {
        $messages = Message::with(['sender', 'receiver', 'listing'])
            ->where('receiver_id', auth()->id())
            ->orWhere('sender_id', auth()->id())
            ->latest()
            ->get();

        // mark the ones I received as read (the page above still shows "New" for this visit)
        Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.index', compact('messages'));
    }

    // Send a message
    public function store(Request $request)
    {
        $data = $request->validate([
            'listing_id'  => 'required|exists:listings,id',
            'receiver_id' => 'nullable|exists:users,id',
            'body'        => 'required|string|max:1000',
        ]);

        $listing = Listing::findOrFail($data['listing_id']);

        if (auth()->id() === $listing->user_id) {
            // The seller can only reply to someone who already wrote about this listing
            $receiverId = $data['receiver_id'] ?? null;

            $allowed = $receiverId && Message::where('listing_id', $listing->id)
                ->where('sender_id', $receiverId)
                ->where('receiver_id', auth()->id())
                ->exists();

            abort_unless($allowed, 403);
        } else {
            // only buyer accounts can start a conversation with a seller
            abort_unless(auth()->user()->account_type === 'buyer', 403, 'Only buyer accounts can contact sellers.');
        
            $receiverId = $listing->user_id;
        }

        Message::create([
            'listing_id'  => $listing->id,
            'sender_id'   => auth()->id(),   // taken from the login, never from the form
            'receiver_id' => $receiverId,
            'body'        => $data['body'],
        ]);

        return back()->with('success', 'Message sent.');
    }
}