<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthSocialiteController extends Controller
{
    function register($driver)
    {

        // setcookie("Socialite", "register", time() + (1000), "/");
        // return Socialite::driver($driver)->redirect();
        // dd($driver);
        return Socialite::driver($driver)->redirect();
    }
    function login($driver)
    {
        setcookie("Socialite", "login", time() + (1000), "/");
        return Socialite::driver($driver)->redirect();
    }
    function callback($driver)
    {

        /* 
            *get the user from socialite then 
            *get the user from BD who have the same email
            
            *if the user exist in BD and the method is register so we 
            redirect the user to /login page coz user is already exist
            
            *if the user not exist and methode login so we 
            redirect the user to /register page to make a signup 
            
            *if the user not exist and methode register so we create the user 
            then make login with is user then redirect to home page 
        
            *if user exist and method is logging so we make the login 
            then redirect to home
            
        */
        // $userSocialite = $driver == "google" ?
        //     Socialite::driver($driver)->stateless()->user() :
        //     Socialite::driver($driver)->user();


        // $user = User::where("email", $userSocialite->getEmail())->first();

        // if ($user  && $_COOKIE['Socialite'] == "register") {
        //     return redirect("/login")->with('status', 'user_exist');
        // }
        // if (!$user  && $_COOKIE['Socialite'] == "login") {
        //     return redirect("/register")->with('status', 'user_not_exist');
        // }
        // if (!$user  && $_COOKIE['Socialite'] == "register") {
        //     $user = User::create([
        //         'name' => $userSocialite->getName(),
        //         'email' => $userSocialite->getEmail(),
        //         'password' => bcrypt($userSocialite->getId()),
        //     ]);
        //     $user->addMediaFromUrl($userSocialite->getAvatar())->toMediaCollection("avatar");

        //     event(new Registered($user));
        // }
        // Auth::guard("web")->login($user);
        // return redirect("/");


        try {
            $socialUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $socialUser->id)->first();
            if ($user) {
                Auth::login($user);
                return redirect('/');
            } else {
                $createUser = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'google_id' => $socialUser->id,
                    'password' => encrypt('123456')
                ]);

                Auth::login($createUser);
                return redirect('/');
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
