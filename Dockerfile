FROM php:8.2-fpm

# 1. Pasang paket dependensi sistem operasi Linux dasar
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip
 

# 2. Bersihkan cache instalasi agar ukuran kontainer sangat ringan
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Pasang ekstensi PHP yang dibutuhkan wajib oleh Laravel & Filament
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# 4. Ambil dan pasang Composer versi terbaru langsung dari server pusatnya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Tentukan folder kerja utama di dalam kontainer virtual
WORKDIR /var/www

# 6. Salin semua file kodingan Laravel dari laptopmu ke dalam kontainer
COPY . /var/www

# 7. Atur hak kepemilikan file agar aman dibaca oleh web server Linux (www-data)
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
