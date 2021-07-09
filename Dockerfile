#Dockerfile
FROM php:8.0-apache

COPY src /var/www/html
RUN chown -R www-data.www-data /var/www/html

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_ROOT_USER=root
