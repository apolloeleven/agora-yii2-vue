FROM php:7.2-fpm

# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        wget \
        git \
            --no-install-recommends

RUN docker-php-ext-install zip intl mbstring pdo_mysql exif \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

COPY ./install-composer.sh /

RUN sh /install-composer.sh \
    && rm /install-composer.sh

RUN usermod -u 1000 www-data

VOLUME /root/.composer
WORKDIR /app

EXPOSE 9000
CMD ["echo", "Hello world"]
CMD ["php-fpm"]
