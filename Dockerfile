FROM php:7.4-apache
RUN a2enmod rewrite headers deflate
RUN apt-get update -y && apt-get install -y \
    libpng-dev \
    zip \
    unzip \
    git \
    libzip-dev \
    libicu-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql gd zip
RUN apt-get -yqq install exiftool
RUN docker-php-ext-configure exif
RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif
RUN docker-php-ext-install intl

WORKDIR /var/www/html
COPY composer.json ./
RUN composer install --no-scripts --no-autoloader

COPY . ./

RUN pwd
RUN ls -la
#RUN chmod -R 777 ./storage
#RUN chmod -R 777 ./bootstrap/cache/

RUN cp .env.develop .env
RUN composer dump-autoload --optimize

RUN php artisan key:generate 
RUN php artisan storage:link

#Log User interface */log-viewer

# RUN php artisan migrate --force
#optimizing configuration loading
RUN php artisan optimize:clear


RUN rm /etc/apache2/sites-available/000-default.conf && rm /etc/apache2/sites-enabled/000-default.conf
RUN cp vhost.docker.conf /etc/apache2/sites-available/vhost.docker.conf
RUN a2ensite vhost.docker.conf

EXPOSE 80
