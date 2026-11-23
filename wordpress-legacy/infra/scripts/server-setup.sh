#!/bin/bash
set -e

echo "=== n8n GCP VM Kurulum Scripti ==="
echo "VM: Ubuntu 22.04, n8n.bitebimuv.org"

# 1. Sistem güncellemesi
apt-get update && apt-get upgrade -y

# 2. Docker kurulumu
apt-get install -y ca-certificates curl gnupg
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
chmod a+r /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null
apt-get update
apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Docker grubuna kullanıcı ekle
usermod -aG docker $SUDO_USER || true

# 3. Nginx + Certbot kurulumu
apt-get install -y nginx certbot python3-certbot-nginx

# 4. Nginx konfigürasyonu kopyala
cp /opt/n8n/infra/nginx/n8n.conf /etc/nginx/sites-available/n8n.bitebimuv.org
ln -sf /etc/nginx/sites-available/n8n.bitebimuv.org /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# 5. Önce HTTP ile nginx başlat (certbot için)
nginx -t && systemctl restart nginx

# 6. SSL sertifikası al
certbot --nginx -d n8n.bitebimuv.org --non-interactive --agree-tos --email admin@bitebimuv.org

# 7. Certbot otomatik yenileme
systemctl enable certbot.timer

# 8. n8n .env dosyasını oluştur
if [ ! -f /opt/n8n/infra/n8n/.env ]; then
    POSTGRES_PASSWORD=$(openssl rand -base64 24 | tr -d '/+=' | head -c 32)
    N8N_ENCRYPTION_KEY=$(openssl rand -base64 32 | tr -d '/+=' | head -c 32)
    cat > /opt/n8n/infra/n8n/.env << EOF
POSTGRES_PASSWORD=$POSTGRES_PASSWORD
N8N_ENCRYPTION_KEY=$N8N_ENCRYPTION_KEY
EOF
    echo "=== .env dosyası oluşturuldu ==="
    echo "POSTGRES_PASSWORD: $POSTGRES_PASSWORD"
    echo "N8N_ENCRYPTION_KEY: $N8N_ENCRYPTION_KEY"
    echo "Bu değerleri güvenli bir yerde saklayın!"
fi

# 9. n8n Docker Compose başlat
cd /opt/n8n/infra/n8n
docker compose up -d

# 10. Nginx son kez yeniden başlat
systemctl restart nginx

echo "=== Kurulum tamamlandı ==="
echo "n8n: https://n8n.bitebimuv.org"
