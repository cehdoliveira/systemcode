FROM php:8.3-apache

COPY site.conf /etc/apache2/sites-available/site.conf

RUN a2ensite site.conf \
    && apt-get update \
    && apt-get install -y apt-utils libzip-dev zlib1g-dev cron vim \
    && docker-php-ext-install mysqli zip \
    && a2enmod headers rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the crontab file to the container
COPY crontab.txt /etc/cron.d/simple-cron

# Ensure newline at end of crontab file and set permissions
RUN chmod 0644 /etc/cron.d/simple-cron \
    && echo "" >> /etc/cron.d/simple-cron \
    && crontab /etc/cron.d/simple-cron

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

# Run the command on container startup
CMD service cron start && apache2-foreground