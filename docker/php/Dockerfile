FROM php:8.1.12-apache

# Install packages
RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libmagickwand-dev \
    inkscape

# Install PHP extensions
RUN docker-php-ext-configure \
    intl

RUN docker-php-ext-install -j$(nproc) \
    intl \
    pdo \
    pdo_mysql \
    opcache \
    zip

RUN pecl install \
    imagick \
    xdebug \
    redis

RUN docker-php-ext-enable \
    imagick \
    xdebug \
    redis

# Install composer and set COMPOSER_HOME to writable directory
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_HOME /tmp/.composer

# Enable development PHP configuration and copy project configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY php.ini "$PHP_INI_DIR/conf.d/custom.ini"

# Enable apache modules and copy project configuration
RUN /usr/sbin/a2enmod rewrite && /usr/sbin/a2enmod headers && /usr/sbin/a2enmod expires
COPY apache.conf "$APACHE_CONFDIR/sites-available/000-default.conf"

RUN mkdir /usr/local/nvm
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 14.16.0
RUN curl https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

##need to chown because front-end build
#RUN chown www-data /var/www/
## Set default user and working directory
#USER www-data

WORKDIR /var/www/html
