# Kampüs Portal - Askıda Destek Projesi

[![Deploy to Production](https://github.com/geldimmix/kampusportal/actions/workflows/deploy.yml/badge.svg)](https://github.com/geldimmix/kampusportal/actions/workflows/deploy.yml)

Üniversite öğrencilerinin gıdaya erişimini kupon/rezervasyon sistemiyle yöneten multi-tenant platform.

## 🌐 Canlı Site

**https://kampusportal.com.tr**

## 🎯 Proje Amacı

- Pilot: Selçuk Üniversitesi Vakfı (Konya)
- Bağışçıların öğrencilere yemek desteği sağlaması
- Restoranlar ve üniversite yemekhaneleri entegrasyonu
- İleride: Diğer üniversiteler + giyim/kitap/etkinlik kategorileri

## 📁 Proje Yapısı

```
kampusportal/
├── api/v1/              # REST API endpoints
├── config/              # Yapılandırma dosyaları
├── core/                # Çekirdek sınıflar
├── database/            # SQL şemaları
├── docs/                # Dokümantasyon
├── modules/             # Uygulama modülleri
│   ├── admin/           # Yönetim paneli
│   ├── auth/            # Kimlik doğrulama
│   ├── beneficiary/     # Faydalanıcılar
│   ├── donation/        # Bağışlar
│   ├── reservation/     # Rezervasyonlar
│   ├── restaurant/      # Restoranlar
│   ├── settlement/      # Hak edişler
│   ├── tenant/          # Vakıf/Üniversite
│   └── voucher/         # Kuponlar
├── public/              # Statik dosyalar
├── scripts/             # Yardımcı scriptler
└── shared/              # Paylaşılan bileşenler
```

## 🛠️ Teknolojiler

- **Backend:** PHP 8.3
- **Database:** PostgreSQL 16
- **Web Server:** Apache 2.4
- **SSL:** Let's Encrypt
- **CI/CD:** GitHub Actions

## 🚀 Kurulum

### Gereksinimler

- PHP 8.3+
- PostgreSQL 16+
- Apache 2.4+ veya Nginx
- Composer (opsiyonel)

### Yerel Geliştirme

```bash
# Repo'yu klonla
git clone https://github.com/geldimmix/kampusportal.git
cd kampusportal

# Veritabanını oluştur
psql -U postgres -c "CREATE DATABASE askida_destek"
psql -U postgres -d askida_destek -f database/schema.sql
```

### Production Deployment

GitHub Actions otomatik deploy yapar. `main` branch'e push yapıldığında:

1. Kod test edilir
2. Sunucuya SSH ile bağlanılır
3. Kod güncellenir
4. Apache reload edilir

## 🔐 GitHub Secrets

Deployment için şu secrets'lar gerekli:

| Secret | Açıklama |
|--------|----------|
| `SERVER_HOST` | Sunucu IP adresi |
| `SERVER_USER` | SSH kullanıcı adı |
| `SERVER_SSH_KEY` | SSH private key |

## 👥 Kullanıcı Rolleri

| Rol | Açıklama |
|-----|----------|
| `super_admin` | Sistem yöneticisi |
| `foundation_admin` | Vakıf yöneticisi |
| `foundation_staff` | Vakıf personeli |
| `cafeteria_manager` | Yemekhane müdürü |
| `restaurant_owner` | Restoran sahibi |
| `donor` | Bağışçı |
| `beneficiary` | Faydalanıcı (öğrenci) |

## 📊 Veritabanı

207 Türkiye üniversitesi dahil, 19 ana tablo:

- `universities` - Üniversiteler
- `tenants` - Vakıflar
- `users` - Kullanıcılar
- `beneficiaries` - Faydalanıcılar
- `restaurants` - Restoranlar
- `cafeterias` - Yemekhaneler
- `donations` - Bağışlar
- `reservations` - Rezervasyonlar
- `vouchers` - Kuponlar
- `settlements` - Hak edişler
- ...ve daha fazlası

## 📝 Lisans

Bu proje özel lisans altındadır. Tüm hakları saklıdır.

## 📧 İletişim

- **Web:** https://kampusportal.com.tr
- **Email:** admin@kampusportal.com.tr

