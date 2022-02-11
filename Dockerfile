FROM php:7.4-fpm

LABEL maintainer="MilesChou <github.com/MilesChou>, fizzka <github.com/fizzka>"

ARG PSR_VERSION=1.1.0
ARG PHALCON_VERSION=4.1.2
ARG PHALCON_EXT_PATH=php7/64bits
ARG PHALCON_DEVTOOLS_VERSION=4.2.0

WORKDIR /var/www/app

RUN apt-get update && apt-get install -y --no-install-recommends \
        libpq-dev \
        libonig-dev \
        libicu-dev \
        libzip-dev \
        curl \
        zip \
        unzip \
        git \
    && docker-php-ext-install -j$(nproc) opcache gettext pdo zip pdo_pgsql pgsql mbstring bcmath intl \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-enable pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

RUN set -xe && \
        # Download PSR, see https://github.com/jbboehr/php-psr
        curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && \
        tar xzf ${PWD}/v${PSR_VERSION}.tar.gz && \
        # Download Phalcon
        curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
        tar xzf ${PWD}/v${PHALCON_VERSION}.tar.gz && \
        docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) \
            ${PWD}/php-psr-${PSR_VERSION} \
            ${PWD}/cphalcon-${PHALCON_VERSION}/build/${PHALCON_EXT_PATH} \
        && \
        # Remove all temp files
        rm -r \
            ${PWD}/v${PSR_VERSION}.tar.gz \
            ${PWD}/php-psr-${PSR_VERSION} \
            ${PWD}/v${PHALCON_VERSION}.tar.gz \
            ${PWD}/cphalcon-${PHALCON_VERSION} \
        && \
        php -m

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# devtools
RUN curl -LO https://github.com/phalcon/phalcon-devtools/archive/v${PHALCON_DEVTOOLS_VERSION}.tar.gz \
    && tar xzf v${PHALCON_DEVTOOLS_VERSION}.tar.gz \
    && mv phalcon-devtools-${PHALCON_DEVTOOLS_VERSION} /usr/local/phalcon-devtools \
    && ln -s /usr/local/phalcon-devtools/phalcon /usr/local/bin/phalcon \
    && chmod ugo+x /usr/local/bin/phalcon \
    && rm -f v${PHALCON_DEVTOOLS_VERSION}.tar.gz

RUN cd /usr/local/phalcon-devtools && composer install