FROM php:8.2-apache

# Active mod_rewrite
RUN a2enmod rewrite

# Mise à jour et installation des extensions nécessaires
RUN apt-get update && apt-get upgrade -y \
    && docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo pdo_mysql \
    && apt install -y ca-certificates curl git

# Installation de l'extension Intl
RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Installation de Go et mhsendmail pour l'envoi de courriels
RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.8.3.linux-amd64.tar.gz' | tar -C '/usr/local' -xvzf - \
    && PATH="/usr/local/go/bin:$PATH" \
    && go get github.com/mailhog/mhsendmail \
    && cp /root/go/bin/mhsendmail /usr/bin/mhsendmail \
    && echo 'sendmail_path = /usr/bin/mhsendmail --smtp-addr mailhog:1025' > /usr/local/etc/php/php.ini

# Copie la configuration personnalisée d'Apache dans l'image Docker
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Active la configuration
RUN a2ensite 000-default.conf

# Copie les fichiers de l'application dans le conteneur
COPY . /var/www/html/
