# BiteBiMuv Dernek WordPress Teması

BiteBiMuv Derneği resmi WordPress teması. Modern, duyarlı ve tam özelliöekli dernek teması.

## Özellikler

- **İnteraktif Gülümseyen Yüz Maskotu** - Fare hareketine ve scroll'a duyarlı SVG animasyonu
  - Gözbebeğleri fare konumunu takip eder
  - Kaydırma oranına göre gülümseme büyür
  - Otomatik kırpma animasyonu
  - Hover'da yanak pembe efekti
- **Kapsamlı Dernek Yonetimi**
  - Etkinlik takvimi (Etkinlikler özel içerik türü)
  - Yönetim kurulu üye profilleri
  - Proje yönetimi
  - Duyuru sistemi
- **Modern Tasarım** - Tam duyarlı (responsive) tasarım
- **AJAX İletişim Formu** - Sayfa yenileme olmadan gönderim
- **AJAX Etkinlik Kayıt** - Modal form ile etkinliğe kayıt
- **Özelleştirici Desteği** - WordPress Customizer ile renkler, metinler
- **WP Block Editörü** - Full Site Editing ve Gutenberg desteği
- **Türkçe Dil Desteği** - Tüm metin çevrilebilir
- **Otomatik Deploy** - GitHub Actions ile sunucuya otomatik deploy

## Kurulum

### Manuelle Kurulum

1. Bu repository'yi ZIP olarak indirin
2. WordPress Admin > Görünüm > Temalar > Tema Ekle > Tema Yükle
3. ZIP dosyasını seçin ve yükleyin
4. Temayı aktif edin

### Otomatik Deploy (GitHub Actions)

Sunucuya otomatik deploy için GitHub Secrets ayarlayın:

| Secret Adı | Açıklama |
|------------|----------|
| `SFTP_HOST` | Sunucu adresi (orn: bitebimuv.org) |
| `SFTP_USERNAME` | SFTP kullanıcı adı |
| `SFTP_PASSWORD` | SFTP şifre (ya da SSH key) |
| `SFTP_PRIVATE_KEY` | SSH özel anahtarı (isteye bağlı) |
| `REMOTE_PATH` | `/var/www/html/wp-content/themes/bitebimuv-dernek` |

Secrets ayarlandıktan sonra `main` branch'ine her push'ta tema otomatik deploy edilir.

## Özelleştirme

WordPress Paneli > Görünüm > Özelleştir bölümünden:

- **Renkler** - Ana renk, ikincil renk, vurgu rengi
- **Hero Bölümü** - Başlık, alt başlık, buton metni
- **Hakkımızda** - Dernek tanıtım metni
- **İstatistikler** - Üye, etkinlik, yıl, proje sayıları
- **İletişim Bilgileri** - Adres, telefon, e-posta
- **Sosyal Medya** - Facebook, Instagram, Twitter, YouTube, LinkedIn

## Özel İçerik Türleri

- **Etkinlikler** (`bbm_event`) - Tarih, saat, konum, kapasite, ücret
- **Yönetim Kurulu** (`bbm_member`) - Unvan, iletişim, LinkedIn
- **Projeler** (`bbm_project`) - Durum, tarih aralığı, bütçe
- **Duyurular** (`bbm_announcement`) - Genel duyurular

## Geliştirme

```bash
# Repository'yi klonla
git clone https://github.com/omerfarukkural/bitebimuv.org.git

# WordPress wp-content/themes dizinine kopyala
cp -r bitebimuv.org /path/to/wordpress/wp-content/themes/bitebimuv-dernek

# WordPress panelinden temayı aktif et
# Görünüm > Temalar > BiteBiMuv Dernek > Aktif Et
```

## Versiyon

**2.0.0** - Haziran 2026

## Lisans

GPL-2.0-or-later - bkz. [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html)
