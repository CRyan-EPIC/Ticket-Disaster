FROM php:8.2-apache

RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install mysqli pdo pdo_mysql gd
RUN a2enmod rewrite

# Allow file inclusion from remote URLs (intentionally insecure for training)
RUN echo "allow_url_include = On" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "allow_url_fopen = On" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "file_uploads = On" >> /usr/local/etc/php/conf.d/custom.ini
RUN echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/custom.ini

# Install ping for command injection module
RUN apt-get update && apt-get install -y iputils-ping && rm -rf /var/lib/apt/lists/*

# Apache config to allow .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html \
 && mkdir -p assets/images/bands hackable/uploads \
 && chmod -R 777 assets/images/bands hackable/uploads
