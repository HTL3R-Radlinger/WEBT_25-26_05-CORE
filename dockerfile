FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN { \
    echo 'max_execution_time=1200'; \
    echo 'memory_limit=2048M'; \
    echo 'post_max_size=100M'; \
    echo 'upload_max_filesize=100M'; \
    echo 'session.save_handler=files'; \
    echo 'session.save_path="/tmp"'; \
    echo 'session.name=DIGITAL_MEAL_PLAN_SESSION'; \
    echo 'session.use_strict_mode=1'; \
    echo 'session.use_only_cookies=1'; \
    echo 'session.cookie_httponly=1'; \
    echo 'session.cookie_lifetime=1800'; \
    echo 'session.gc_maxlifetime=1800'; \
    echo 'session.gc_probability=1'; \
    echo 'session.gc_divisor=100'; \
} > /usr/local/etc/php/conf.d/custom.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY httpd-vhosts.conf /etc/apache2/sites-available/000-default.conf


WORKDIR /var/www/html/
COPY . /var/www/html/

RUN rm -f \
    15_digital_meal_plan.pdf \
    dockerfile \
    docker-compose.yaml \
    httpd-vhosts.conf \
    README.md

RUN chown -R www-data:www-data *
RUN chmod +x /var/www/html

ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]