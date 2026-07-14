FROM php:8.2-apache

# 1. Pasang paket dependensi sistem operasi Linux dasar
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Pasang ekstensi PHP wajib
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql exif pcntl bcmath gd intl

# 3. Ambil Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Tentukan folder kerja
WORKDIR /var/www/html

# 5. Salin konfigurasi Apache terlebih dahulu
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# 6. Salin semua file projek
COPY . /var/www/html

# 7. Jalankan Composer install tanpa scripts
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs --no-scripts

# 8. Setel izin akses secara spesifik & cepat (HANYA folder storage & cache, jangan seluruh folder html!)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Deklarasikan Port Apache secara tegas
ENV PORT=80
EXPOSE 80
