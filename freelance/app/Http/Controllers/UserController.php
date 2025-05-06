<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('users.create');
    }

    // Store a newly created resource in storage
    
    // Display the specified resource
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Show the form for editing the specified resource
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:client,freelancer,admin',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'email.unique' => 'This email is already taken.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);
    
        $photoPath = $user->photo; // Keep existing photo path by default
    
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
    
            logger('Photo upload details:', [
                'original_name' => $photo->getClientOriginalName(),
                'mime_type' => $photo->getMimeType(),
                'size' => $photo->getSize(),
            ]);
    
            $photoPath = $photo->store('photos', 'public');
        }
    
        $user->update([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'] ? Hash::make($validatedData['password']) : $user->password,
            'role' => $validatedData['role'],
            'photo' => $photoPath,
        ]);
    
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    

    // Remove the specified resource from storage
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}