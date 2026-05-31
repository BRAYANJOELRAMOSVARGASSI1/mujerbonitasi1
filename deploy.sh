#!/bin/bash
ssh -o StrictHostKeyChecking=no -i .\magy-makeup-key-1776408363.pem ec2-user@34.229.165.40 << 'EOF'
cd /var/www/html
sudo git fetch origin
sudo git checkout joel_produccion
sudo git reset --hard origin/joel_produccion
sudo git pull origin joel_produccion
sudo chmod -R 777 storage bootstrap/cache
sudo php artisan migrate --force
sudo php artisan optimize:clear
sudo php artisan config:cache
sudo php artisan view:cache
sudo systemctl restart httpd
EOF
echo "Deployment completed."
echo "Deployment completed."
