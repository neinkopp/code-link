<?php

namespace App\Http\Controllers;

use App\Models\Swipe; //importiert das Swipe-Model, um Swipes in der Datenbank zu speichern.
use Illuminate\Support\Facades\Auth; //Verwaltet die Authentifizierung und gibt den eingeloggten Nutzer zurück.
use Illuminate\Http\Request; //ermöglicht den Zugriff auf Daten, die von einem HTTP-Request gesendet wurden.
use Illuminate\Support\Facades\DB; //importiert die DB Facade um direte SQL Queries zu schreiben 
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class SwipeController extends Controller
{
	// Liste der Nutzer anzeigen, die geswiped werden können
	public function show()
	{
		$userId = Auth::id();

		$usersToSwipeOn = User::where('users.id', '!=', $userId)
			->whereNotIn('users.id', Swipe::where('from_user_id', $userId)->pluck('to_user_id'))
			->leftJoin('profiles', 'users.id', '=', 'profiles.user_id') // Join profiles
			->select('users.id', 'users.name as username', 'profiles.name as profile_name', 'profiles.programming_langs') // Alias for name columns
			->inRandomOrder()
			->get();

		return view('swipe', compact('usersToSwipeOn'));
	}



	public function swipe(Request $request)
	{ //request als Parameter entgegen nehmen
		$user = Auth::user(); //eingeloggten Nutzer zuruckgeben 
		$targetUserId = $request->input('to_user_id'); //id des Nutzers den der aktuelle User Swiped 
		$liked = $request->input('liked'); //gibt an ob es ein right oder left swipe ist

		$existingSwipe = Swipe::where('from_user_id', $user->id) //überprüft ob der Nutzer die andere Person schon geswiped hat
			->where('to_user_id', $targetUserId)
			->first(); //falls ja, wird der Swipe nicht doppelt gespeichert

		if ($existingSwipe) {
			return redirect()->back()->with('error', 'Swipe already exists.');
		}

		Swipe::create([ // neuen eintrag in der swipes tabelle mit... 
			'from_user_id' => $user->id,
			'to_user_id' => $targetUserId,
			'liked' => $liked,

		]);

		return redirect()->back()->with('success', 'Swipe successful.');
	}

	public function getMatches()
	{
		$userId = Auth::id();

		$matches = DB::table('swipes as s1')
			->join('swipes as s2', function ($join) {
				$join->on('s1.from_user_id', '=', 's2.to_user_id')
					->on('s1.to_user_id', '=', 's2.from_user_id');
			})
			->join('users as matched_user', function ($join) use ($userId) {
				$join->on('matched_user.id', '=', DB::raw("
					CASE 
						WHEN s1.from_user_id = $userId THEN s1.to_user_id 
						ELSE s1.from_user_id 
					END
				"));
			})
			->where('s1.liked', true)
			->where('s2.liked', true)
			->where(function ($query) use ($userId) {
				$query->where('s1.from_user_id', $userId)
					->orWhere('s1.to_user_id', $userId);
			})
			->select('matched_user.*')
			->distinct()
			->get();

		$newMatches = $matches->filter(function ($match) {
			return !session()->has("match_shown_{$match->id}");
		});

		if ($newMatches->isNotEmpty()) {
			session()->flash('match', 'You have a new match!');

			foreach ($newMatches as $match) {
				session()->put("match_shown_{$match->id}", true);
			}
		}

		if (request()->ajax()) {
			return response()->json([
				'matches' => $matches,
				'message' => $newMatches->isNotEmpty() ? 'You have a new match!' : null
			]);
		}

		return view('matches', compact('matches'));
	}



	public function getUsersToSwipeOn()
	{
		$users = DB::table('users')
			->join('profiles', 'users.id', '=', 'profiles.user_id')
			->select(
				'users.id',
				'users.name',
				'profiles.occupation',
				'profiles.programming_langs',
				'profiles.name'
			)
			->get();

		return view('users', compact('users'));
	}
}
