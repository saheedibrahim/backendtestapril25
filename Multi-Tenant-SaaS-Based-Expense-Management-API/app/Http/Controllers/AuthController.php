<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(){

        $authUser = Auth::user();

        $users = User::where('company_id', $authUser->company_id)->get();
        $data = [
            'users' => $users
        ];

        return response()->json($data, 200);
    }

    public function register(Request $request){

        $authUser = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string',
            'role' => 'required|in:Admin,Manager,Employee',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_id' => Auth::user()->company_id,
            'role' => $validated['role']
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->only(['id', 'name', 'email', 'role'])
        ], 201);
    }
        
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function update(Request $request, $id){
        $authUser = Auth::user();
    
        $user = User::findOrFail($id);

        if ($authUser->company_id !== $user->company_id) {
            $data = [
                'message' => "Unauthorized Access"
            ];

            return response()->json($data, 403);
        }

        $validated = $request->validate([
            'role' => 'required|in:Admin,Manager,Employee'
        ]);

        $user->update([
            'role' => $validated['role']
        ]);
    
        return response()->json([
            'message' => 'User role updated successfully.'
        ], 200);

    }

    public function delete($id){
        $authUser = Auth::user();
    
        $user = User::findOrFail($id);
    
        // Prevent Admins from deleting users from another company
        if ($user->company_id !== $authUser->company_id) {
            return response()->json(['message' => 'You cannot delete a user from another company.'], 403);
        }
    
        $user->delete();
    
        return response()->json([
            'message' => 'User deleted successfully.'
        ], 200);

    }
}
