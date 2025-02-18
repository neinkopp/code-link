<!DOCTYPE html>
<html>

<head>
	<title>Profil anzeigen</title>
</head>

<body>
	<h1>{{ $profile->name }}'s Profil</h1>
	<p>Date of Birth: {{ $profile->dob }}</p>
	<p>Job: {{ $profile->occupation }}</p>
	<p>Languages: {{ implode(', ', json_decode($profile->programming_langs)) }}</p>
	<p>Social Media: {{ $profile->social_media }}</p>
	<p>Code Snippet: {{$profile->showcase_code }} </p>


	<a href="{{ route('profile.edit') }}">Profil bearbeiten</a>
</body>

</html>