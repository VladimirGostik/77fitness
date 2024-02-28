<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
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

    function registrationPost(Request $request)
{
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

    // Create the user
    $user = User::create($data);

    if (!$user) {
        return redirect(route('registration'))->with("error", "Registration details are not valid");
    }

    // Create the associated client

    $client = $user->client()->create(['user_id' => $user->id]);

    
    // Log in the user
    Auth::login($user);

    // Redirect to login route
    return redirect(route('login'))->with("success", "Registration successful");
}



    function logout(){
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
