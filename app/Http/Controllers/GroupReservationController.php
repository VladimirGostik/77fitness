<?php

// GroupReservationController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use App\Models\Trainer;
use App\Models\Reservation;
use App\Models\GroupReservation; // Adjust the namespace based on your project structure
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;


class GroupReservationController extends Controller
{
    public function create()
    {
        $trainerId = auth()->user()->trainer->id; // Assuming you have a relationship between User and Trainer
        $trainer = Trainer::findOrFail($trainerId);
        $rooms = Room::all();

        return view('reservations.create', [
            'sessionPrice' => $trainer->session_price,
            'rooms' => $rooms,
        ]);
    }

    public function store(Request $request)
{
    // Add validation as needed
    $request->validate([
        'start_time' => 'required',
        'end_time' => 'required',
        'max_participants' => 'required|integer|min:1',
        'room_id' => 'required|exists:rooms,id',
    ]);

    // Assume the date is sent from the form, modify the format if needed
    $trainerId = auth()->user()->trainer->id;
    $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('start_time'))->toDateTimeString();
    $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('end_time'))->toDateTimeString();
    
    $overlappingReservations = GroupReservation::where('trainer_id', $trainerId)
    ->where(function ($query) use ($startDateTime, $endDateTime) {
        $query->whereBetween('start_reservation', [$startDateTime, $endDateTime])
            ->orWhereBetween('end_reservation', [$startDateTime, $endDateTime])
            ->orWhere(function ($query) use ($startDateTime, $endDateTime) {
                $query->where('start_reservation', '<=', $endDateTime)
                    ->where('end_reservation', '>=', $startDateTime);
            });
    })
    ->exists();
    // If there are overlapping reservations, display an error
    if ($overlappingReservations) {
        return redirect()->route('reservations.create')->with('error', 'Overlapping Group reservations are not allowed.');
    }

    $overlappingReservations = Reservation::where('trainer_id', $trainerId)
        ->where(function ($query) use ($startDateTime, $endDateTime) {
            $query->whereBetween('start_reservation', [$startDateTime, $endDateTime])
                ->orWhereBetween('end_reservation', [$startDateTime, $endDateTime])
                ->orWhere(function ($query) use ($startDateTime, $endDateTime) {
                    $query->where('start_reservation', '<=', $endDateTime)
                        ->where('end_reservation', '>=', $startDateTime);
                });
        })
        ->exists();

        // If there are overlapping reservations, display an error
        if ($overlappingReservations) {
            return redirect()->route('reservations.create')->with('error', 'Overlapping reservations are not allowed.');
        }

    GroupReservation::create([
        'trainer_id' => $trainerId,
        'start_reservation' => $startDateTime,
        'end_reservation' => $endDateTime,
        'max_participants' => $request->input('max_participants'),
        'room_id' => $request->input('room_id'),

    ]);

    // Redirect back with a success message
    return redirect()->route('reservations.create')->with('success', 'Group Reservation created successfully');
}

    
}
