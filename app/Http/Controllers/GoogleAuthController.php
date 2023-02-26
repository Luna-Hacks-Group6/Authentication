<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    //
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }
    public function callbackGoogle(){
        try {
            $google_user= Socialite::driver('google')->user();
            $user= User::where('google_id', $google_user->getId())->first();

            if(!$user){
                $new_user = User::create([
                    'name'=> $google_user->getName(),
                    'email'=> $google_user->getEmail(),
                    'google_id'=> $google_user->getId(),
                ]);
                Auth::login($new_user);
                return back();
            }
            else{
                Auth::login($user);
                return back();
            }
        } catch (\Throwable $th) {
            dd("Sth went wrong".$th->getMessage());
        }
    }
}
