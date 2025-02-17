<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Swipe</title>
</head>

<body>
	<h1>Benutzer swipen</h1>

	<form action="{{ route('swipe') }}" method="POST">
		@csrf
		<div>
			<label for="to_user_id">Diesen Benutzer beurteilen:</label>
			<select name="to_user_id" id="to_user_id">
				@foreach($usersToSwipeOn as $user)
				<option value="{{ $user->id }}">{{ $user->name }}</option>
				@endforeach
			</select>
		</div>

		<div>
			<input type="radio" name="liked" value="1" id="liked_yes">♥️
			<input type="radio" name="liked" value="0" id="liked_no">💔
		</div>

		<button type="submit">Swipen</button>
	</form>

	@if(session('error'))
	<p>{{ session('error') }}</p>
	@endif

	@if(session('success'))
	<p>{{ session('success') }}</p>
	@endif
</body>

</html>