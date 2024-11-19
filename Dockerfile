# Usamos la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Copiar los archivos del proyecto en el contenedor
COPY . /var/www/html/

# Establecer los permisos adecuados para los archivos
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Habilitar el módulo de reescritura de Apache (para manejar .htaccess)
RUN a2enmod rewrite

# Configuración de Apache para permitir el acceso y permitir .htaccess
RUN echo '<Directory /var/www/html>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/sites-available/000-default.conf

# Exponer el puerto 80
EXPOSE 80
