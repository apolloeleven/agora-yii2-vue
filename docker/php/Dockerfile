FROM php:7.4-fpm

ARG user
ARG uid

# Install system dependencies
RUN apt-get update
RUN apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    wget

RUN apt-get install -y --no-install-recommends \
    # needed for gd
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure \
        gd --with-jpeg

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

RUN pecl install -o -f xdebug-2.9.8 \
    && rm -rf /tmp/pear

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/php/php.ini /usr/local/etc/php/
#COPY ./www.conf /usr/local/etc/php/

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /app

# Download webp image optimization tool and make it available using command "cwebp"
RUN wget https://storage.googleapis.com/downloads.webmproject.org/releases/webp/libwebp-1.1.0-linux-x86-64.tar.gz
RUN tar -xf libwebp-1.1.0-linux-x86-64.tar.gz
RUN cp libwebp-1.1.0-linux-x86-64/bin/cwebp /usr/local/bin/
RUN rm -rf libwebp-1.1.0-linux-x86-6*

USER $user
EXPOSE 9000
CMD ["php-fpm"]
