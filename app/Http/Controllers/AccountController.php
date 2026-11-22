<?php

namespace App\Http\Controllers;

class AccountController extends Controller
{
    public function switch()
    {
        $user = auth()->user();

        abort_if($user->role === 'admin', 403);

        $user->account_type = $user->account_type === 'seller' ? 'buyer' : 'seller';
        $user->save();

        return redirect()->route('listings.index')
            ->with('success', 'You are now using a ' . $user->account_type . ' account.');
    }
}