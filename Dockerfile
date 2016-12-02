FROM php:7.0-apache
MAINTAINER "Alex Boyce <alex@adwave365.com>"
LABEL version=1.0

ENV APCU_REPO https://github.com/krakjoe/apcu

RUN apt-get update && \
  apt-get install -y zlib1g-dev libicu-dev g++ libmcrypt-dev git && \
  docker-php-ext-install intl json mbstring pdo pdo_mysql mcrypt && \
  docker-php-ext-enable intl json mbstring pdo pdo_mysql mcrypt

RUN \
  echo 'Installing APCU' && \
  git clone $APCU_REPO && \
  cd apcu && \
  phpize && \
  PHPCONFIG=$(which php-config) && \
  ./configure --with-php-config=$PHPCONFIG && \
  make && \
  export TEST_PHP_ARGS='-n' && \
  make test && \
  no | make install && \
  cd .. && \
  rm -Rf apcu/

RUN echo "extension=apcu.so" > /usr/local/etc/php/conf.d/apcu.ini

RUN mkdir -p /var/log/apache

COPY webserver.conf /etc/apache2/sites-available/
COPY . /var/www/html/
WORKDIR /var/www/html/

RUN a2enmod rewrite && a2ensite webserver && a2dissite 000-default

RUN mv php.ini /usr/local/etc/php/
RUN app/console c:c --env=prod
RUN chown -R www-data:www-data .
RUN chown -R www-data:www-data app/cache && chmod -R a+wxs app/cache/ && chmod -R a+ws app/logs/

EXPOSE 80

ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]