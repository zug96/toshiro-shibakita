# Usar uma imagem base oficial do PHP com FPM (FastCGI Process Manager)
FROM php:8.1-fpm 

# Instalar as extensões PHP necessárias para conectar ao MySQL
# docker-php-ext-install é um script ajudante nessas imagens
RUN docker-php-ext-install pdo pdo_mysql mysqli
