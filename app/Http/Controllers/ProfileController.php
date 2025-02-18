<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Jobs\ProcessImageGeneration;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
	public function show($userId)
	{
		$profile = Profile::where('user_id', $userId)->firstOrFail();

		return view('show-profile', compact('profile'));
	}

	public function showCreation()
	{
		return view('create-profile');
	}

	public function create(Request $request)
	{
		$userId = Auth::id();

		// validate the request
		$request->validate([
			'name' => 'required',
			'dob' => 'required',
			'gender' => 'required',
			'interested_in' => 'required',
			'occupation' => 'required',
			'programming_langs' => 'required',
			'social_media' => 'required',
			'showcase_code' => 'required',
		]);

		$existingProfile = Profile::where('user_id', $userId)->first();

		if ($existingProfile) {
			redirect()->route('swipe');
		} else {
			// comma-separate list to JSON array
			$programmingLangsJSON = json_encode(explode(',', $request->input('programming_langs')));
			$profile = Profile::create([
				'name' => $request->input('name'),
				'dob' => $request->input('dob'),
				'gender' => $request->input("gender"),
				'interested_in' => json_encode($request->input('interested_in')),
				'programming_langs' => $programmingLangsJSON,
				'occupation' => $request->input('occupation'),
				'showcase_code' => $request->input('showcase_code'),
				'social_media' => $request->input('social_media'),
				'user_id' => $userId,
			]);

			// Dispatch the image generation job
			ProcessImageGeneration::dispatch(
				$request->input('showcase_code'),
				$userId
			);
		}

		return redirect()->route('swipe');
	}



	public function edit()
	{
		$profile = Auth::user()->profile;
		return view('edit-profile', compact('profile'));
	}
	public function update(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'dob' => 'required|date',
			'occupation' => 'required|string',
			'programming_langs' => 'required',
			'social_media' => 'nullable',
			'showcase_code' => 'nullable|string',
		]);
		$profile = Auth::user()->profile;
		$explodedProgrammingLangsArray = explode(',', $request->programming_langs);
		$codeChanged = $profile->showcase_code !== $request->showcase_code;

		$profile->update([
			'name' => $request->name,
			'occupation' => $request->occupation,
			'programming_langs' => json_encode($explodedProgrammingLangsArray),
			'social_media' => $request->social_media,
			'showcase_code' => $request->showcase_code,

		]);

		if ($codeChanged) {
			ProcessImageGeneration::dispatch(
				$request->showcase_code,
				Auth::id()
			);
		}

		return redirect()->route('profile.show', Auth::user()->id)->with('success', 'Profil updated');
	}
}
