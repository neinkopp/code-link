<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Profile;

class HomepageController extends Controller
{
	public function show()
	{
		$autenticated = Auth::check();
		if ($autenticated) {
			$user = Auth::user();

			if ($user->profile) {
				return redirect()->route('swipe');
			} else {
				return redirect()->route('create-profile');
			}
		}
		return view('welcome');
	}
}
