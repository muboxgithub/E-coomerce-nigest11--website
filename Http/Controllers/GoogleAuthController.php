<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Laravel\Socialite\Facades\Socialite;
class GoogleAuthController extends Controller
{
    //function redirec


    public function redirect()

    {
        return Socialite::driver('google')->redirect();
    }

    //handle call back url

    public function callbackGoogle()
{
    try {
        $google_user = Socialite::driver('google')->user();

        $user = User::where('google_id', $google_user->getId())->first();
        if (!$user) {
            $existingUser = User::where('email', $google_user->getEmail())->first();
            if ($existingUser) {
                Auth::login($existingUser);
                return redirect()->intended('home');
            }

            $new_user = User::create([
                'google_id' => $google_user->getId(),
                'name' => $google_user->getName(),
                'email' => $google_user->getEmail()
                
            ]);

            Auth::login($new_user);
            return redirect()->intended('home');
        } else {
            Auth::login($user);
            return redirect()->intended('home');
        }
    } catch (\Throwable $th) {
        dd('Something went wrong: ' . $th->getMessage());
    }
}

}
