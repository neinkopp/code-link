#!/bin/bash
docker compose exec code-link-app php artisan migrate
docker compose exec code-link-app php artisan optimize:clear
docker compose exec code-link-app php artisan optimize
docker compose exec code-link-app php artisan package:discover --ansi