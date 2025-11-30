<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';  // Changed from RouteServiceProvider::HOME to '/dashboard'

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // UI-only mode: Try normal authentication first
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Redirect based on user role
            if ($user->isDelivery()) {
                return redirect()->route('deliveries.index');
            } elseif ($user->isHelper()) {
                return redirect()->route('orders.create');
            }

            return redirect()->intended($this->redirectTo);
        }

        // For UI-only demo: Try to find or create a demo user
        // This allows viewing the UI without real authentication
        try {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $credentials['email']],
                [
                    'name' => explode('@', $credentials['email'])[0],
                    'password' => bcrypt($credentials['password']),
                    'role' => 'owner', // Default to owner for demo
                ]
            );
            
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            
            return redirect()->intended($this->redirectTo);
        } catch (\Exception $e) {
            // If database operations fail, show error
            return back()->withErrors([
                'email' => 'Unable to create demo user. Please ensure database is set up or use existing credentials.',
            ])->onlyInput('email');
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}