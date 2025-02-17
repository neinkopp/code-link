<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GithubController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function redirectToGithub()
	{
		return Socialite::driver('github')->redirect();
	}

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function handleGithubCallback()
	{
		try {
			$user = Socialite::driver('github')->user();

			$findUser = User::where('github_id', $user->id)->first();


			if ($findUser) {

				Auth::login($findUser);

				return redirect()->intended('swipe');
			} else {
				$newUser = User::updateOrCreate(['github_id' => $user->id], [
					'name' => $user->nickname,
					'github_token' => $user->token,
					'github_refresh_token' => $user->refreshToken,
				]);

				Auth::login($newUser);

				return redirect()->intended('create-profile');
			}
		} catch (Exception $e) {
			dd($e);
		}
	}
}
