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
    Route::post('/swipe', [SwipeController::class, 'swipe'])->name('swipe');
    Route::get('/matches', [SwipeController::class, 'getMatches'])->name('matches');
});


require __DIR__ . '/auth.php';
