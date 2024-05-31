<?php

namespace App\Http\Controllers;

use App\Models\Article; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\Auth; 

class ArticlesController extends Controller
{
   public function __construct()
   {
    // Middleware pre kontrolu rolí užívateľov
    $this->middleware(function ($request, $next) {
        if (Auth::user()->role === 3 || Auth::user()->role === 2) {
            return $next($request);
        }
        return abort(403, 'Unauthorized action.');
    })->except(['index', 'show']);
   }

    public function index()
    {
        $articles = Article::all(); 
        return view('articles')->with('articles', $articles); // Vrátenie pohľadu s článkami
    }

    public function create()
    {
        return view('createArticle'); 
    }

    public function store(Request $request)
    {
        // Validácia dát z requestu
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Spracovanie súboru s obrázkom
        if ($request->hasFile('cover_image')) {
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpeg'; // Defaultný obrázok
        }

        // Uloženie nového článku do databázy
        $article = new Article;
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->cover_image = $fileNameToStore;
        $article->user_id = auth()->user()->id;
        $article->save();

        return redirect()->route('articles.index')->with('success', 'Article created successfully');
    }

    // Metóda na zobrazenie konkrétneho článku
    public function show(string $id)
    {
        $article = Article::findOrFail($id); // Nájdite článok podľa ID
        $previous = Article::where('id', '<', $article->id)->orderBy('id', 'desc')->first(); // Predchádzajúci článok
        $next = Article::where('id', '>', $article->id)->orderBy('id', 'asc')->first(); // Nasledujúci článok

        return view('showArticle', compact('article', 'previous', 'next')); // Vrátenie pohľadu s článkom
    }

    public function edit(string $id)
    {
        $article = Article::find($id); // Nájdite článok podľa ID

        // Kontrola prístupu na úpravu článku
        if (auth()->user()->role === 3 || (auth()->user()->id === $article->user_id && auth()->user()->role === 2)) {
            return view('editArticle')->with('article', $article);
        } else {
            return redirect('/articles')->with('error', 'Unauthorized access'); 
        }
    }

    // Metóda na aktualizáciu existujúceho článku
    public function update(Request $request, string $id)
    {
        // Validácia dát z requestu
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        $article = Article::find($id); 

        if (!$article) {
            return redirect()->route('articles.index')->with('error', 'Article not found'); // Článok nebol nájdený
        }

        // Kontrola prístupu na aktualizáciu článku
        if (auth()->user()->role === 3 || (auth()->user()->id === $article->user_id && auth()->user()->role === 2)) {
            if ($request->hasFile('cover_image')) {
                // Spracovanie súboru s obrázkom
                $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

                if ($article->cover_image !== 'noimage.jpg') {
                    Storage::delete('public/cover_images/' . $article->cover_image); // Odstránenie starého obrázku
                }

                $article->cover_image = $fileNameToStore;
            }

            // Aktualizácia článku v databáze
            $article->title = $request->input('title');
            $article->content = $request->input('content');
            $article->save();

            return redirect()->route('articles.index')->with('success', 'Article updated successfully');
        } else {
            return redirect('/articles')->with('error', 'Unauthorized access'); // Neoprávnený prístup
        }
    }

    public function destroy(string $id)
    {
        $article = Article::find($id); 

        // Kontrola prístupu na zmazanie článku
        if (auth()->user()->id !== $article->user_id && auth()->user()->role !== 3) {
            return redirect('/articles')->with('error', 'Unauthorized page'); // Neoprávnený prístup
        }

        if ($article->cover_image != 'noimage.jpg') {
            Storage::delete('public/cover_images/' . $article->cover_image); // Odstránenie obrázku
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted successfully');
    }
}
