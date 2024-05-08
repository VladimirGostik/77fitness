<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Make sure to import the User model
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
     * Show the form for creating a new trainer.
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
        $reservations = Reservation::all();
        $groupReservations = GroupReservation::withCount('participants')->get();
        $clients = Client::all();
        return view('trainers.profile', compact('trainer', 'reservations', 'clients','groupReservations'));
    }

    public function create(){
        return view('trainers.create'); // You can create this view as needed
    }

    /**
     * Store a newly created trainer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function edit($id)
     {
        $trainer = Trainer::find($id);
        if ($trainer) {
            $trainer->load('profilePhotos');  // Eagerly load photos if needed
        }

        // ... rest of your logic ...

        return view('trainers.edit', compact('trainer'));
        }

        public function store(Request $request)
        {
            $request->validate([
                // User validation rules
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required',
                'password' => 'required|min:8',
                'receive_notifications' => 'boolean',
    
                // Trainer validation rules
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
    
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'password' => Hash::make($request->input('password')),
                'receive_notifications' => $request->has('receive_notifications') ? 1 : 0,
                'role' => 2, // Assuming 2 is the role for trainers
            ]);
    
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
            //dd($request->all());
            $rules = [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone_number' => 'required',
                'specialization' => 'required',
                'description' => 'required',
                'experience' => 'required',
                'session_price' => 'required'
            ];
    
            // Check if the email has changed
            $originalEmail = Trainer::find($id)->user->email;
    
            if ($request->has('email') && $request->input('email') !== $originalEmail) {
                $rules['email'] = 'required|email|unique:users,email,' . $id;
            }

            try {
                // Find the trainer with the associated user
                $trainer = Trainer::with('user')->findOrFail($id);
        
                // Update the user information
                $trainer->user->update([
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'email' => $request->input('email'),
                    'phone_number' => $request->input('phone_number'),
                ]);
        
                // Update the trainer information
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
                        // Generate a unique filename for each photo
                        $filename = time() . '_' . $photo->getClientOriginalName();
                
                        // Store the photo locally
                        $photo->storeAs($storagePath, $filename);
                
                        // Create database record for the photo
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
        
                // Debugging: Output the exception message
                dd($e->getMessage());
        
                return redirect()->route('trainers.index')->with('error', 'Failed to update trainer');
            }
        }
        
        public function destroy($id)
    {
        try {
            $trainer = Trainer::findOrFail($id);

            // Assuming you want to delete associated user as well
            $trainer->user->delete();

            return redirect()->route('trainers.index')->with('success', 'Trainer deleted successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('trainers.index')->with('error', 'Failed to delete trainer');
        }
    }

    // Other methods for updating, deleting, showing trainers, etc.
    

    }