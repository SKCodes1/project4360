<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleAuthController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google callback
    public function callbackGoogle()
    {
        try {
            $google_user = Socialite::driver('google')->stateless()->user();

            // Check if the user exists in the database by google_id
            $user = User::where('google_id', $google_user->getId())->first();

            if (!$user) {
                // If the user doesn't exist, create a new user
                $new_user = User::create([
                    'name' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'google_id' => $google_user->getId(),  // Save google_id
                ]);
            
                Auth::login($new_user);
            } else {
                // If the user exists, log them in
                Auth::login($user);
            }

            // Redirect to the intended page
            return redirect()->intended('home');

        } catch (\Throwable $th) {
            // Handle errors gracefully
            return redirect()->route('login')->with('error', 'Something went wrong: ' . $th->getMessage());
        }
    }
}
