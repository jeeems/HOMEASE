<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Dynamically redirect users after login based on their role.
     */
    protected function redirectTo()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return route('admin.dashboard');  // Redirects admins to their dashboard
            }
            if (Auth::user()->role === 'worker') {
                return route('worker.home');  // Redirect workers
            }
        }
        return '/home'; // Default redirect
    }


    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Handle login request with validation.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:client,worker,admin'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Your email seems not registered. Do you want to register?']);
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors(['password' => 'The provided password is incorrect.']);
        }

        if ($user->role !== $request->role) {
            return back()->withErrors(['role' => "It seems that your account is registered as a " . ucfirst($user->role) . "."]);
        }

        return redirect()->intended($this->redirectTo());
    }


    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'worker'
                ? redirect()->route('worker.home')
                : redirect('/home');
        }
        return view('auth.login');
    }


    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
