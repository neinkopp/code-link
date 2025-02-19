<!DOCTYPE html>
<html lang="en">

<head>
	<title>Edit Profile</title>
	<link rel="stylesheet" href="{{ asset('css/edit-profile.css') }}">
</head>

<body>
	<div class=h1-container>
		<h1>Edit Profile</h1>
	</div>
	<form action="{{ route('profile.update') }}" method="POST">
		@csrf
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" value="{{ old('name', $profile->name) }}" required><br><br>

		<label for="dob">Date of Birth:</label>
		<input type="date" id="dob" name="dob" value="{{ old('dob', $profile->dob ? $profile->dob->format('Y-m-d') : '') }}" required>

		<label for="occupation">Job:</label>
		<input type="text" id="occupation" name="occupation" value="{{ old('occupation', $profile->occupation) }}" required><br><br>

		<label for="programming_langs">Languages:</label>
		<textarea id="programming_langs" name="programming_langs" required>{{ old('programming_langs', is_array(json_decode($profile->programming_langs, true)) ? implode(', ', json_decode($profile->programming_langs, true)) : '') }}</textarea>


		<label for="social_media">Social Media:</label>
		<input type="text" id="social_media" name="social_media" value="{{ old('social_media', $profile->social_media) }}"><br><br>

		<label for="showcase_code">Code Snippet:</label>
		<textarea id="showcase_code" name="showcase_code" rows="4" cols="50" required>{{ old('showcase_code', $profile->showcase_code) }}</textarea>


		<button type="submit">Save</button>
	</form>
	<a href="{{ url('profiles/' . Auth::id()) }}">Show your profile</a>
</body>

</html>