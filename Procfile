release: |
  php artisan migrate --force || (sleep 5 && php artisan migrate --force) || (sleep 5 && php artisan migrate --force)
web: php artisan serve --host=0.0.0.0 --port=${PORT}