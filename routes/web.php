<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ItineraryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Travel Planner AI
|--------------------------------------------------------------------------
*/

// ----------------------------------------------------------------
// Public Routes
// ----------------------------------------------------------------

// Ignore automatic browser favicon requests if no icon file is present.
Route::get('/favicon.ico', fn () => response('', 204));

// Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/auth/firebase/google', [AuthController::class, 'firebaseGoogle'])
        ->name('auth.firebase.google');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ----------------------------------------------------------------
// Authenticated Routes
// ----------------------------------------------------------------

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ----------------------------------------------------------------
    // Trips — Full CRUD
    // ----------------------------------------------------------------
    Route::resource('trips', TripController::class);

    // ----------------------------------------------------------------
    // Destinations — Nested under trips
    // ----------------------------------------------------------------
    Route::prefix('trips/{trip}/destinations')->name('trips.destinations.')->group(function () {
        Route::get('/create', [DestinationController::class, 'create'])->name('create');
        Route::post('/', [DestinationController::class, 'store'])->name('store');
        Route::get('/{destination}/edit', [DestinationController::class, 'edit'])->name('edit');
        Route::put('/{destination}', [DestinationController::class, 'update'])->name('update');
        Route::delete('/{destination}', [DestinationController::class, 'destroy'])->name('destroy');
    });

    // ----------------------------------------------------------------
    // Accommodations — Nested under trips
    // ----------------------------------------------------------------
    Route::prefix('trips/{trip}/accommodations')->name('trips.accommodations.')->group(function () {
        Route::get('/create', [AccommodationController::class, 'create'])->name('create');
        Route::post('/', [AccommodationController::class, 'store'])->name('store');
        Route::get('/{accommodation}/edit', [AccommodationController::class, 'edit'])->name('edit');
        Route::put('/{accommodation}', [AccommodationController::class, 'update'])->name('update');
        Route::delete('/{accommodation}', [AccommodationController::class, 'destroy'])->name('destroy');
    });

    // ----------------------------------------------------------------
    // Activities — Nested under trips
    // ----------------------------------------------------------------
    Route::prefix('trips/{trip}/activities')->name('trips.activities.')->group(function () {
        Route::get('/create', [ActivityController::class, 'create'])->name('create');
        Route::post('/', [ActivityController::class, 'store'])->name('store');
        Route::get('/{activity}/edit', [ActivityController::class, 'edit'])->name('edit');
        Route::put('/{activity}', [ActivityController::class, 'update'])->name('update');
        Route::delete('/{activity}', [ActivityController::class, 'destroy'])->name('destroy');
    });

    // ----------------------------------------------------------------
    // Itinerary — Day-wise view
    // ----------------------------------------------------------------
    Route::get('/trips/{trip}/itinerary', [ItineraryController::class, 'show'])
        ->name('trips.itinerary');

});
