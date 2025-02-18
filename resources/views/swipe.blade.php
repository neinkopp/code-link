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
				<form action="{{ route('logout') }}" method="POST">
					@csrf
					<button class="login">Log out</button>
				</form>
			</div>
		</nav>
	</header>
	<h1>Benutzer swipen</h1>

	<form action="{{ route('swipe') }}" method="POST">
		@csrf
		<div>
			<label for="to_user_id">Diesen Benutzer beurteilen:</label>
			<select name="to_user_id" id="to_user_id">
				@foreach($usersToSwipeOn as $user)
				<option value="{{ $user['id'] }}">{{ $user['login'] }}</option>
				@endforeach
			</select>
		</div>

		<div>
			<input type="radio" name="liked" value="1" id="liked_yes">♥️
			<input type="radio" name="liked" value="0" id="liked_no">💔
		</div>

		<button type="submit">Swipen</button>
	</form>

	<!-- hidden form to submit swipes -->
	<form id="swipeForm" action="{{ route('swipe') }}" method="POST">
		@csrf
		<input type="hidden" name="from_user_id" value="{{ auth()->id() }}"> <!-- Get current user ID -->
		<input type="hidden" name="to_user_id" id="toUserId">
		<input type="hidden" name="liked" id="liked">
	</form>

	@if(session('error'))
	<p>{{ session('error') }}</p>
	@endif

	@if(session('success'))
	<p>{{ session('success') }}</p>
	@endif

	<ion-icon id="dislike" name="heart-dislike"></ion-icon>
	<div id="swiper">
		@foreach($usersToSwipeOn as $index => $user)
		<div class="card" style="--i:{{ $index }}">
			<img src="{{ $user['avatar_url'] }}" alt="{{ $user['login'] }}">
			<p>{{ $user['login'] }}</p>
		</div>
		@endforeach
	</div>

	<ion-icon id="like" name="heart"></ion-icon>

	<script>
		class Card {
			constructor({
				imageUrl,
				username,
				userId,
				onDismiss,
				onLike,
				onDislike
			}) {
				this.imageUrl = imageUrl;
				this.username = username;
				this.userId = userId;
				this.onDismiss = onDismiss;
				this.onLike = onLike;
				this.onDislike = onDislike;
				this.#init();
			}

			#startPoint;
			#offsetX;
			#offsetY;

			#isTouchDevice = () => ('ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0);

			#init = () => {
				const card = document.createElement('div');
				card.classList.add('card');
				card.setAttribute('data-user-id', this.userId);

				const img = document.createElement('img');
				img.src = this.imageUrl;
				card.append(img);

				const name = document.createElement('p');
				name.textContent = this.username;
				card.append(name);

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
					};
					document.addEventListener('touchmove', this.#handleTouchMove);
					this.element.style.transition = 'transform 0s';
				});

				document.addEventListener('touchend', this.#handleTouchEnd);
				document.addEventListener('touchcancel', this.#handleTouchEnd);
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
					};
					document.addEventListener('mousemove', this.#handleMouseMove);
					this.element.style.transition = 'transform 0s';
				});

				document.addEventListener('mouseup', this.#handleMoveUp);
				this.element.addEventListener('dragstart', (e) => e.preventDefault());
			}

			#handleMove = (x, y) => {
				this.#offsetX = x - this.#startPoint.x;
				this.#offsetY = y - this.#startPoint.y;
				const rotate = this.#offsetX * 0.1;
				this.element.style.transform = `translate(${this.#offsetX}px, ${this.#offsetY}px) rotate(${rotate}deg)`;

				if (Math.abs(this.#offsetX) > this.element.clientWidth * 0.7) {
					this.#dismiss(this.#offsetX > 0 ? 1 : -1);
				}
			}

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

				const liked = direction === 1 ? 1 : 0;
				const toUserId = this.userId;

				if (!toUserId) {
					console.error("Error: No user ID found!");
					return;
				}

				// Fill in the hidden form and submit
				document.getElementById('toUserId').value = toUserId;
				document.getElementById('liked').value = liked;
				document.getElementById('swipeForm').submit();

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

		const swiper = document.querySelector('#swiper');
		const like = document.querySelector('#like');
		const dislike = document.querySelector('#dislike');

		function appendNewCard(imageUrl, username, userId) {
			const card = new Card({
				imageUrl: imageUrl,
				username: username,
				userId: userId,
				onDismiss: () => {},
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
		}

		@foreach($usersToSwipeOn as $user)
		appendNewCard('{{ $user['
			avatar_url '] }}', '{{ $user['
			login '] }}', '{{ $user['
			id '] }}');
		@endforeach
	</script>

	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>