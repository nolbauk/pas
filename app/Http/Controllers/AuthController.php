<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login view
     */
    public function showLogin()
    {
        return view('login.login');
    }

    /**
     * Handle the user login request
     */
    public function login(Request $request)
    {
        // Validate user input credentials
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Attempt to log in the user with the provided credentials
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Return back with error if credentials do not match
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Show the registration view
     */
    public function showRegister()
    {
        return view('register.register');
    }

    /**
     * Handle the user registration request
     */
    public function register(Request $request)
    {
        // Validate the incoming registration data
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user in the database
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password for security
            'role' => 'user', // Default role for new registrations
        ]);

        // Automatically log in the newly registered user
        Auth::login($user);

        return redirect('/dashboard');
    }

    /**
     * Handle the user logout request
     */
    public function logout(Request $request)
    {
        // Log the user out of the application
        Auth::logout();
        
        // Invalidate the current session and regenerate token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
