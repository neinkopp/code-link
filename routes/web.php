<?php

use App\Http\Controllers\SwipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return view('welcome');
});

Route::get('/check-session', function () {
	session(['test' => 'Session is working!']);
	return session('test');
});

Route::get('login', function () {
	// redirect to /auth/redirect
	return redirect()->route('auth.github');
})->name('login');

Route::middleware('auth')->group(function () {
	// Nur eingeloggte Nutzer dürfen swipen oder Matches sehen
	// Route to show the swipe form
	Route::get('/swipe/{to_user_id}', [SwipeController::class, 'showSwipeForm'])->name('swipe.form');
	Route::get('/home', [SwipeController::class, 'show'])->name('swipe');
	Route::post('/home', [SwipeController::class, 'swipe'])->name('swipe.handle');
	Route::get('/matches', [SwipeController::class, 'getMatches'])->name('matches');
	Route::get('/picture', [SwipeController::class, 'showPicture']);
});


require __DIR__ . '/auth.php';
