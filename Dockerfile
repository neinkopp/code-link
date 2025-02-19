FROM php:8.2-fpm

ARG user
ARG uid

# Install system dependencies and clean up in a single layer
RUN apt-get update && apt-get install -y \
	git \
	curl \
	libpng-dev \
	libonig-dev \
	libxml2-dev \
	zip \
	unzip \
	libpq-dev \
	&& apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
	pdo_mysql \
	pdo_pgsql \
	mbstring \
	exif \
	pcntl \
	bcmath \
	gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user with www-data as primary group
RUN useradd -u $uid -g www-data -G www-data,root -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
	chown -R $user:www-data /home/$user

WORKDIR /var/www

# Copy the application first
COPY --chown=$user:www-data . .

# Create necessary directories and set permissions
RUN mkdir -p /var/www/vendor && \
	mkdir -p storage/framework/{sessions,views,cache} && \
	mkdir -p storage/logs && \
	chown -R $user:www-data /var/www && \
	chmod -R 775 storage bootstrap/cache

# Switch to user for remaining operations
USER $user

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Add entrypoint script with executable permissions
COPY --chown=$user:$user --chmod=755 docker-entrypoint.sh /usr/local/bin/

EXPOSE 9000

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]