FROM php:8.1-apache

# Expose arguments to modify the uid and gid of the www-data user to match the host building the
# image
ARG DM_UID=1000
ARG DM_GID=1001
RUN usermod  --uid $DM_UID www-data
RUN groupmod --gid $DM_GID www-data

RUN docker-php-ext-install mysqli
RUN chown www-data:www-data /var/www/html