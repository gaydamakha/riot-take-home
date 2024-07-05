FROM webdevops/php-nginx:8.3

WORKDIR /app

COPY . /app
RUN composer install
# Optimize dependencies loading
RUN composer dump-autoload -a
