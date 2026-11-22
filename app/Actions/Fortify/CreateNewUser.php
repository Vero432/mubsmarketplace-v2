<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {

        Validator::make($input, [
            'name'         => ['required', 'string', 'max:255'],

            // only official MUBS email addresses
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users',
                               'regex:/^[A-Za-z0-9._%+-]+@mubs\.ac\.ug$/i'],
            'phone'        => ['required', 'string', 'max:20'],
            'account_type' => ['required', 'in:buyer,seller'],
            'password'     => $this->passwordRules(),
            'terms'        => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'email.regex' => 'Please use your official @mubs.ac.ug email address.',
        ])->validate();

        $user = User::create([
            'name'         => $input['name'],
            'email'        => $input['email'],
            'password'     => Hash::make($input['password']),
            'phone'        => $input['phone'],
            'account_type' => $input['account_type'],
        ]);

        // new accounts wait for an administrator to approve them
        $user->status = 'pending';
        $user->terms_accepted_at = now();
        $user->save();

        return $user;
    }
}