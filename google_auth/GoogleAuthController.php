<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $new_user = User::create(
                    ['name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                    ]);
                Auth::login($new_user);
                return response()->json([
                    'success' => true,
                ], \Illuminate\Http\Response::HTTP_OK);
            } else {
                Auth::login($user);
                return response()->json([
                    'success' => true,
                ], \Illuminate\Http\Response::HTTP_OK);
            }

        } catch (\Exception $ex) {
            return self::httpBadRequest($ex->getMessage(), $ex->getCode());
        }
    }

}