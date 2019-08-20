FROM php:fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql bcmath

RUN curl -sS https://getcomposer.org/installer | php

RUN mv composer.phar /usr/local/bin/composer

COPY docker/start.sh /usr/local/bin/start

RUN chown -R www-data:www-data /var/www/html \
    && chmod u+x /usr/local/bin/start

CMD ["php-fpm"]
# ENTRYPOINT ["/usr/local/bin/start.sh"]
# CMD ["run"]