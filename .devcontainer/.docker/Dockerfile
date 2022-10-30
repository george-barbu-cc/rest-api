ARG UBUNTU_VERSION=21.10

FROM ubuntu:${UBUNTU_VERSION}

ARG WWWGROUP
ARG PHP_VERSION=8.1
ARG COMPOSER_VERSION=2.3.5
ARG NODE_VERSION=16
ARG NPM_VERSION
ARG YARN_VERSION

LABEL maintainer="George Barbu"
LABEL version="1.x"
LABEL description="Development environment for the joy and pleasure of web developers"

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y sudo gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 \
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 0x14AA40EC0831756756D7F66C4F4EA0AAE5267A6C \
    && echo "deb https://ppa.launchpadcontent.net/ondrej/php/ubuntu impish main" > /etc/apt/sources.list.d/ppa_ondrej_php.list

RUN apt-get update \
    && apt-get install -y php${PHP_VERSION}-cli php${PHP_VERSION}-dev \
       php${PHP_VERSION}-pgsql php${PHP_VERSION}-sqlite3 php${PHP_VERSION}-gd \
       php${PHP_VERSION}-curl \
       php${PHP_VERSION}-imap php${PHP_VERSION}-mysql php${PHP_VERSION}-mbstring \
       php${PHP_VERSION}-xml php${PHP_VERSION}-zip php${PHP_VERSION}-bcmath php${PHP_VERSION}-soap \
       php${PHP_VERSION}-intl php${PHP_VERSION}-readline \
       php${PHP_VERSION}-ldap \
       php${PHP_VERSION}-msgpack php${PHP_VERSION}-igbinary php${PHP_VERSION}-redis php${PHP_VERSION}-swoole \
       php${PHP_VERSION}-memcached php${PHP_VERSION}-pcov php${PHP_VERSION}-xdebug

RUN apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION}

# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_${NODE_VERSION}.x -o nodesource_setup.sh
RUN bash nodesource_setup.sh
RUN apt-get install nodejs -y

# Install npm
RUN npm install -g npm@${NPM_VERSION}
# Install yarn
RUN npm install -g yarn@${YARN_VERSION}

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php${PHP_VERSION}

RUN groupadd --force -g $WWWGROUP developer
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 developer

COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/${PHP_VERSION}/cli/conf.d/99-php.ini
RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]
