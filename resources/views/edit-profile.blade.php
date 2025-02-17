<!DOCTYPE html>
<html>

<head>
	<title>Edit Profile</title>
</head>

<body>
	<h1>Edit Profile</h1>
	<form action="{{ route('profile.update') }}" method="POST">
		@csrf
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" value="{{ old('name', $profile->name) }}" required><br><br>

		<label for="dob">Date of Birth:</label>
		<input type="date" id="dob" name="dob" value="{{ old('dob', $profile->dob) }}" required><br><br>

		<label for="occupation">Job:</label>
		<input type="text" id="occupation" name="occupation" value="{{ old('occupation', $profile->occupation) }}" required><br><br>

		<label for="programming_langs">Languages (JSON):</label>
		<textarea id="programming_langs" name="programming_langs" required>{{ old('programming_langs', json_encode($profile->programming_langs)) }}</textarea><br><br>

		<label for="social_media">Social Media:</label>
		<input type="url" id="social_media" name="social_media" value="{{ old('social_media', $profile->social_media) }}"><br><br>

		<button type="submit">Save</button>
	</form>
</body>

</html>