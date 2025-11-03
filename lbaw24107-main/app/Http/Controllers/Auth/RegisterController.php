<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:30|unique:account',
            'email' => 'required|email|max:250|unique:account',
            'password' => 'required|min:8|max:25|confirmed'
        ]);
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'biography' => 'placeholder',
            'phone_number' => '969696969'
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        session(['user_id' => Auth::id()]);
        session()->save(); // Explicitly save the session
        return redirect()->route('dashboard')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
