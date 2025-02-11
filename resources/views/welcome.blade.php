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
            <img src="images/Logo.svg"  alt="Codelink Logo">
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
            <img src="images/Logo.svg" alt="Codelink Logo" class="form-logo">
            <h2>Judge me by my code BITCH</h2>
            <form action="#">
                <label for="name">Name</label>
                <input type="text" id="name" placeholder="Value" required>

                <label for="surname">Surname</label>
                <input type="text" id="surname" placeholder="Value" required>

                <label for="dob">Date of birth</label>
                <input type="date" id="dob" placeholder="dd/mm/yyyy" required>

                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Value" required>

                <label for="occupation">Occupation</label>
                <input type="text" id="occupation" placeholder="Value" required>

                <label for="languages">Your favorite programming languages</label>
                <textarea id="languages" placeholder="Value"></textarea>

                <button type="submit" class="submit-btn">Sign up</button>
            </form>
        </div>
    </main>
</body>
</html>