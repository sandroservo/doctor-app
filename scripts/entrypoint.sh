#!/bin/bash

# Define o domínio
DOMAIN="doctor-app.cloudservo.com.br"
EMAIL="sandro_servo@hotmail.com" # Mude para o seu email

# Verifica se o certificado já existe
if [ ! -f "/etc/letsencrypt/live/$DOMAIN/fullchain.pem" ]; then
    echo "Certificado SSL não encontrado para $DOMAIN. Solicitando um novo certificado..."
    certbot certonly --webroot --webroot-path=/var/www/html \
        --email $EMAIL --agree-tos --no-eff-email \
        -d $DOMAIN
else
    echo "Certificado SSL já existe para $DOMAIN."
fi

# Inicia o processo de renovação automática
while :; do
    certbot renew
    sleep 12h
done

