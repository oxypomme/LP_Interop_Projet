FROM php:8.0.13-alpine

WORKDIR /app

# Upgrade
RUN apk update \
  && apk upgrade -U -a \
  && apk add --no-cache libxslt-dev \
  && docker-php-ext-install xsl

EXPOSE 8080
CMD php -S 0.0.0.0:8080 ./index.php