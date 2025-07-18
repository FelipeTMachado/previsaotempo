
# Usa imagem base do PHP-FPM
FROM php:8.2-fpm

# Instala python3, pip e nginx
RUN apt-get update \
    && apt-get install -y python3 python3-pip nginx \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala dependências Python
COPY requirements.txt /app/requirements.txt
WORKDIR /app
RUN pip3 install --break-system-packages --no-cache-dir -r requirements.txt

# Copia código do app
COPY . /app


# Configura PHP-FPM (opcional: define cgi.fix_pathinfo=0 no php.ini)
RUN mkdir -p /run/php && \
    echo "cgi.fix_pathinfo=0" >> /usr/local/etc/php/php.ini

# Configura Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Permissões
RUN chown -R www-data:www-data /app

# Expondo porta
EXPOSE 80

# Comando para iniciar PHP-FPM e Nginx
CMD service nginx start && php-fpm
