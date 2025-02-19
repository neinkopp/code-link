<?php

use App\Http\Controllers\SwipeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PictureController;
use App\Http\Middleware\EnsureProfileIsCreated;

// homepage
Route::get('/', [HomepageController::class, 'show'])->name('home');

// login route to redirect to /auth/redirect
Route::get('login', function () {
	// redirect to /auth/redirect
	return redirect()->route('auth.github');
})->name('login');
// logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');




Route::middleware('auth')->group(function () {
	Route::middleware(EnsureProfileIsCreated::class)->group(function () {
		// Nur eingeloggte Nutzer dürfen swipen oder Matches sehen
		// Route to show the swipe form
		Route::get('/swipe', [SwipeController::class, 'show'])->name('swipe');
		Route::post('/swipe', [SwipeController::class, 'swipe'])->name('swipe.handle');

		// matches
		Route::get('/matches', [SwipeController::class, 'getMatches'])->name('matches');

		// code picture
		Route::get('/showcase-code-picture/{userId}', [PictureController::class, 'showDemo'])->name('picture');

		// profiles
		Route::get('/profiles/{userId}', [ProfileController::class, 'show'])->name('profile.show');

		Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('profile.edit');
		Route::post('/edit-profile', [ProfileController::class, 'update'])->name('profile.update');

		Route::get('/get-matches', [SwipeController::class, 'getMatches'])->name('matches.getMatches');
	});

	Route::get('/create-profile', [ProfileController::class, 'showCreation'])->name('create-profile');
	Route::post('/create-profile', [ProfileController::class, 'create'])->name('create-profile.handle');
});



require __DIR__ . '/auth.php';
