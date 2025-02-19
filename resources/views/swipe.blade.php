<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('css/swipe.css') }}">
	<title>Laravel</title>
</head>
@extends('alert')

@section('content')

<body>
	<header>
		<div class="logo">
			<img src="images/App_Logo.png" alt="Codelink Logo">
		</div>
		<nav>
			<ul>
				<li class="active-buttons"><a href="edit-profile">Edit your profile</a></li>
				<li class="active-buttons"><a href="matches">See Matches</a></li>
			</ul>
			<div class="auth-buttons">
				<form action="{{ route('logout') }}" method="POST">
					@csrf
					<button class="login">Log out</button>
				</form>
			</div>
		</nav>
	</header>


	@if(session('error'))
	<p>{{ session('error') }}</p>
	@endif

	<div class="flex-container">
		<div>
			<ion-icon id="dislike" name="heart-dislike"></ion-icon>
		</div>
		<div id="swiper">
			<!-- Cards will be appended here -->

		</div>
		<div>
			<ion-icon id="like" name="heart"></ion-icon>
		</div>
	</div>
	<!-- Hidden Form for Swiping -->
	<form id="swipeForm" action="{{ route('swipe') }}" method="POST">
		@csrf
		<input type="hidden" name="from_user_id" value="{{ Auth::id() }}">
		<input type="hidden" name="to_user_id" id="toUserId">
		<input type="hidden" name="liked" id="liked">
	</form>

	<script>
		class Card {
			constructor({
				imageUrl,
				username,
				profile_name,
				userId,
				onDismiss,
				onLike,
				onDislike,
				languages
			}) {
				this.imageUrl = imageUrl;
				this.username = username;
				this.profile_name = profile_name;
				this.userId = userId;
				this.onDismiss = onDismiss;
				this.onLike = onLike;
				this.onDislike = onDislike;
				this.languages = languages;

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
				const imgWrapper = document.createElement('div');
				imgWrapper.classList.add('img-wrapper');
				card.append(imgWrapper);
				const infoWrapper = document.createElement('div');
				infoWrapper.classList.add('info-wrapper');
				card.append(infoWrapper);

				const infoAvatar = document.createElement('div');
				infoAvatar.classList.add('info-avatar');
				infoWrapper.append(infoAvatar);

				const avatar = document.createElement('img');
				// url is avatars.githubusercontent.com/u/userId?v=4
				avatar.src = `https://avatars.githubusercontent.com/u/${this.userId}?v=4`;
				infoAvatar.append(avatar);

				const infoDetails = document.createElement('div');
				infoDetails.classList.add('info-details');
				infoWrapper.append(infoDetails);

				const img = document.createElement('img');
				img.classList.add('card-img');
				img.src = this.imageUrl;
				imgWrapper.append(img);


				const profile_name = document.createElement('p');
				profile_name.classList.add('info-container');
				profile_name.textContent = this.profile_name;
				infoDetails.append(profile_name);

				const name = document.createElement('p');
				name.classList.add('info-container');
				name.textContent = "@" + this.username;
				infoDetails.append(name);

				const languages = document.createElement('p');
				languages.classList.add('info-container');
				languages.textContent = "Languages: " + (Array.isArray(this.languages) ? this.languages.join(', ') : this.languages);
				infoDetails.append(languages);

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

		function appendNewCard(imageUrl, profile_name, username, userId, languages) {
			const card = new Card({
				imageUrl: imageUrl,
				profile_name: profile_name,
				username: username,
				userId: userId,
				languages: languages,
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
		@if(count($usersToSwipeOn) > 0)
		appendNewCard(
			'showcase-code-picture/{{ $usersToSwipeOn[0]["id"] }}',
			'{{ $usersToSwipeOn[0]["profile_name"] }}',
			'{{ $usersToSwipeOn[0]["username"] }}',
			'{{ $usersToSwipeOn[0]["id"] }}',
			'{{ implode(", ", json_decode(json_decode($usersToSwipeOn[0]["programming_langs"], true))) }}'
		);
		@endif
	</script>

	<script>
		setInterval(function() {
			$.ajax({
				url: '{{ route("matches.getMatches") }}',
				type: 'GET',
				dataType: 'json',
				success: function(response) {
					if (response.message) {
						toastr.success(response.message);
					}

					if (response.matches.length > 0) {
						$('#matches-list').html('');
						response.matches.forEach(function(match) {
							$('#matches-list').append(
								'<div class="match-item">' + match.name + '</div>'
							);
						});
					}
				},
				error: function() {
					console.log('Error fetching matches');
				}
			});
		}, 2000);
	</script>

	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
@endsection

</html>