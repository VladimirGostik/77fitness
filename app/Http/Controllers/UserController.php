<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{
    // Metóda na zobrazenie formulára pre vytvorenie používateľa
    public function create()
    {
        \Log::info('UserController@create called');

        return view('users.create');
    }

    // Metóda na uloženie nového používateľa
    public function store(Request $request)
    {
        // Validácia vstupných údajov
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'phone_number' => 'required',
            'receive_notifications' => 'boolean',
        ]);

        // Vytvorenie používateľa
        User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phone_number' => $request->input('phone_number'),
            'receive_notifications' => (bool) $request->input('receive_notifications'),
            'role' => 1, // vždy nastaviť rolu na 1
        ]);

        return redirect()->route('users.index')->with('success', 'Používateľ bol úspešne vytvorený!');
    }

    // Metóda na zobrazenie zoznamu používateľov
    public function index(Request $request)
    {
        $query = $request->input('query');
    
        // Ak existuje vyhľadávací dotaz, filtrovať používateľov
        if ($query) {
            $users = User::where('first_name', 'LIKE', "%$query%")
                ->orWhere('last_name', 'LIKE', "%$query%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->get();
        } else {
            // Ak neexistuje vyhľadávací dotaz, získať všetkých používateľov
            $users = User::all();
        }
        return view('users.index', compact('users', 'query'));
    }

    // Metóda na zobrazenie formulára pre úpravu používateľa
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Metóda na aktualizáciu údajov používateľa
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Pridanie validačných pravidiel pre polia hesla
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone_number' => 'required',
            'receive_notifications' => 'boolean',
        ]);

        // Aktualizácia ostatných atribútov používateľa
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'receive_notifications' => (bool) $request->input('receive_notifications'),
        ]);

        return redirect()->back()->with('success', 'Údaje používateľa boli úspešne zmenené!');
    }

    // Metóda na zmenu hesla používateľa
    public function changePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'current_password' => 'required|password:current_user',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Heslo bolo úspešne zmenené!');
    }

    // Metóda na zmazanie používateľa
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Používateľ bol úspešne zmazaný');
    }
}
