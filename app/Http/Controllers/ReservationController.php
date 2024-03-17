<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\Client;
use App\Models\Trainer;
use App\Models\Reservation;
use App\Models\GroupReservation;
use App\Models\Room;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        // Fetch all reservations
        $reservations = Reservation::all();
        $groupReservations = GroupReservation::all();

        return view('reservations.index', compact('reservations', 'groupReservations'));
    }


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


    public function submit(Request $request, $reservation_id)
    {
    Log::info('Submitting reservation with ID: ' . $reservation_id);
    $request->validate([
        ]);

        $client_id = Auth::user()->client->id;
        $reservation = Reservation::find($reservation_id);

        $reservation->update([
            'client_id' => $client_id,
        ]);
        // Optionally, return a response indicating success or failure
    }

    


    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }



    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'reservation_price' => 'required|numeric|min:0',
        ]);

        // Fetch the trainer's ID from the authenticated user
        $trainerId = auth()->user()->trainer->id;
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('start_time'))->toDateTimeString();
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('end_time'))->toDateTimeString();
        
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


        // Create a new reservation
        Reservation::create([
            'client_id' => null,
            'trainer_id' => $trainerId,
            'start_reservation' => $startDateTime,
            'end_reservation' => $endDateTime,
            'reservation_price' => $request->input('reservation_price'),
        ]);

        // Redirect back with a success message
        return redirect()->route('reservations.create')->with('success', 'Reservation created successfully');
    }



    public function update(Request $request, $id)
    {
        // Validation rules for the update action
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'reservation_price' => 'required|numeric|min:0',
        ]);
        //dd($request->all());
        $trainerId = Auth::user()->trainer->id;

        $reservation = Reservation::findOrFail($id);
        $reservationDate = Carbon::parse($request->input('start_time'))->toDateString();
        if (Carbon::parse($reservationDate)->isPast()) {
            return redirect()->back()->with('error', 'Cannot update reservation with a past date.');
        }
    

        $startDate = Carbon::parse($reservation->start_reservation)->toDateString();
        $endDate = Carbon::parse($reservation->end_reservation)->toDateString();

        // Combine the existing date with the new time inputs
        $newStartDateTime = Carbon::parse($startDate . ' ' . $request->input('start_time'));
        $newEndDateTime = Carbon::parse($endDate . ' ' . $request->input('end_time'));

        // Check if the new time range overlaps with other reservations
        $overlappingReservations = Reservation::where('trainer_id', $trainerId)
            ->where('id', '<>', $id) // Exclude the current reservation from the check
            ->where(function ($query) use ($newStartDateTime, $newEndDateTime) {
                $query->whereBetween('start_reservation', [$newStartDateTime, $newEndDateTime])
                    ->orWhereBetween('end_reservation', [$newStartDateTime, $newEndDateTime])
                    ->orWhere(function ($query) use ($newStartDateTime, $newEndDateTime) {
                        $query->where('start_reservation', '<', $newEndDateTime)
                            ->where('end_reservation', '>', $newStartDateTime);
                    });
            })
            ->exists();

        if ($overlappingReservations) {
            return redirect()->back()->with('error', 'The reservation overlaps with an existing reservation.');
        }

        $overlappingReservations = GroupReservation::where('trainer_id', $trainerId)
            ->where('id', '<>', $id) // Exclude the current reservation from the check
            ->where(function ($query) use ($newStartDateTime, $newEndDateTime) {
                $query->whereBetween('start_reservation', [$newStartDateTime, $newEndDateTime])
                    ->orWhereBetween('end_reservation', [$newStartDateTime, $newEndDateTime])
                    ->orWhere(function ($query) use ($newStartDateTime, $newEndDateTime) {
                        $query->where('start_reservation', '<', $newEndDateTime)
                            ->where('end_reservation', '>', $newStartDateTime);
                    });
            })
            ->exists();


            if ($overlappingReservations) {
                return redirect()->back()->with('error', 'The reservation overlaps with an existing Group reservation.');
            }
    

        // Update the reservation attributes
        $reservation->update([
            'start_reservation' => $newStartDateTime,
            'end_reservation' => $newEndDateTime,
            'reservation_price' => $request->input('reservation_price'),
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully');
    }



    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        // Add any additional authorization logic if needed

        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully');
    }


// ReservationController.php



}