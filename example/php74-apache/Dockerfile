FROM php:7.4-apache
RUN a2enmod rewrite
RUN apt-get update && apt-get install -y \
libzip-dev git iputils-ping
RUN docker-php-ext-install zip
RUN pecl install xdebug-3.0.3
RUN echo "xdebug.mode=develop" >> /usr/local/etc/php/php.ini
RUN echo "xdebug.max_nesting_level=64" >> /usr/local/etc/php/php.ini
RUN echo "xdebug.var_display_max_depth=6" >> /usr/local/etc/php/php.ini
RUN docker-php-ext-enable xdebug
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
