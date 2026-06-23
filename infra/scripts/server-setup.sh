#!/bin/bash
# ==============================================================
# Google Cloud VM İlk Kurulum Scripti
# Sunucu: 34.141.16.229
# Çalıştırma: bash server-setup.sh
# ==============================================================
set -e

echo "=== Sistem güncelleniyor ==="
sudo apt-get update && sudo apt-get upgrade -y

echo "=== Docker kuruluyor ==="
curl -fsSL https://get.docker.com | sh
sudo usermod -aG docker $USER

echo "=== Docker Compose kuruluyor ==="
sudo apt-get install -y docker-compose-plugin

echo "=== Nginx kuruluyor ==="
sudo apt-get install -y nginx certbot python3-certbot-nginx

echo "=== Git kuruluyor ==="
sudo apt-get install -y git

echo "=== Dizinler oluşturuluyor ==="
mkdir -p ~/apps/n8n
mkdir -p ~/apps/mutluet
mkdir -p /var/www/certbot

echo "=== Nginx konfigürasyonları kopyalanıyor ==="
# Bu adımı repo klonladıktan sonra yapın:
# sudo cp ~/apps/bitebimuv.org/infra/nginx/n8n.conf /etc/nginx/sites-available/n8n.bitebimuv.org
# sudo cp ~/apps/bitebimuv.org/infra/nginx/app.conf /etc/nginx/sites-available/app.bitebimuv.org
# sudo ln -sf /etc/nginx/sites-available/n8n.bitebimuv.org /etc/nginx/sites-enabled/
# sudo ln -sf /etc/nginx/sites-available/app.bitebimuv.org /etc/nginx/sites-enabled/
# sudo nginx -t && sudo systemctl reload nginx

echo "=== UFW Güvenlik Duvarı ==="
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw --force enable

echo "=== Kurulum tamamlandı ==="
echo "Sonraki adım: setup-ssh-keys.sh scriptini çalıştırın"
echo "Sonra: certbot --nginx -d n8n.bitebimuv.org -d app.bitebimuv.org"
