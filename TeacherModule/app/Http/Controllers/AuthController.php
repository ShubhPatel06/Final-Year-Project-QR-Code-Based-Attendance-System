<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role_id == 2) {
            return redirect()->route('teacher.dashboard');
        }

        // Default redirect for other roles or if the role is not set
        return redirect('/home');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phoneNo' => ['required', 'string', 'max:255'],
            'password' => ['required', Password::defaults()],
            'role_id' => ['required', 'integer', 'in:1,2'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'phoneNo' => $request->phoneNo,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,

        ]);

        $user->save();

        return back()->with('success', 'Registered Successfully');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();

        //     return redirect()->intended('dashboard');
        // }

        if (Auth::attempt($credentials)) {
            // Authentication successful
            $user = Auth::user();
            if ($user->role_id == 1) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            } elseif ($user->role_id == 2) {
                $request->session()->regenerate();
                return redirect()->route('teacher.dashboard');
            } else {
                // Handle other roles or scenarios here
            }
        }

        // If login fails, return to the login form with an error message
        return back()->with('error', 'Login failed. Please check your credentials.');

        //return redirect()->back()->with('error', 'Invalid login credentials'); // Handle authentication failure

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
