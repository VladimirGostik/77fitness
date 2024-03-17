<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Trainer;


class ClientController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->take(5)->get();
        $trainers = Trainer::all();

        return view('home.profileClient', compact('articles', 'trainers')); // Pass $trainers to the view
    }
}
