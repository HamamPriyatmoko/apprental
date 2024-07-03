<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Validation\ValidationException; // Tambahkan ini
use Laravel\Sanctum\HasApiTokens; 

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Buat entitas User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Buat entitas Profile yang terkait
        // $user->profile()->create([
        //     'username' => $user->name,
        //     // Tambahkan atribut lain dari profil jika ada
        // ]);

        return response()->json(['message' => 'User successfully registered', 'user' => $user], 201);
    }

    // public function login(Request $request) ini yang benar
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|string|email',
    //             'password' => 'required|string',
    //         ]);

    //         if (!Auth::attempt($request->only('email', 'password'))) {
    //             return response()->json(['error' => 'Kredensial yang diberikan salah.'], 401);
    //         }

    //         $user = Auth::user();
    //         $token = $user->createToken('auth-token')->plainTextToken;

    //         return response()->json(['token' => $token, 'name' => $user->name], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
    //     }

    //     $user = Auth::user();
    //     $token = $user->createToken('auth-token')->plainTextToken;

    //     return response()->json(['token' => $token], 200);
    // }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $profilePicture = $user->profile ? $user->profile->profile_picture : null;
            $profilePictureUrl = $profilePicture ? asset('storage/profile_pictures/' . $profilePicture) : null;

            return response()->json([
                'token' => $user->createToken('authToken')->accessToken,
                'name' => $user->name,
                'profile_picture' => $profilePictureUrl,
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout'], 200);
    }

    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
}
