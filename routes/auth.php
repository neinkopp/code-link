<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GithubController;

Route::controller(GithubController::class)->group(function () {
	Route::get('auth/redirect', 'redirectToGithub')->name('auth.github');
	Route::get('auth/callback', 'handleGithubCallback');
});
