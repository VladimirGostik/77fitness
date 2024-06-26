<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Uistite sa, že importujete model User
use App\Models\Trainer;
use App\Models\Client;
use App\Models\Reservation;
use App\Models\GroupParticipant;
use App\Models\GroupReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TrainerController extends Controller
{
    /**
     * Zobrazenie formulára na vytvorenie nového trénera.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $trainers = Trainer::all();
        return view('trainers.index', compact('trainers'));
    }

    public function show(Trainer $trainer)
    {
        $trainer->load('profilePhotos');
        $reservations = Reservation::all();
        $groupReservations = GroupReservation::withCount('participants')->get();
        $clients = Client::all();

        return view('trainers.profile', compact('trainer', 'reservations', 'clients','groupReservations'));
    }

    public function create()
    {
        return view('trainers.create'); // Vytvorte tento pohľad podľa potreby
    }

    /**
     * Uloženie nového trénera do úložiska.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trainer = Trainer::find($id);
        if ($trainer) {
            $trainer->load('profilePhotos'); // Eagerly načítajte fotografie, ak je to potrebné
        }

        return view('trainers.edit', compact('trainer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Validačné pravidlá pre používateľa
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required',
            'password' => 'required|min:8',
            'receive_notifications' => 'boolean',

            // Validačné pravidlá pre trénera
            'specialization' => 'required',
            'description' => 'required',
            'experience' => 'required',
            'session_price' => 'required',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if ($request->hasFile('profile_photo')) {
            $fileName = time() . '_' . $request->file('profile_photo')->getClientOriginalName();
            $request->file('profile_photo')->storeAs('profile_photos', $fileName, 'public');
        } else {
            $fileName = null;
        }

        // Vytvorenie nového používateľa
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'password' => Hash::make($request->input('password')),
            'receive_notifications' => $request->has('receive_notifications') ? 1 : 0,
            'role' => 2, // 2 je rola pre trénerov
        ]);

        // Vytvorenie nového trénera
        $trainer = Trainer::create([
            'user_id' => $user->id,
            'specialization' => $request->input('specialization'),
            'description' => $request->input('description'),
            'experience' => $request->input('experience'),
            'session_price' => $request->input('session_price'),
            'profile_photo' => $fileName,
        ]);

        return redirect()->route('trainers.create')->with('success', 'Trainer created successfully');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'specialization' => 'required',
            'description' => 'required',
            'experience' => 'required',
            'session_price' => 'required'
        ];

        // Skontrolujte, či sa email zmenil
        $originalEmail = Trainer::find($id)->user->email;

        if ($request->has('email') && $request->input('email') !== $originalEmail) {
            $rules['email'] = 'required|email|unique:users,email,' . $id;
        }

        try {
            // Nájdite trénera s priradeným používateľom
            $trainer = Trainer::with('user')->findOrFail($id);

            // Aktualizujte informácie o používateľovi
            $trainer->user->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
            ]);

            // Aktualizujte informácie o trénerovi
            $trainer->update([
                'specialization' => $request->input('specialization'),
                'description' => $request->input('description'),
                'experience' => $request->input('experience'),
                'session_price' => $request->input('session_price'),
            ]);

            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('public/profile_photos');
                $trainer->update(['profile_photo' => $photoPath]);
            }

            if ($request->hasFile('gallery_photos')) {
                $photos = $request->file('gallery_photos');
                $storagePath = 'public/trainer_gallery_photos';

                foreach ($photos as $photo) {
                    // Generovanie unikátneho názvu súboru pre každú fotografiu
                    $filename = time() . '_' . $photo->getClientOriginalName();

                    // Uloženie fotografie lokálne
                    $photo->storeAs($storagePath, $filename);

                    // Vytvorenie záznamu v databáze pre fotografiu
                    $trainer->profilePhotos()->create([
                        'trainer_id' => $trainer->user_id,
                        'filename' => $filename,
                        'path' => $storagePath,
                    ]);
                }
            }

            return redirect()->route('trainers.index')->with('success', 'Trainer updated successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            // Debugging: Výpis chybovej správy
            dd($e->getMessage());

            return redirect()->route('trainers.index')->with('error', 'Failed to update trainer');
        }
    }

    public function destroy($id)
    {
        try {
            $trainer = Trainer::findOrFail($id);

            // Predpokladá sa, že chcete zmazať aj priradeného používateľa
            $trainer->user->delete();

            return redirect()->route('trainers.index')->with('success', 'Trainer deleted successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('trainers.index')->with('error', 'Failed to delete trainer');
        }
    }

    // Ostatné metódy na aktualizáciu, mazanie, zobrazovanie trénerov atď.
}
