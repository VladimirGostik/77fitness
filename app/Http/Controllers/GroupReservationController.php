<?php

// GroupReservationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupReservation; // Adjust the namespace based on your project structure

class GroupReservationController extends Controller
{
    public function create()
    {
        // Add any logic needed for the create view
        return view('group_reservations.create');
    }

    public function store(Request $request)
    {
        // Add validation as needed
        $request->validate([
            // Define your validation rules here
        ]);

        // Create a new group reservation based on the form input
        GroupReservation::create([
            'trainer_id' => auth()->user()->trainer->id,
            'room_id' => $request->input('room_id'),
            'max_participants' => $request->input('max_participants'),
            'is_reserved' => false,
            'start_reservation' => $request->input('start_reservation'),
            'end_reservation' => $request->input('end_reservation'),
        ]);

        // Redirect or perform any other action after successful creation
        return redirect()->route('group_reservations.create')->with('success', 'Group reservation created successfully');
    }
}
