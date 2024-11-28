<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user, 200);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $user->id,
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8', // Password is now nullable
        ]);

        // Only hash the password if it's provided
        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        // Update the user with validated data
        $user->update($request->only(['first_name', 'last_name', 'username', 'email', 'phone', 'password']));

        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function login(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            // Attempt to find the user by username
            $user = User::where('username', $request->username)->first();

            // Check if the user exists and if the password matches
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'username' => ['The provided credentials are incorrect.'],
                ]);
            }

            // If login is successful, create a new token
            $token = $user->createToken('API Token')->plainTextToken;

            // Return the response as an array of objects
            return response()->json([
                [
                    'user' => $user,
                    'token' => $token,
                ]
            ], 200);

        } catch (\Exception $e) {
            // Log the exception and return a generic error message
            \Log::error('Login Error: ' . $e->getMessage());

            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }


}
