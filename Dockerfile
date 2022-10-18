FROM php:8.1-apache

RUN a2enmod rewrite

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip npm \
    && docker-php-ext-install intl opcache \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Install PCOV extension for code coverage
RUN pecl install pcov && docker-php-ext-enable pcov

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY config/apache.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./ /var/www/html

RUN composer install --no-scripts --optimize-autoloader

###> recipes ###
### recipes ###

WORKDIR /var/www/html
CMD ["apache2-foreground"]

