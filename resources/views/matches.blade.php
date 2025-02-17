<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Deine Matches</title>
</head>

<body>
	<h1>Deine Matches</h1>

	@if($matches->isEmpty())
	<p>Noch keine Matches gefunden. Bleib dran mit swipen!</p>
	@else
	<ul>
		@foreach($matches as $match)
		<li>
			Gematcht mit Benutzer:
			{{ $match->name }}
		</li>
		@endforeach
	</ul>
	@endif

	<a href="/swipe">Mehr Swipen</a> <!-- Replace with dynamic user selection -->
</body>

</html>