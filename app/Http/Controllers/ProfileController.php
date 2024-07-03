<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/profile_pictures', $imageName);

            // Delete old profile picture if exists
            if ($user->profile->profile_picture) {
                Storage::delete('public/profile_pictures/' . $user->profile->profile_picture);
            }

            // Update profile picture
            $user->profile->profile_picture = $imageName;
        }

        // Update username based on user's name
        $user->profile->username = $user->name;
        $user->profile->save();

        return response()->json(['message' => 'Profile picture and username updated successfully']);
    }

    // public function store(Request $request, $userId)
    // {
    //     $user = User::findOrFail($userId);

    //     $validatedData = $request->validate([
    //         'address' => 'required|string',
    //         'phone_number' => 'required|string',
    //         'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'gender' => 'required|in:Laki-laki,Perempuan',
    //     ]);

    //     // Handle profile picture if uploaded
    //     $profilePicture = null;
    //     if ($request->hasFile('profile_picture')) {
    //         $image = $request->file('profile_picture');
    //         $profilePicture = time() . '.' . $image->getClientOriginalExtension();
    //         $image->storeAs('public/profile_pictures', $profilePicture);
    //     }

    //     $profileData = [
    //         'user_id' => $user->id,
    //         'username' => $user->name,
    //         'address' => $validatedData['address'],
    //         'phone_number' => $validatedData['phone_number'],
    //         'profile_picture' => $profilePicture,
    //         'gender' => $validatedData['gender'],
    //     ];

    //     Profile::create($profileData);

    //     return redirect()->route('profile.show', ['userId' => $userId]);
    // }

}
