<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function edit($id)
    {
        return view('auth.account.profile-edit', ['id' => $id]);
    }

    protected function update(Request $request)
    {
        // dd($request);
        $request->expectsJson();
        // dd(Auth::user()->id);
        // dd(User::where('id', Auth::user()->id)->first());

        $request->validate([
            'name' => 'nullable|min:3|max:50',
            'email' => 'email',
            'password' => 'nullable|min:6',
            
        ]);


        $user = User::where('id', Auth::user()->id)->first();
        // dd($user);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->update();

        // dd($request->file('u_image'));
        if (!empty($request->file('u_image'))) {

            $image = $request->file('u_image');
            // dd($image);
            $input['imagename'] = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images/users');
            $image->move($destinationPath, $input['imagename']);
            $user->hero_image = $input['imagename'];
            // dd($user->hero_image);
            $user->update();
            
        }

        if(!empty($request->input('password_confirm'))){
            if($request->input('password') == $request->input('password_confirm')){
                $user = User::where('id', Auth::user()->id)->first();
                $user->password = Hash::make($request->input('password'));
                $user->update();

            }else{
                Session::flash('message', 'Password not matched with Password Confirm!');
                Session::flash('alert-class', 'alert-danger');
                return Redirect::back();
            }
        }

        
        // dd('last test');
        Session::flash('message', 'Your Account data Updated Sucessfully!');
        Session::flash('alert-class', 'alert-success');
        return Redirect::back();

    }
}
