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

					<ion-icon id="dislike" name="heart-dislike"></ion-icon>
					<div id="swiper">
						<!-- <div class="card" style="--i:0">
      <img src="https://source.unsplash.com/random/1000x1000/?sky" alt="">
    </div>
    <div class="card" style="--i:1">
      <img src="https://source.unsplash.com/random/1000x1000/?landscape" alt="">
    </div>
    <div class="card" style="--i:2">
      <img src="https://source.unsplash.com/random/1000x1000/?ocean" alt="">
    </div>
    <div class="card" style="--i:3">
      <img src="https://source.unsplash.com/random/1000x1000/?moutain" alt="">
    </div>
    <div class="card" style="--i:4">
      <img src="https://source.unsplash.com/random/1000x1000/?forest" alt="">
    </div> -->
					</div>
					<ion-icon id="like" name="heart"></ion-icon>

					<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
					<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
				</div>
			</main>
		</div>

		<script>
			// DOM
			const swiper = document.querySelector('#swiper');
			const like = document.querySelector('#like');
			const dislike = document.querySelector('#dislike');

			// constants
			const urls = [
				'https://source.unsplash.com/random/1000x1000/?sky',
				'https://source.unsplash.com/random/1000x1000/?landscape',
				'https://source.unsplash.com/random/1000x1000/?ocean',
				'https://source.unsplash.com/random/1000x1000/?moutain',
				'https://source.unsplash.com/random/1000x1000/?forest'
			];

			// variables
			let cardCount = 0;

			// functions
			function appendNewCard() {
				const card = new Card({
					imageUrl: urls[cardCount % 5],
					onDismiss: appendNewCard,
					onLike: () => {
						like.style.animationPlayState = 'running';
						like.classList.toggle('trigger');
					},
					onDislike: () => {
						dislike.style.animationPlayState = 'running';
						dislike.classList.toggle('trigger');
					}
				});
				swiper.append(card.element);
				cardCount++;

				const cards = swiper.querySelectorAll('.card:not(.dismissing)');
				cards.forEach((card, index) => {
					card.style.setProperty('--i', index);
				});
			}

			// first 5 cards
			for (let i = 0; i < 5; i++) {
				appendNewCard();
			}
			class Card {
				constructor({
					imageUrl,
					onDismiss,
					onLike,
					onDislike
				}) {
					this.imageUrl = imageUrl;
					this.onDismiss = onDismiss;
					this.onLike = onLike;
					this.onDislike = onDislike;
					this.#init();
				}

				// private properties
				#startPoint;
				#offsetX;
				#offsetY;

				#isTouchDevice = () => {
					return (('ontouchstart' in window) ||
						(navigator.maxTouchPoints > 0) ||
						(navigator.msMaxTouchPoints > 0));
				}

				#init = () => {
					const card = document.createElement('div');
					card.classList.add('card');
					const img = document.createElement('img');
					img.src = this.imageUrl;
					card.append(img);
					this.element = card;
					if (this.#isTouchDevice()) {
						this.#listenToTouchEvents();
					} else {
						this.#listenToMouseEvents();
					}
				}

				#listenToTouchEvents = () => {
					this.element.addEventListener('touchstart', (e) => {
						const touch = e.changedTouches[0];
						if (!touch) return;
						const {
							clientX,
							clientY
						} = touch;
						this.#startPoint = {
							x: clientX,
							y: clientY
						}
						document.addEventListener('touchmove', this.#handleTouchMove);
						this.element.style.transition = 'transform 0s';
					});

					document.addEventListener('touchend', this.#handleTouchEnd);
					document.addEventListener('cancel', this.#handleTouchEnd);
				}

				#listenToMouseEvents = () => {
					this.element.addEventListener('mousedown', (e) => {
						const {
							clientX,
							clientY
						} = e;
						this.#startPoint = {
							x: clientX,
							y: clientY
						}
						document.addEventListener('mousemove', this.#handleMouseMove);
						this.element.style.transition = 'transform 0s';
					});

					document.addEventListener('mouseup', this.#handleMoveUp);

					// prevent card from being dragged
					this.element.addEventListener('dragstart', (e) => {
						e.preventDefault();
					});
				}

				#handleMove = (x, y) => {
					this.#offsetX = x - this.#startPoint.x;
					this.#offsetY = y - this.#startPoint.y;
					const rotate = this.#offsetX * 0.1;
					this.element.style.transform = `translate(${this.#offsetX}px, ${this.#offsetY}px) rotate(${rotate}deg)`;
					// dismiss card
					if (Math.abs(this.#offsetX) > this.element.clientWidth * 0.7) {
						this.#dismiss(this.#offsetX > 0 ? 1 : -1);
					}
				}

				// mouse event handlers
				#handleMouseMove = (e) => {
					e.preventDefault();
					if (!this.#startPoint) return;
					const {
						clientX,
						clientY
					} = e;
					this.#handleMove(clientX, clientY);
				}

				#handleMoveUp = () => {
					this.#startPoint = null;
					document.removeEventListener('mousemove', this.#handleMouseMove);
					this.element.style.transform = '';
				}

				// touch event handlers
				#handleTouchMove = (e) => {
					if (!this.#startPoint) return;
					const touch = e.changedTouches[0];
					if (!touch) return;
					const {
						clientX,
						clientY
					} = touch;
					this.#handleMove(clientX, clientY);
				}

				#handleTouchEnd = () => {
					this.#startPoint = null;
					document.removeEventListener('touchmove', this.#handleTouchMove);
					this.element.style.transform = '';
				}

				#dismiss = (direction) => {
					this.#startPoint = null;
					document.removeEventListener('mouseup', this.#handleMoveUp);
					document.removeEventListener('mousemove', this.#handleMouseMove);
					document.removeEventListener('touchend', this.#handleTouchEnd);
					document.removeEventListener('touchmove', this.#handleTouchMove);
					this.element.style.transition = 'transform 1s';
					this.element.style.transform = `translate(${direction * window.innerWidth}px, ${this.#offsetY}px) rotate(${90 * direction}deg)`;
					this.element.classList.add('dismissing');
					setTimeout(() => {
						this.element.remove();
					}, 1000);
					if (typeof this.onDismiss === 'function') {
						this.onDismiss();
					}
					if (typeof this.onLike === 'function' && direction === 1) {
						this.onLike();
					}
					if (typeof this.onDislike === 'function' && direction === -1) {
						this.onDislike();
					}
				}
			}
		</script>
		@endsection

	</main>
</body>

</html>