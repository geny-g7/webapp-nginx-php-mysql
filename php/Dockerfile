# Dockerfile
# Utilise une petite image
FROM php:fpm-alpine

# Mets a jour le serveur
RUN apk update; \
    apk upgrade;

# Install mysqli extension  permettant d'utiliser la bd dans PHP
RUN docker-php-ext-install mysqli
