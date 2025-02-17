<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
	public function showDemo(Request $request)
	{
		// get userId from request parameters
		$userId = $request->route("userId");
		$disk = Storage::disk("supabase");
		if ($disk->exists($userId . ".png")) {
			// get raw file
			$raw_file = $disk->get($userId . ".png");
			// return raw file
			return response($raw_file, 200)->header("Content-Type", "image/png");
		} else {
			// return default image
			$default_image = $disk->get("default.png");
			return response($default_image, 200)->header("Content-Type", "image/png");
		}
	}
}
