FROM php:8.1-apache

# Expose arguments to modify the uid and gid of the www-data user to match the host building the
# image
ARG DM_UID=1000
ARG DM_GID=1001
RUN usermod  --uid $DM_UID www-data
RUN groupmod --gid $DM_GID www-data

RUN docker-php-ext-install mysqli

# For debugging
RUN pecl install xdebug

# Configure xdebug. Partially taken from https://stackoverflow.com/a/65061814 and
# https://stackoverflow.com/a/50088033
# xdebug requires a browser extension to be installed to enable remote debugging
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=IDEA" >> /usr/local/etc/php/conf.d/xdebug.ini

RUN chown www-data:www-data /var/www/html