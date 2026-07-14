FROM php:8.2-apache

# 1. Pasang paket dependensi sistem operasi Linux dasar (Termasuk libpq-dev untuk Postgres)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \
    libpq-dev

# 2. Bersihkan cache instalasi agar ukuran kontainer sangat ringan
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Pasang ekstensi PHP yang dibutuhkan wajib oleh Laravel, Filament, & PostgreSQL
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql exif pcntl bcmath gd intl

# 4. Ambil dan pasang Composer versi terbaru langsung dari server pusatnya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Tentukan folder kerja utama menggunakan standar Apache
WORKDIR /var/www/html

# 6. Salin semua file kodingan Laravel dari laptopmu ke dalam kontainer
COPY . /var/www/html

# 7. Jalankan Composer install
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs

# 8. Atur hak kepemilikan file agar aman dibaca oleh web server Linux (www-data)
RUN chown -R www-data:www-data /var/www/html /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Set Document Root Apache langsung mengarah ke folder public Laravel secara aman
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/default-ssl.conf
RUN a2enmod rewrite

EXPOSE 80
