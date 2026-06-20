#!/bin/bash
set -e

echo "=== 1. Configurando HTTPS con Certbot (Let's Encrypt) ==="
# Instalar certbot
sudo dnf install -y certbot python3-certbot-apache 2>/dev/null || sudo yum install -y certbot python3-certbot-apache 2>/dev/null

DOMAIN="mujerbonita.34.229.165.40.nip.io"
WEBROOT="/var/www/html/public"

echo "=== 2. Obteniendo certificado para $DOMAIN ==="
# Parar Apache temporalmente para el challange standalone
sudo systemctl stop httpd 2>/dev/null || true
sudo certbot certonly --standalone \
    --non-interactive \
    --agree-tos \
    --email joelramostrbj@gmail.com \
    -d $DOMAIN \
    --http-01-port 80 2>&1 || echo "Certbot falló, intentando con webroot..."

echo "=== 3. Estado del certificado ==="
sudo certbot certificates 2>&1

echo "=== 4. Configurando Apache VirtualHost HTTPS ==="
sudo tee /etc/httpd/conf.d/mujerbonita-ssl.conf > /dev/null << 'APACHEEOF'
<VirtualHost *:443>
    ServerName mujerbonita.34.229.165.40.nip.io
    DocumentRoot /var/www/html/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/mujerbonita.34.229.165.40.nip.io/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/mujerbonita.34.229.165.40.nip.io/privkey.pem

    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>

    ErrorLog /var/log/httpd/mujerbonita_ssl_error.log
    CustomLog /var/log/httpd/mujerbonita_ssl_access.log combined
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName mujerbonita.34.229.165.40.nip.io
    Redirect permanent / https://mujerbonita.34.229.165.40.nip.io/
</VirtualHost>
APACHEEOF

echo "=== 5. Habilitando mod_ssl ==="
sudo dnf install -y mod_ssl 2>/dev/null || sudo yum install -y mod_ssl 2>/dev/null
sudo systemctl enable httpd
sudo systemctl start httpd

echo "=== HTTPS configurado! ==="
