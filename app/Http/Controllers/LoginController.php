<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        // Attempt login with provided credentials
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentication successful
            if(Auth::user()->status == 0){
                return back()->withErrors(['error' => 'Account Inactive. Please Contact Admin'])->withInput();
            }
            $user = Auth::user();
            $role = $user->getRoleNames()->first();

            // Log the login details
            LoginLog::create([
                'user_id' => $user->id,
                'login_time' => now(),
                'user_type' => $role,
                'ip_address' => $request->ip(),
            ]);

            return redirect()->intended('data-list')->with('success', 'Login successful');

        } else {
            // Authentication failed
            return back()->withErrors(['password' => 'Invalid credentials'])->withInput();
        }
    }


    public function logout()
    {
        Auth::logout(); // Log the user out
        return redirect()->route('login'); // Redirect to login page after logout
    }
}
