<!DOCTYPE html>
<html>

<head>
	<title>Profil anzeigen</title>
</head>

<body>
	<h1>{{ $profile->name }}'s Profil</h1>
	<p>Geburtsdatum: {{ $profile->dob }}</p>
	<p>Beruf: {{ $profile->occupation }}</p>
	<p>Programmiersprachen: {{ implode(', ', json_decode($profile->programming_langs)) }}</p>
	<p>Social Media: {{ $profile->social_media }}</p>


	<a href="{{ route('profile.edit') }}">Profil bearbeiten</a>
</body>

</html>