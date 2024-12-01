<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Retrieve all users
    public function index()
    {
        Log::info('Fetching all users.');

        $users = User::all();
        Log::info('Users retrieved successfully.', ['count' => $users->count()]);

        return response()->json($users, 200);
    }

    // Create a new user
    public function store(Request $request)
    {
        Log::info('Attempting to create a new user.', ['data' => $request->all()]);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
        ]);

        Log::info('User created successfully.', ['user_id' => $user->id]);

        return response()->json($user, 201);
    }

    // Retrieve a specific user
    public function show(User $user)
    {
        Log::info('Fetching user details.', ['user_id' => $user->id]);

        return response()->json($user, 200);
    }

    // Update a specific user
    public function update(Request $request, User $user)
    {
        Log::info('Attempting to update user.', ['user_id' => $user->id, 'data' => $request->all()]);

        $validatedData = $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'username' => 'string|max:255|unique:users,username,' . $user->id,
            'email' => 'string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        Log::info('User updated successfully.', ['user_id' => $user->id]);

        return response()->json($user, 200);
    }

    // Delete a specific user
    public function destroy(User $user)
    {
        Log::info('Attempting to delete user.', ['user_id' => $user->id]);

        $user->delete();

        Log::info('User deleted successfully.', ['user_id' => $user->id]);

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    // User login
    public function login(Request $request)
    {
        Log::info('Login attempt.', ['username' => $request->username]);

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('Login failed for user.', ['username' => $request->username]);

            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        Log::info('Login successful.', ['user_id' => $user->id]);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
