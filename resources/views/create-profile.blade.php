<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<title>Laravel</title>
</head>

<body>
	<header>
		<div class="logo">
			<img src="images/App_Logo.png" alt="Codelink Logo">
		</div>
		<nav>
			<ul>
				<li><a href="#">Community</a></li>
				<li><a href="#">About Us</a></li>
				<li><a href="#">Contact</a></li>
			</ul>
			<div class="auth-buttons">
				<button class="login">Log in</button>
				<button class="signup">Sign up</button>
			</div>
		</nav>
	</header>

	<main>
		<div class="form-container">
			<img src="images/App_Logo.png" alt="Codelink Logo" class="form-logo">
			<h2>
				<p>&#128151;</p>Code my heart
			</h2>
			<form action="{{ route('create-profile.handle') }}" method="POST">
				@csrf
				<label for="name">Name</label>
				<input type="text" id="name" name="name" placeholder="..." required>

				<label for="dob">Date of birth</label>
				<input type="date" id="dob" name="dob" placeholder="dd/mm/yyyy" required>

				<label for="gender">Gender</label>
				<select id="gender" name="gender" required>
					<option value="">Choose</option>
					<option value="male">Male</option>
					<option value="female">Female</option>
					<option value="other">Other/Diverse</option>
				</select>

				<label for="interested_in">Interested in gender</label>
				<select id="interested_in" name="interested_in[]" multiple required>
					<option value="male">Male</option>
					<option value="female">Female</option>
					<option value="other">Other/Diverse</option>
				</select>

				<label for="occupation">Occupation</label>
				<input type="text" id="occupation" name="occupation" placeholder="..." required>

				<label for="programming_langs">Your favorite programming languages</label>
				<textarea id="programming_langs" name="programming_langs" placeholder="Please don't say Python"></textarea>

				<label for='social_media'>Social media (how do you want to get contacted by matches?)</label>
				<input type="text" id="social_media" name="social_media" placeholder="..." required>

				<label for="showcase_code">Your favorite code snippet</label>
				<textarea id="showcase_code" name="showcase_code" placeholder="..." required></textarea>

				<button type="submit" class="submit-btn">Sign up</button>
			</form>
		</div>
		@if ($errors->any())
		<div>
			<ul>
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
	</main>
</body>

</html>