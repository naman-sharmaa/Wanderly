<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * AuthController
 *
 * Handles: Register, Login, Logout
 */
class AuthController extends Controller
{
    // ----------------------------------------------------------------
    // Register
    // ----------------------------------------------------------------

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle registration form submission.
     */
    public function register(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:mongodb.users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required'      => 'Your name is required.',
            'email.unique'       => 'This email is already registered. Please login.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min'       => 'Password must be at least 8 characters.',
        ]);

        // Create user with hashed password
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Log the user in
        Auth::login($user);

        // Flash success message
        session()->flash('toast_success', 'Welcome aboard! Your journey begins now. ✈️');

        return redirect()->route('dashboard');
    }

    // ----------------------------------------------------------------
    // Login
    // ----------------------------------------------------------------

    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login form submission.
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // Attempt login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            session()->flash('toast_success', 'Welcome back! Ready to plan your next adventure? 🌍');

            return redirect()->intended(route('dashboard'));
        }

        // Login failed
        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
    }

    // ----------------------------------------------------------------
    // Firebase Google Sign-In
    // ----------------------------------------------------------------

    /**
     * Handle Firebase Google token login.
     */
    public function firebaseGoogle(Request $request, FirebaseAuthService $firebaseAuth)
    {
        $validated = $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        // Log token audience/issuer for diagnostics (do not log full token)
        try {
            [$header, $payload, $sig] = explode('.', $validated['id_token']) + [null, null, null];
            $payload = $payload ? json_decode(base64_decode(strtr($payload, '-_', '+/')), true) : [];
            
            \Log::debug('Google sign-in token payload (aud, iss):', [
                'aud' => $payload['aud'] ?? null,
                'iss' => $payload['iss'] ?? null,
            ]);
        } catch (\Throwable $_e) {
            \Log::debug('Could not decode id_token payload for diagnostics.');
        }

        try {
            $claims = $firebaseAuth->verifyIdToken($validated['id_token']);
        } catch (\Throwable $throwable) {
            return response()->json([
                'message' => $throwable->getMessage() ?: 'Unable to verify Google sign-in.',
            ], 422);
        }

        $email = $claims['email'] ?? null;
        $firebaseUid = $claims['sub'] ?? null;

        if (! $email || ! $firebaseUid) {
            return response()->json([
                'message' => 'Google account details are incomplete.',
            ], 422);
        }

        $user = User::query()
            ->where('firebase_uid', $firebaseUid)
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $user = new User();
            $user->email = $email;
        }

        $user->name = $claims['name'] ?? $user->name ?? Str::before($email, '@');
        $user->avatar = $claims['picture'] ?? $user->avatar;
        $user->firebase_uid = $firebaseUid;
        $user->auth_provider = 'google';
        $user->email_verified_at = ! empty($claims['email_verified']) ? now() : $user->email_verified_at;

        if (empty($user->password)) {
            $user->password = Hash::make(Str::random(48));
        }

        $user->save();

        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Signed in with Google.',
            'redirect_url' => route('dashboard'),
        ]);
    }

    // ----------------------------------------------------------------
    // Logout
    // ----------------------------------------------------------------

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('toast_info', 'You have been logged out safely. Safe travels! 👋');
    }
}
