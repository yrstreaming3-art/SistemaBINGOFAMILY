# ============================================================
# Dockerfile - Bingo SaaS
# Permite el despliegue automatico en Railway, Render o cualquier
# plataforma moderna compatible con Docker, sin configurar
# servidores ni cPanel manualmente.
# ============================================================

FROM php:8.2-apache

# Habilita mod_rewrite (necesario para el sistema de rutas del framework)
RUN a2enmod rewrite

# Instala la extension PDO MySQL (requerida para la conexion a base de datos)
RUN docker-php-ext-install pdo pdo_mysql

# Copia todo el proyecto dentro del contenedor
COPY . /var/www/html

# La raiz publica del sitio es la carpeta "public" (buenas practicas MVC:
# el resto del codigo NUNCA queda accesible directamente desde internet)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Permite que los archivos .htaccess funcionen (sistema de rutas)
RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Da permisos de escritura a la carpeta de logs
RUN chown -R www-data:www-data /var/www/html/storage

# Script de arranque: ajusta el puerto segun lo que la plataforma indique
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
