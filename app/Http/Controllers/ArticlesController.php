<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
    * Create a new controller instance.
    *
    * @return void
    */

   public function __construct()
   {
       $this->middleware('auth', ['except' => ['index', 'show']]);
   }

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
            'content' => 'required',
            'cover_image' => 'image|nullable|max:1999'
            // Add any other validation rules you need
        ]);

        if($request->hasFile('cover_image')){
            //get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename .'_'.time().'.'.$extension;
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else{
            $fileNameToStore = 'noimage.jpeg';
        }

         // Create a new article
         $article = new Article;
         $article->title = $request->input('title');
         $article->content = $request->input('content'); 
         $article->cover_image = $fileNameToStore;
         $article->user_id = auth()->user()->id;
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
        if(auth()->user()->id !== $article->user_id){
            return redirect('/articles')->with('error', 'Unautorized page');
        }

    
        return view('editArticle')->with('article',$article);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'image|nullable|max:1999'
            // Add any other validation rules you need
        ]);

        if($request->hasFile('cover_image')){
            //get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename .'_'.time().'.'.$extension;
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } 


         // Create a new article
         $article = Article::find($id);
         $article->title = $request->input('title');
         $article->content = $request->input('content'); 
         if($request->hasFile('cover_image')){
            $article->cover_image = $fileNameToStore;
         }
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

        if(auth()->user()->id !== $article->user_id){
            return redirect('/articles')->with('error', 'Unautorized page');
        }

        if($article->cover_image != 'noimage.jpg'){
            Storage::delete('public/cover_images/'.$article->cover_image);
        }
    
        $article->delete(); 

    return redirect()->route('articles.index')->with('success', 'Article deleted successfully');
    }
}
