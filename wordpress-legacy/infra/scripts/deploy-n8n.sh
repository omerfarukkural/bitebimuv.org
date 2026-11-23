#!/bin/bash
# ==============================================================
# n8n Deploy Scripti (sunucuda çalışır)
# GitHub Actions tarafından tetiklenir
# ==============================================================
set -e

N8N_DIR="$HOME/apps/n8n"

echo "=== n8n deploy başlıyor ==="
cd "$N8N_DIR"

# En son imajı çek
docker compose pull n8n

# Yeniden başlat (downtime minimize)
docker compose up -d --remove-orphans

# Eski imajları temizle
docker image prune -f

echo "=== n8n deploy tamamlandı ==="
docker compose ps
