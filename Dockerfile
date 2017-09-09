FROM php:apache
COPY auth/ /var/www/html/auth/
COPY logme/ /var/www/html/logme/
COPY maps/ /var/www/html/maps/
COPY tracker/ /var/www/html/tracker/
EXPOSE 80
