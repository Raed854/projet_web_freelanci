<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:patient,docteur,admin',
        ], [
            'nom.required' => 'The nom field is required.',
            'prenom.required' => 'The prenom field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role.in' => 'The role must be either patient or doctor.',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoDetails = [
                'original_name' => $photo->getClientOriginalName(),
                'mime_type' => $photo->getMimeType(),
                'size' => $photo->getSize(),
            ];

            logger('Photo upload details:', $photoDetails);
        }
        $photoPath = $request->file('photo') ? $request->file('photo')->store('photos', 'public') : null;

        User::create([
            'nom' => $validatedData['nom'],
            'prenom' => $validatedData['prenom'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'photo' => $photoPath,
        ]);

        return redirect('/');
    }

public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // First check if user exists and is active
    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !$user->active) {
        return back()->withErrors([
            'email' => 'Your account is deactivated. Please contact support.',
        ])->onlyInput('email');
    }

    // Proceed with authentication if user is active
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        session(['user_id' => $user->id]);

        if ($user->role != 'admin') {
            return redirect()->intended('/loggedin')->with('success', 'You are logged in!');
        } else {
            return redirect()->intended('/users')->with('success', 'You are logged in!');
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out!');
    }
    public function create()
    {
        return view('users.create');
    }
}