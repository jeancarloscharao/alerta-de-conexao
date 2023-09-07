# Usar a imagem oficial do PHP com Apache
FROM php:8.0-apache

# Instalar extensões e ferramentas necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Instalar Composer, o gerenciador de pacotes PHP
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Copiar o código PHP e o composer.json para o container
COPY src/* /var/www/html/
COPY composer.json composer.lock /var/www/html/

# Instalar as dependências listadas no composer.json
# RUN composer install

# Expor a porta 80
EXPOSE 80

# Usar o Apache para servir a aplicação
CMD ["apache2-foreground"]
