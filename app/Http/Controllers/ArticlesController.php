<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $articles = Article::all();
        return view('articles')->with('articles',$articles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('createArticle');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { /// Validate the request
        $request->validate([
            'title' => 'required',
            'content' => 'required'
            // Add any other validation rules you need
        ]);
         // Create a new article
         $article = new Article;
         $article->title = $request->input('title');
         $article->content = $request->input('content'); 
         // Set other properties if needed
         $article->save();
 
         // Redirect or return a response as needed
         return redirect()->route('articles.index')->with('success', 'Article created successfully');
        

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $article = Article::find($id);
        return view('showArticle')->with('article',$article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::find($id);
        return view('editArticle')->with('article',$article);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
            // Add any other validation rules you need
        ]);
         // Create a new article
         $article = Article::find($id);
         $article->title = $request->input('title');
         $article->content = $request->input('content'); 
         // Set other properties if needed
         $article->save();
 
         // Redirect or return a response as needed
         return redirect()->route('articles.index')->with('success', 'Article created successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::find($id);
        $article->delete(); 

    return redirect()->route('articles.index')->with('success', 'Article deleted successfully');
    }
}
