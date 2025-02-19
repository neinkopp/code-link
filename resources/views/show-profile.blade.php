<!DOCTYPE html>
<html>

<head>
	<title>Profil anzeigen</title>
	<link rel="stylesheet" href="{{ asset('css/show-profile.css') }}">

</head>

<body>
	<h1>{{ $profile->name }}'s Profil</h1>
	<div class=profile-info>
		<p>Date of Birth: {{ $profile->dob }}</p>
		<p>Job: {{ $profile->occupation }}</p>
		<p>Languages: {{ implode(', ', json_decode($profile->programming_langs)) }}</p>
		<p>Social Media: {{ $profile->social_media }}</p>
		<p>Code Snippet: {{$profile->showcase_code }} </p>
	</div>

	<div class=btn-container>
		<a href="{{ route('profile.edit') }}">Profil bearbeiten</a>
		<a href="/swipe">Swipe more</a>
	</div>
</body>

</html>