# bitebimuv.org Altyapı Kurulum Rehberi

## Mimari Özet

```
bitebimuv.org (Turhost - 94.199.201.3)
  └── WordPress sitesi
  └── GitHub Actions → FTP/SFTP otomatik deploy

n8n.bitebimuv.org (Google Cloud - 34.141.16.229)
  └── Docker: n8n + PostgreSQL
  └── Nginx reverse proxy + SSL
  └── GitHub Actions → SSH otomatik deploy

app.bitebimuv.org (Google Cloud - 34.141.16.229)
  └── Docker: mutluet backend + frontend
  └── Nginx reverse proxy + SSL
```

---

## 1. Gerekli GitHub Secrets

### bitebimuv.org repo → Settings → Secrets → Actions

| Secret Adı | Değer | Nereden Alınır |
|---|---|---|
| `GCP_HOST` | `34.141.16.229` | Sabit |
| `GCP_USER` | `ubuntu` veya VM kullanıcı adı | Google Cloud Console |
| `GCP_SSH_PRIVATE_KEY` | Deploy private key | `setup-ssh-keys.sh` çıktısı |
| `TURHOST_FTP_HOST` | FTP host adresi | Turhost cPanel |
| `TURHOST_FTP_USER` | FTP kullanıcı adı | Turhost cPanel |
| `TURHOST_FTP_PASSWORD` | FTP şifresi | Turhost cPanel |

### mutluet repo → Settings → Secrets → Actions

| Secret Adı | Değer |
|---|---|
| `GCP_HOST` | `34.141.16.229` |
| `GCP_USER` | VM kullanıcı adı |
| `GCP_SSH_PRIVATE_KEY` | Deploy private key |
| `JWT_SECRET` | Güçlü rastgele şifre |
| `POSTGRES_PASSWORD` | DB şifresi |

---

## 2. Google Cloud VM Kurulumu

### Adım 1: Sunucuya bağlan
```bash
# Google Cloud Console'dan SSH
https://console.cloud.google.com/compute/instances
# Veya gcloud CLI:
gcloud compute ssh INSTANCE_NAME --zone=ZONE
```

### Adım 2: Kurulum scriptini çalıştır
```bash
git clone https://github.com/omerfarukkural/bitebimuv.org.git ~/apps/bitebimuv.org
bash ~/apps/bitebimuv.org/infra/scripts/server-setup.sh
```

### Adım 3: SSH anahtarlarını kur
```bash
bash ~/apps/bitebimuv.org/infra/scripts/setup-ssh-keys.sh
# Çıkan PRIVATE KEY'i GitHub Secrets'a ekle
```

### Adım 4: n8n .env dosyasını oluştur
```bash
mkdir -p ~/apps/n8n
cp ~/apps/bitebimuv.org/infra/n8n/.env.example ~/apps/n8n/.env
nano ~/apps/n8n/.env
# Şifreleri doldur
```

### Adım 5: SSL sertifikaları
```bash
sudo certbot --nginx -d n8n.bitebimuv.org
sudo certbot --nginx -d app.bitebimuv.org
```

### Adım 6: n8n başlat
```bash
cd ~/apps/n8n
cp ~/apps/bitebimuv.org/infra/n8n/docker-compose.yml .
docker compose up -d
```

---

## 3. cPanel Git Version Control (bitebimuv.org)

1. Turhost cPanel'e giriş yap
2. **Git™ Version Control** → **Create**
3. Ayarlar:
   - **Clone URL**: `https://github.com/omerfarukkural/bitebimuv.org.git`
   - **Repository Path**: `/home/USERNAME/public_html` (veya theme için `/home/USERNAME/public_html/wp-content/themes/bitebimuv`)
   - **Repository Name**: `bitebimuv-theme`
4. **Create** butonuna bas
5. cPanel SSH Terminal'de:
   ```bash
   cd /home/USERNAME/repositories/bitebimuv.org
   git pull
   ```

---

## 4. Platform Entegrasyonları

### Claude API
- API Key: https://console.anthropic.com/settings/keys
- n8n'de: Settings → Credentials → New → HTTP Header Auth
  - Name: `Claude API`
  - Header: `x-api-key: sk-ant-...`

### Google Cloud
- Service Account: https://console.cloud.google.com/iam-admin/serviceaccounts
- JSON key indir → n8n Credentials → Google Credentials

### GitHub
- Personal Access Token: https://github.com/settings/tokens
- n8n'de: Settings → Credentials → GitHub

### Perplexity
- API Key: https://www.perplexity.ai/settings/api
- n8n'de: HTTP Header Auth

---

## 5. IntelliJ IDEA SSH Bağlantısı

1. **Tools → Deployment → Configuration**
2. **Add (+) → SFTP**
3. Ayarlar:
   - Host: `34.141.16.229`
   - User: VM kullanıcı adı
   - Auth type: Key pair
   - Private key: `~/.ssh/id_ed25519_gcloud`
4. **Test Connection**

---

## 6. Antigravity Entegrasyonu

Antigravity için hangi servis olduğunu belirtin (hosting sağlayıcısı mı, CDN mi, başka bir platform mı?).
Link veya açıklama ile bildirin, entegrasyon eklerim.
