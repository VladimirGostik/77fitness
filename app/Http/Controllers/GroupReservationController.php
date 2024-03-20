<?php

// GroupReservationController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use App\Models\Trainer;
use App\Models\Reservation;
use App\Models\GroupReservation; // Adjust the namespace based on your project structure
use App\Models\GroupParticipant; // Import the GroupParticipant model
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;


class GroupReservationController extends Controller
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

    public function submit(Request $request, $groupReservation_id)
        {
            Log::info('Submitting reservation with ID: ' . $groupReservation_id);
    
        }

    public function getGroupReservationDetails($id)
        {
            $groupReservation = GroupReservation::findOrFail($id); // Assuming GroupReservation is your model for group reservations

            // Count participants for this group reservation
            $participantCount = GroupParticipant::where('group_id', $groupReservation->id)->count();

            return response()->json([
                'groupReservation' => $groupReservation,
                'participantCount' => $participantCount,
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
    $minAllowedStartTime = Carbon::now()->addHour();
    $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('start_time'))->toDateTimeString();
    $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('end_time'))->toDateTimeString();
    
    if (Carbon::parse($startDateTime)->lte($minAllowedStartTime)) {
        return redirect()->route('reservations.create')->with('error', 'Reservation start time must be at least 1 hour from now.');
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

    public function edit(GroupReservation $groupReservation)
    {
        // Fetch the necessary data
        $trainerId = auth()->user()->trainer->id;
        $trainer = Trainer::findOrFail($trainerId);
        $rooms = Room::all();

        // Return the view with the necessary data
        return view('group_reservations.edit', compact('groupReservation', 'rooms'));
        // You may need to fetch any necessary data from the database
        return view('group_reservations.edit', compact('groupReservation'));
    }

    public function update(Request $request, $id)
        {
            // Validation rules for the update action

            //dd($request->all());

            $request->validate([
                'start_time' => 'required',
                'end_time' => 'required',
                'max_participants' => 'required|integer|min:1',
                'room_id' => 'required|exists:rooms,id',
            ]);

            $trainerId = Auth::user()->trainer->id;
            $groupReservation = GroupReservation::findOrFail($id);
            $reservationDate = Carbon::parse($request->input('start_time'))->toDateString();
            
            if (Carbon::parse($reservationDate)->isPast()) {
                return redirect()->back()->with('error', 'Cannot update reservation with a past date.');
            }
        
        

            $startDate = Carbon::parse($groupReservation->start_reservation)->toDateString();
            $endDate = Carbon::parse($groupReservation->end_reservation)->toDateString();
    
            // Combine the existing date with the new time inputs
            $newStartDateTime = Carbon::parse($startDate . ' ' . $request->input('start_time'));
            $newEndDateTime = Carbon::parse($endDate . ' ' . $request->input('end_time'));

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

            dd($groupReservation->all());

            // Update the group reservation attributes
            $groupReservation->update([
                'start_reservation' => $request->input('start_time'),
                'end_reservation' => $request->input('end_time'),
                'max_participants' => $request->input('max_participants'),
                'room_id' => $request->input('room_id'),
            ]);

            // Redirect back with a success message
            return redirect()->route('group_reservations.edit')->with('success', 'Group Reservation updated successfully');
        }



public function destroy(GroupReservation $groupReservation)
    {
        // Logic for deleting the group reservation from the database
        $groupReservation->delete();

        return redirect()->route('group_reservations.index')
            ->with('success', 'Group Reservation deleted successfully');
    }

    
}
