<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Trainer;
use App\Models\Reservation;
use App\Models\GroupReservation;
use App\Models\GroupParticipant; // Importovanie modelu GroupParticipant
use App\Models\Room;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationSuccessful;
use App\Mail\ReservationEdited;

class ReservationController extends Controller
{
    // Metóda na zobrazenie zoznamu rezervácií
    public function index()
    {
        $reservations = Reservation::all();
        $groupReservations = GroupReservation::with('participants')->get();
        
        return view('reservations.index', compact('reservations', 'groupReservations'));
    }

    // Metóda na zobrazenie formulára pre úpravu rezervácie
    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }

    // Metóda na zobrazenie formulára pre vytvorenie novej rezervácie
    public function create()
    {
        $trainer = auth()->user()->trainer; // Načítanie trénera aktuálne prihláseného používateľa
        $rooms = Room::all();
        $clients = Client::all(); 

        return view('reservations.create', [
            'sessionPrice' => $trainer->session_price, // Cena tréningu
            'rooms' => $rooms, // Zoznam miestností
            'clients' => $clients, // Zoznam klientov
        ]);
    }

    // Metóda na uloženie novej rezervácie
    public function submit(Request $request, $reservation_id)
    {
        $client = Auth::user()->client;
        $reservation = Reservation::findOrFail($reservation_id);
        $reservationCost = $reservation->reservation_price;

        // Kontrola, či má klient dostatočný kredit
        if ($client->credit < $reservationCost) {
            return redirect()->route('credit.charge_credit')->with('error', 'Nedostatočný kredit!');
        }

        $client->decrement('credit', $reservationCost);

        // Vytvorenie transakcie pre platbu rezervácie
        $transaction = Transaction::create([
            'client_id' => $client->user_id,
            'amount' => $reservationCost,
            'description' => 'Reservation payment',
            'id_reservation' => $reservation_id,
        ]);

        // Aktualizácia rezervácie s ID klienta a ID transakcie
        $reservation->update([
            'client_id' => $client->user_id,
            'transaction_id' => $transaction->id,
        ]);

        // Odoslanie potvrdzovacieho emailu klientovi
        Mail::to($client->user->email)->send(new ReservationSuccessful($reservation));

        return redirect()->route('reservations.index')->with('success', 'Rezervácia bola úspešne potvrdená');
    }

    // Metóda na uloženie novej rezervácie do databázy
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'nullable|exists:clients,id', 
            'start_time' => 'required',
            'end_time' => 'required',
            'reservation_price' => 'required|numeric|min:0',
        ]);

        $trainerId = auth()->user()->trainer->user_id;
        $minAllowedStartTime = Carbon::now()->addHour();
        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('start_time'))->toDateTimeString();
        $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('reservation_date') . ' ' . $request->input('end_time'))->toDateTimeString();
        
        if (Carbon::parse($startDateTime)->lte($minAllowedStartTime)) {
            return redirect()->route('reservations.create')->with('error', 'Reservation start time must be at least 1 hour from now.');
        }
        
        // Kontrola prekrývajúcich sa rezervácií
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

        if ($overlappingReservations) {
            return redirect()->route('reservations.create')->with('error', 'Overlapping Group reservations are not allowed.');
        }

        // Vytvorenie novej rezervácie
        Reservation::create([
            'client_id' => $request->input('client_id'),
            'trainer_id' => $trainerId,
            'start_reservation' => $startDateTime,
            'end_reservation' => $endDateTime,
            'reservation_price' => $request->input('reservation_price'),
        ]);

        return redirect()->route('reservations.create')->with('success', 'Reservation created successfully');
    }

    // Metóda na aktualizáciu existujúcej rezervácie
    public function update(Request $request, $id)
    {
        // Validačné pravidlá pre aktualizáciu
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'reservation_price' => 'required|numeric|min:0',
        ]);

        $trainerId = Auth::user()->trainer->user_id;

        $reservation = Reservation::findOrFail($id);
        $reservationDate = Carbon::parse($request->input('start_time'))->toDateString();
        $startDate = Carbon::parse($reservation->start_reservation)->toDateString();
        $endDate = Carbon::parse($reservation->end_reservation)->toDateString();
        $minAllowedStartTime = Carbon::now()->addHour();

        if (Carbon::parse($startDate)->lte($minAllowedStartTime)) {
            return redirect()->route('reservations.create')->with('error', 'Reservation start time must be at least 1 hour from now.');
        }

        // Kombinácia existujúceho dátumu s novými časovými vstupmi
        $newStartDateTime = Carbon::parse($startDate . ' ' . $request->input('start_time'));
        $newEndDateTime = Carbon::parse($endDate . ' ' . $request->input('end_time'));

        // Kontrola, či nové časové rozmedzie neprekrýva iné rezervácie
        $overlappingReservations = Reservation::where('trainer_id', $trainerId)
            ->where('id', '<>', $id) // Vylúčenie aktuálnej rezervácie z kontroly
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
            ->where('id', '<>', $id) // Vylúčenie aktuálnej rezervácie z kontroly
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

        // Aktualizácia atribútov rezervácie
        $reservation->update([
            'start_reservation' => $newStartDateTime,
            'end_reservation' => $newEndDateTime,
            'reservation_price' => $request->input('reservation_price'),
        ]);

        if ($reservation->client_id) {
            Mail::to($reservation->client->user->email)->send(new ReservationEdited($reservation));
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully');
    }

    // Metóda na zmazanie rezervácie
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $transaction = Transaction::where('id_reservation', $id)->first();

        if ($transaction) {
            $client = Client::findOrFail($reservation->client_id);
            $client->credit += $transaction->amount;
            $client->save();

            // Vytvorenie novej transakcie pre vrátenie peňazí
            Transaction::create([
                'client_id' => $client->user_id,
                'amount' => -$transaction->amount,
                'description' => 'Refund for reservation',
                'id_reservation' => $id,
            ]);

            $transaction->delete();
        }

        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully, and client credit refunded if applicable.');
    }
}
