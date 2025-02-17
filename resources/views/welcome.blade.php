<!DOCTYPE html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Laravel GitHub Login</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
	<div class="text-center">
		<h1 class="text-3xl mb-6">Code my heart...</h1>
		<a href="{{ route('auth.github') }}"
			class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
			Login with GitHub
		</a>
	</div>
</body>

</html>