FROM php:8.3-fpm-alpine

#RUN apk --update --no-cache add \
#    libsodium \
#    libsodium-dev \
#    libzip-dev \
#    postgresql-dev

RUN docker-php-ext-install mysqli pdo_mysql

# RUN docker-php-ext-install sodium zip intl

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Install wait for it
COPY wait-for-it.sh /usr/local/bin/wait-for-it.sh
RUN chmod +x /usr/local/bin/wait-for-it.sh

ENTRYPOINT ["/bin/sh", "entrypoint.sh"]
