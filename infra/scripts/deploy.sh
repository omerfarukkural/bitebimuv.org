#!/bin/bash
set -e

echo "=== n8n Deploy ==="
cd /opt/n8n

# Repo güncelle
git fetch origin
git reset --hard origin/main

# n8n container'ı yeniden başlat
cd infra/n8n
docker compose pull n8n
docker compose up -d n8n

echo "=== Deploy tamamlandı ==="
