<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return view('welcome');
});

Route::get('/check-session', function () {
	session(['test' => 'Session is working!']);
	return session('test');
});

require __DIR__ . '/auth.php';
