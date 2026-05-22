FROM php:8.1-apache

# 1. Washa Apache mod_rewrite kwa ajili ya Laravel routing ya kupendeza
RUN a2enmod rewrite

# 2. Sakinisha system dependencies na PHP extensions zinazohitajika
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        libpq-dev \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        xml \
        zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 3. Badilisha Document Root ya Apache ielekeze kwenye folder la /public la Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Badilisha Port ya Apache itumie port 10000 inayotakiwa na Render
RUN sed -i 's/Listen 80/Listen 10000/g' /etc/apache2/ports.conf
RUN sed -i 's/:80>/:10000>/g' /etc/apache2/sites-available/*.conf

WORKDIR /var/www/html

# 5. Copy mradi wako mzima kwenda kwenye container
COPY . /var/www/html

# 6. Sakinisha Composer dependencies zote (pamoja na Faker ya seeder)
RUN composer install --optimize-autoloader --no-interaction --no-progress

# 7. Safisha Cache zote za Laravel zilizoganda
RUN php artisan config:clear \
    && php artisan cache:clear \
    && php artisan route:clear \
    && php artisan view:clear

# 8. Weka ruhusa (permissions) sahihi kwa ajili ya Laravel storage na cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 10000

# 9. LALIMISHA MIGRATIONS NA SEEDERS ZIRUN HAPA KABLA YA KUWASHA APACHE
# Tunatumia shell script fupi ili isifeli kama database haijawa tayari kwa sekunde hiyo
CMD php artisan migrate --seed --force && apache2-foreground