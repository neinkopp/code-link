<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="{{ asset('css/matches.css') }}">
	<title>Your Matches</title>
</head>

<body>
	<h1>Your Matches</h1>

	@if($matches->isEmpty())
	<p>No matches found yet. Keep swiping! 🚀</p>
	@else
	<ul>
		@foreach($matches as $match)
		<li>
			Matched with user
			{{ $match->name }}
		</li>
		@endforeach
	</ul>
	@endif
	<div class=btn-container>
		<a href="/swipe">Swipe more</a>
	</div>
</body>

</html>