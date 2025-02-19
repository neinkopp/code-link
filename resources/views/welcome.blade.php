<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Laravel GitHub Login</title>
	<link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>



<body>


	<div class="text-center">

		<div id=heart></div>
		<h1 class=welcome-text>Code your way into my heart</h1>
		<a href="{{ route('auth.github') }}"
			class=login-btn>
			Login with GitHub
		</a>
	</div>

</body>

</html>