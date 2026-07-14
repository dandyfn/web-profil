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
    libpq-dev


# 2. Bersihkan cache instalasi agar ukuran kontainer sangat ringan
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Pasang ekstensi PHP yang dibutuhkan wajib oleh Laravel & Filament
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd intl

# 4. Ambil dan pasang Composer versi terbaru langsung dari server pusatnya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Tentukan folder kerja utama di dalam kontainer virtual
WORKDIR /var/www

# 6. Salin semua file kodingan Laravel dari laptopmu ke dalam kontainer
COPY . /var/www

# 7. Atur hak kepemilikan file agar aman dibaca oleh web server Linux (www-data)
RUN chown -R www-data:www-data /var/www

ENV APACHE_DOCUMENT_ROOT /var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

EXPOSE 80
