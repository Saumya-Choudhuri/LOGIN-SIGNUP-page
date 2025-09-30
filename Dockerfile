FROM php:8.3-apache
COPY . /var/www/html/
EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "/var/www/html"]