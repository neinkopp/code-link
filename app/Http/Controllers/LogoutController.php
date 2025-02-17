<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
	public function logout(Request $request) //aktuele http anfrage 
	{
		Auth::logout();

		$request->session()->invalidate(); //aktuelle Sitzung löschen 
		$request->session()->regenerateToken(); //regenerate ein neues token damit das alte nicht mehr verwendet werden kann :( 

		return redirect()->route('home');
	}
}
