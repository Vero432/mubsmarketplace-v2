<?php

namespace App\Actions\Jetstream;

use App\Models\User;
use Laravel\Jetstream\Contracts\DeletesUsers;
use App\Models\Report;
use Illuminate\Validation\ValidationException;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        // an account with an open report cannot be deleted until an administrator finishes the review
$underReview = Report::where('reported_user_id', $user->id)
    ->whereIn('status', ['open', 'reviewing'])
    ->exists();

if ($underReview) {
    throw ValidationException::withMessages([
        'password' => 'Your account has a report under review, so it cannot be deleted until an administrator finishes the review.',
    ]);
}
        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
