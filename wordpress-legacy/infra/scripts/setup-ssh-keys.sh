#!/bin/bash
# ==============================================================
# SSH Anahtar Yönetimi - Tüm Platformlar
# Çalıştırma: bash setup-ssh-keys.sh
# ==============================================================
set -e

SSH_DIR="$HOME/.ssh"
mkdir -p "$SSH_DIR"
chmod 700 "$SSH_DIR"

# ── 1. GitHub için SSH anahtarı ────────────────────────────────
if [ ! -f "$SSH_DIR/id_ed25519_github" ]; then
    echo "=== GitHub SSH anahtarı oluşturuluyor ==="
    ssh-keygen -t ed25519 -C "admin@bitebimuv.org" -f "$SSH_DIR/id_ed25519_github" -N ""
    echo ""
    echo ">>> GitHub'a eklenecek PUBLIC KEY:"
    cat "$SSH_DIR/id_ed25519_github.pub"
    echo ""
    echo "Eklemek için: https://github.com/settings/ssh/new"
fi

# ── 2. Google Cloud için SSH anahtarı ──────────────────────────
if [ ! -f "$SSH_DIR/id_ed25519_gcloud" ]; then
    echo "=== Google Cloud SSH anahtarı oluşturuluyor ==="
    ssh-keygen -t ed25519 -C "admin@bitebimuv.org" -f "$SSH_DIR/id_ed25519_gcloud" -N ""
    echo ""
    echo ">>> Google Cloud'a eklenecek PUBLIC KEY:"
    cat "$SSH_DIR/id_ed25519_gcloud.pub"
    echo ""
    echo "Eklemek için: https://console.cloud.google.com/compute/metadata?tab=sshkeys"
fi

# ── 3. GitHub Actions Deploy Key (sunucu tarafı) ───────────────
if [ ! -f "$SSH_DIR/id_ed25519_deploy" ]; then
    echo "=== GitHub Actions Deploy anahtarı oluşturuluyor ==="
    ssh-keygen -t ed25519 -C "github-actions-deploy" -f "$SSH_DIR/id_ed25519_deploy" -N ""
    # Public key'i authorized_keys'e ekle
    cat "$SSH_DIR/id_ed25519_deploy.pub" >> "$SSH_DIR/authorized_keys"
    chmod 600 "$SSH_DIR/authorized_keys"
    echo ""
    echo ">>> GitHub Secrets'a eklenecek PRIVATE KEY (GCP_SSH_PRIVATE_KEY):"
    cat "$SSH_DIR/id_ed25519_deploy"
    echo ""
    echo "GitHub Secrets eklemek için:"
    echo "  bitebimuv.org: https://github.com/omerfarukkural/bitebimuv.org/settings/secrets/actions"
    echo "  mutluet:       https://github.com/omerfarukkural/mutluet/settings/secrets/actions"
fi

# ── 4. SSH Config Dosyası ──────────────────────────────────────
cat > "$SSH_DIR/config" << 'EOF'
# GitHub
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_ed25519_github
    IdentitiesOnly yes

# Google Cloud VM
Host gcloud-bitebimuv
    HostName 34.141.16.229
    User YOUR_USERNAME_HERE
    IdentityFile ~/.ssh/id_ed25519_gcloud
    IdentitiesOnly yes
EOF

chmod 600 "$SSH_DIR/config"

echo "=== SSH kurulumu tamamlandı ==="
echo "Bağlantı testi: ssh -T git@github.com"
echo "GCloud bağlantısı: ssh gcloud-bitebimuv"
