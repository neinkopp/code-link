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
            <img src="images/App_Logo.png"  alt="Codelink Logo">
        </div>
        <nav>
            <ul>
                <li><a href="#">Community</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
    @extends('layouts.app')

@section('content')
    <div class="container flex">
        <aside class="w-1/4 p-4 bg-gray-100 h-screen overflow-auto">
            <h2 class="text-xl font-bold mb-4">Matches</h2>
            <ul id="matchesList">
            </ul>
        </aside>
        <main class="flex-1 flex items-center justify-center">
            <div id="swipe-container" class="relative w-3/4 h-3/4 p-6 bg-white shadow-lg rounded-lg">
                <p id="code-snippet" class="text-lg font-mono">Loading code...</p>
                
                <div class="flex justify-around mt-6">
                    <button id="dislike" class="p-4 bg-red-500 text-white rounded-full">❌</button>
                    <button id="like" class="p-4 bg-green-500 text-white rounded-full">❤️</button>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let codeIndex = 0;
            let codeSnippets = [];

            function fetchCodes() {
                fetch("/api/codes") // Fetch from your Laravel backend
                    .then(response => response.json())
                    .then(data => {
                        codeSnippets = data;
                        displayCode();
                    })
                    .catch(error => console.error("Error fetching codes:", error));
            }

            function displayCode() {
                if (codeSnippets.length > 0 && codeIndex < codeSnippets.length) {
                    document.getElementById("code-snippet").textContent = codeSnippets[codeIndex].content;
                } else {
                    document.getElementById("code-snippet").textContent = "No more code snippets!";
                }
            }

            document.getElementById("like").addEventListener("click", function () {
                sendReaction(codeSnippets[codeIndex].id, "like");
            });

            document.getElementById("dislike").addEventListener("click", function () {
                sendReaction(codeSnippets[codeIndex].id, "dislike");
            });

            function sendReaction(codeId, reaction) {
                fetch("/api/reaction", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ code_id: codeId, reaction: reaction })
                })
                .then(() => {
                    codeIndex++;
                    displayCode();
                })
                .catch(error => console.error("Error sending reaction:", error));
            }

            fetchCodes();
        });
    </script>
@endsection

    </main>
</body>
</html>