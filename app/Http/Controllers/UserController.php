<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
    
        // If there is a search query, filter the users
        if ($query) {
            $users = User::where('first_name', 'LIKE', "%$query%")
                ->orWhere('last_name', 'LIKE', "%$query%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->get();
        } else {
            // If no search query, get all users
            $users = User::all();
        }
        return view('users.index', compact('users', 'query'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Add validation rules for password fields
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone_number' => 'required',
            'receive_notifications' => 'boolean',
        ]);

 

        // Update other user attributes
        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'receive_notifications' => (bool) $request->input('receive_notifications'),
        ]);

        return redirect()->back()->with('success', 'Users info changed successfully!');
    }

    public function changePassword(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'current_password' => 'required|password:current_user',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->back()->with('success', 'Password changed successfully!');
}

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
