FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    libpq-dev \
    postgresql-client && \
    docker-php-ext-install pdo pdo_pgsql zip

WORKDIR /usr/src/app

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

RUN npm install
RUN npm run build

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]


CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]


