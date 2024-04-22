<?php

// GroupReservationController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Trainer;
use App\Models\Reservation;
use App\Models\GroupReservation; // Adjust the namespace based on your project structure
use App\Models\GroupParticipant; // Import the GroupParticipant model
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;

class GroupReservationController extends Controller
{
    public function index()
{
    $reservations = Reservation::all();
    $groupReservations = GroupReservation::with('participants')->get();

    return view('reservations.index', compact('reservations', 'groupReservations'));
}

    public function create()
    {
        $trainerId = auth()->user()->trainer->user_id; // Assuming you have a relationship between User and Trainer
        $trainer = Trainer::findOrFail($trainerId);
        $rooms = Room::all();

        return view('reservations.create', [
            'sessionPrice' => $trainer->session_price,
            'rooms' => $rooms,
        ]);
    }

    public function submit(Request $request, $id)
{  
    $user = Auth::user();
    $client = $user->client;  // Assuming a User belongsTo Client relationship
    $clientId = $client->user_id;  // Access client ID from the associated Client model

    // Get the maximum participants allowed for this group reservation
    $groupReservation = GroupReservation::findOrFail($id);
    $maxParticipants = $groupReservation->max_participants;

    $totalParticipants =  GroupParticipant::where('group_id', $groupReservation->id)->count();
    // Retrieve additional participants from the request
    $participants = $request->input('participants', []);


    // Check if adding participants would exceed the limit
    if (count($participants) + $totalParticipants > $maxParticipants) {
        return redirect()->back()->with('error', 'Cannot update reservation... too many people');
    }

    // Add the authenticated user as a participant
    $participant = new GroupParticipant();
    $participant->group_id = $id;
    $participant->client_id = $clientId;
    $participant->name = $user->first_name; // Or use any other appropriate field for the participant's name
    $participant->save();

    // Add additional participants from the request
    foreach ($participants as $participantName) {
        $participant = new GroupParticipant();
        $participant->group_id = $id;
        $participant->client_id = $clientId;
        $participant->name = $participantName;
        $participant->save();
    }

    // Return a JSON response indicating success
    return response()->json(['message' => 'Group participants added successfully']);
}

public function addParticipant(GroupReservation $groupReservation, Request $request)
{
    // Validation logic to ensure participant selection and avoid exceeding max limit
    $userParticipantId = $request->input('participant_id');

    //$participantName = User::find($userParticipantId)->name;
    
    $client = Client::where('user_id', $userParticipantId)->first(); // Retrieve the client

    $participant = User::find($userParticipantId);
    $participantName = $participant ? $participant->first_name . ' ' . $participant->last_name : '';
  

    GroupParticipant::create([
        'group_id' => $groupReservation->id,
        'client_id' => $client->user_id, // Use the client's ID
        'name' => $participantName,
    ]);

    return redirect()->route('group_reservations.edit', $groupReservation->id)
    ->with('success', 'Participant added successfully!');
}



    public function getGroupReservationDetails($id)
        {

            $groupReservation = GroupReservation::findOrFail($id); // Assuming GroupReservation is your model for group reservations

            $client_id = Auth::user()->client_id;

            // Count participants for this group reservation
            $participantCount = GroupParticipant::where('group_id', $groupReservation->id)->count();

            return response()->json([
                'groupReservation' => $groupReservation,
                'client_id' => $client_id,
                'participantCount' => $participantCount,
            ]);
        }


    public function getParticipants($id)
        {
            try {
                // Fetch group participants for the given group ID
                $participants = GroupParticipant::where('group_id', $id)->get();
                
                // Return the participants as JSON response
                return response()->json($participants);
            } catch (\Exception $e) {
                // Handle any exceptions and return error response
                return response()->json(['error' => 'Failed to fetch group participants.'], 500);
            }
        }


    public function store(Request $request){
    // Add validation as needed
    $request->validate([
        //'client_id' => 'nullable|exists:clients,id', // Allow null or valid client ID
        'start_time' => 'required',
        'end_time' => 'required',
        'max_participants' => 'required|integer|min:1',
        'room_id' => 'required|exists:rooms,id',
    ]);

    // Assume the date is sent from the form, modify the format if needed
    $trainerId = auth()->user()->trainer->user_id;
    $minAllowedStartTime = Carbon::now()->addHour()->addDay();
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
        $trainerId = auth()->user()->trainer->user_id;
        $trainer = Trainer::findOrFail($trainerId);
        $rooms = Room::all();
        $clients = Client::all();

        // Return the view with the necessary data
        return view('group_reservations.edit', compact('groupReservation', 'rooms', 'clients'));

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

            $trainerId = Auth::user()->trainer->user_id;
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

        return redirect()->route('reservations.index')
            ->with('success', 'Group Reservation deleted successfully');
    }

    
}
