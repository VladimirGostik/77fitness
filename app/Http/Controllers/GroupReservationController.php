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
use App\Models\GroupReservation; // Upravte názov priestoru podľa štruktúry vášho projektu
use App\Models\GroupParticipant; // Importovanie modelu GroupParticipant
use App\Models\Room;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Mail\GroupReservationConfirmation;
use App\Mail\GroupReservationEdited;
use Illuminate\Support\Facades\Mail;
use PDF; // Použitie aliasu

class GroupReservationController extends Controller
{
    // Metóda na zobrazenie všetkých rezervácií
    public function index()
    {
        $reservations = Reservation::all(); // Získanie všetkých rezervácií
        $groupReservations = GroupReservation::with('participants')->get(); // Získanie všetkých skupinových rezervácií s účastníkmi

        return view('reservations.index', compact('reservations', 'groupReservations')); // Vrátenie pohľadu s rezerváciami
    }

    // Metóda na vytvorenie novej rezervácie
    public function create()
    {
        $trainerId = auth()->user()->trainer->user_id; // Predpokladá sa vzťah medzi User a Trainer
        $trainer = Trainer::findOrFail($trainerId);
        $rooms = Room::all(); // Získanie všetkých miestností

        return view('reservations.create', [
            'sessionPrice' => $trainer->session_price, // Cena tréningovej hodiny
            'rooms' => $rooms,
        ]);
    }

    // Metóda na uloženie novej rezervácie
    public function submit(Request $request, $id)
    {   
        $user = Auth::user();
        $client = $user->client;  // Predpokladá sa vzťah medzi User a Client
        $clientCredit = $client->credit;
        $client_id = $client->user_id;  // Získanie ID klienta z modelu Client

        // Získanie maximálneho počtu účastníkov pre túto skupinovú rezerváciu
        $groupReservation = GroupReservation::findOrFail($id);
        $maxParticipants = $groupReservation->max_participants;

        // Získanie ďalších účastníkov z požiadavky
        $participants = $request->input('participants', []);
        $totalParticipants = count($participants); // Použitie funkcie count na zistenie počtu účastníkov

        // Kontrola, či pridaním účastníkov neprekročíme limit
        if (count($participants) + $totalParticipants > $maxParticipants) {
            return redirect()->back()->with('error', 'Cannot update reservation... too many people');
        }

        $reservationCost = 12 + ($totalParticipants * 12);

        if ($clientCredit < $reservationCost) {
            // Presmerovanie na stránku na doplnenie kreditu
            return redirect()->route('credit.charge_credit')->with('error', 'Insufficient credit. Please top up your credit.');
        }
        
        // Vytvorenie transakcie
        Transaction::create([
            'client_id' => $client_id,
            'amount' => $reservationCost,
            'description' => 'Group reservation payment',
            'id_group_reservation' => $id,
        ]);
        
        // Pridanie autentifikovaného používateľa ako účastníka
        $participant = new GroupParticipant();
        $participant->group_id = $id;
        $participant->client_id = $client_id;
        $participant->name = $user->first_name; // Alebo použite iné vhodné pole pre meno účastníka
        $client->credit -= $reservationCost;
        $client->save();
        $participant->save();
        
        // Pridanie ďalších účastníkov z požiadavky
        foreach ($participants as $participantName) {
            $participant = new GroupParticipant();
            $participant->group_id = $id;
            $participant->client_id = $client_id;
            $participant->name = $participantName;
            $participant->save();
        }
        
        $clientEmail = Auth::user()->email;
        Mail::to($clientEmail)->send(new GroupReservationConfirmation($groupReservation)); // Odoslanie potvrdenia emailom

        // Vrátenie JSON odpovede s úspešnou správou
        return response()->json(['message' => 'Group participants added successfully']);
    }

    // Metóda na pridanie účastníka do skupinovej rezervácie
    public function addParticipant(GroupReservation $groupReservation, Request $request)
    {
        // Validácia logiky na zabezpečenie výberu účastníka a zabránenie prekročeniu max. limitu
        $userParticipantId = $request->input('participant_id');    
        $client = Client::where('user_id', $userParticipantId)->first(); // Získanie klienta

        $participant = User::find($userParticipantId);
        $participantName = $participant ? $participant->first_name . ' ' . $participant->last_name : '';

        // Vytvorenie nového účastníka skupiny
        GroupParticipant::create([
            'group_id' => $groupReservation->id,
            'client_id' => $client->user_id, // Použitie ID klienta
            'name' => $participantName,
        ]);

        return redirect()->route('group_reservations.edit', $groupReservation->id)
            ->with('success', 'Participant added successfully!');
    }

    // Metóda na stiahnutie PDF s detailmi skupinovej rezervácie
    public function downloadPdf($id)
    {
        $groupReservation = GroupReservation::with(['participants.client.user', 'trainer.user', 'room'])->findOrFail($id);
        $pdf = PDF::loadView('group_reservations.pdf', ['groupReservation' => $groupReservation]);
        return $pdf->download('group_reservations.pdf');
    }

    // Metóda na získanie detailov skupinovej rezervácie
    public function getGroupReservationDetails($id)
    {
        $groupReservation = GroupReservation::findOrFail($id); // Predpokladá sa model GroupReservation pre skupinové rezervácie

        $client_id = Auth::user()->client_id;

        // Počítanie účastníkov pre túto skupinovú rezerváciu
        $participantCount = GroupParticipant::where('group_id', $groupReservation->id)->count();

        return response()->json([
            'groupReservation' => $groupReservation,
            'client_id' => $client_id,
            'participantCount' => $participantCount,
        ]);
    }

    // Metóda na získanie účastníkov skupinovej rezervácie
    public function getParticipants($id)
    {
        try {
            // Získanie účastníkov skupiny pre dané ID skupiny
            $participants = GroupParticipant::where('group_id', $id)->get();
            
            // Vrátenie účastníkov ako JSON odpovede
            return response()->json($participants);
        } catch (\Exception $e) {
            // Spracovanie výnimiek a vrátenie chyby
            return response()->json(['error' => 'Failed to fetch group participants.'], 500);
        }
    }

    // Metóda na uloženie novej skupinovej rezervácie
    public function store(Request $request)
    {
        // Pridanie validácie podľa potreby
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'max_participants' => 'required|integer|min:1',
            'room_id' => 'required|exists:rooms,id',
        ]);

        // Predpokladá sa, že dátum je poslaný z formulára, upravte formát podľa potreby
        $trainerId = auth()->user()->trainer->user_id;
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('start_time'))->toDateTimeString();
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('end_time'))->toDateTimeString();

        // Kontrola prekrývajúcich sa rezervácií
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
        
        // Ak existujú prekrývajúce sa rezervácie, zobrazte chybu
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

        // Ak existujú prekrývajúce sa rezervácie, zobrazte chybu
        if ($overlappingReservations) {
            return redirect()->route('reservations.create')->with('error', 'Overlapping reservations are not allowed.');
        }

        // Vytvorenie novej skupinovej rezervácie
        GroupReservation::create([
            'trainer_id' => $trainerId,
            'start_reservation' => $startDateTime,
            'end_reservation' => $endDateTime,
            'max_participants' => $request->input('max_participants'),
            'room_id' => $request->input('room_id'),
        ]);

        return redirect()->route('reservations.create')->with('success', 'Group Reservation created successfully');
    }

    // Metóda na úpravu skupinovej rezervácie
    public function edit(GroupReservation $groupReservation)
    {
        // Získanie potrebných údajov
        $trainerId = auth()->user()->trainer->user_id;
        $trainer = Trainer::findOrFail($trainerId);
        $rooms = Room::all();
        $clients = Client::all();

        // Vrátenie pohľadu s potrebnými údajmi
        return view('group_reservations.edit', compact('groupReservation', 'rooms', 'clients'));
    }

    // Metóda na aktualizáciu existujúcej skupinovej rezervácie
    public function update(Request $request, $id)
    {
        // Validácia pravidiel pre aktualizáciu
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'max_participants' => 'required|integer|min:1',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $trainerId = Auth::user()->trainer->user_id;
        $groupReservation = GroupReservation::findOrFail($id);

        $startDate = Carbon::parse($groupReservation->start_reservation)->toDateString();
        $endDate = Carbon::parse($groupReservation->end_reservation)->toDateString();
    
        // Kombinácia existujúceho dátumu s novými časovými vstupmi
        $newStartDateTime = Carbon::parse($startDate . ' ' . $request->input('start_time'));
        $newEndDateTime = Carbon::parse($endDate . ' ' . $request->input('end_time'));

        // Kontrola prekrývajúcich sa rezervácií
        $overlappingReservations = Reservation::where('trainer_id', $trainerId)
            ->where('id', '<>', $id) // Vylúčiť aktuálnu rezerváciu z kontroly
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
            ->where('id', '<>', $id) // Vylúčiť aktuálnu rezerváciu z kontroly
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

        // Aktualizácia atribútov skupinovej rezervácie
        $groupReservation->update([
            'start_reservation' => $newStartDateTime,
            'end_reservation' => $newEndDateTime,
            'max_participants' => $request->input('max_participants'),
            'room_id' => $request->input('room_id'),
        ]);

        $participants = GroupParticipant::where('group_id', $groupReservation->id)->select('client_id')->distinct()->get();

        // Odoslanie emailu každému účastníkovi o úprave rezervácie
        foreach ($participants as $participant) {
            $client = Client::find($participant->client_id);
            $clientEmail = $client->user->email;
            Mail::to($clientEmail)->send(new GroupReservationEdited($groupReservation));
        }

        // Presmerovanie späť s úspešnou správou
        return redirect()->route('group_reservations.edit', $id)
            ->with('success', 'Group reservation updated successfully!');
    }

    // Metóda na zmazanie skupinovej rezervácie
    public function destroy(GroupReservation $groupReservation)
    {
        // Logika na zmazanie skupinovej rezervácie z databázy
        $groupReservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Group Reservation deleted successfully');
    }
}
