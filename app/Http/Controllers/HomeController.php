<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\Trainer;
use App\Models\Reservation;
use App\Models\GroupReservation; 
use App\Models\GroupParticipant; 

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
    $articles = Article::latest()->take(5)->get();
    $trainers = Trainer::all();
    $reservations = Reservation::all();

    $groupReservations = GroupReservation::with('participants')->get();

    return view('home')->with(['articles' => $articles, 'trainers' => $trainers, 'reservations'=>$reservations,'groupReservations'=>$groupReservations]);
}

public function getReservations($trainerId)
    {
        // Logika pro získání rezervací trenéra s ID $trainerId
        $reservations = Reservation::where('trainer_id', $trainerId)->get();
        $groupReservations = GroupReservation::where('trainer_id', $trainerId)->with('participants')->get();

        // Vraťte data jako JSON
        return response()->json([
            'reservations' => $reservations,
            'groupReservations' => $groupReservations
        ]);
    }

}
