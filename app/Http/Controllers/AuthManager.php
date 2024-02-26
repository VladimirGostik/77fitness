<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AuthManager extends Controller
{
    function login(){
        if(Auth::check()){
            return redirect(route('home'));
        }
        return view('login');
    }

    function registration(){
        if(Auth::check()){
            return redirect(route('home'));
        }
        return view('registration');
    }

    function loginPost(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            return redirect()->intended(route('home'));
        }
        return redirect(route('login'))->with("error","Login details are not valid");
    }

    function registrationPost(Request $request){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone_number' => 'required|unique:users',
            'receive_notifications' => 'boolean',
            'role' => 'integer',
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'receive_notifications' => $request->receive_notifications,
            'role' => $request->role,
        ];


        $user = User::create($data);

            if (!$user) {
                return redirect(route('registration'))->with("error", "Registration details are not valid");
            }
            Auth::login($user);

            return redirect(route('login'))->with("success", "Registration successful");
        }


    function logout(){
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
