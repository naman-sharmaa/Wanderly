<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * HomeController
 *
 * Handles the public landing page.
 */
class HomeController extends Controller
{
    /**
     * Show the landing page (redirect to dashboard if authenticated).
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('home');
    }
}
