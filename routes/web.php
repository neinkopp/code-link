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

Route::middleware('auth')->group(function () {
	// Nur eingeloggte Nutzer dürfen swipen oder Matches sehen
	// Route to show the swipe form
	Route::get('/swipe/{to_user_id}', [SwipeController::class, 'showSwipeForm'])->name('swipe.form');
	Route::get('/swipe', [SwipeController::class, 'show'])->name('swipe');
	Route::post('/swipe', [SwipeController::class, 'swipe'])->name('swipe.handle');
	Route::get('/matches', [SwipeController::class, 'getMatches'])->name('matches');
});


require __DIR__ . '/auth.php';
