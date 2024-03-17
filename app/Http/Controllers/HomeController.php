<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\Trainer;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    $user_id = auth()->user()->id;  
    $user = User::find($user_id);
    
    $articles = Article::latest()->take(5)->get();
    $trainers = Trainer::all();

    return view('home')->with(['articles' => $articles, 'trainers' => $trainers]);
}
}
