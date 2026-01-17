#!/bin/bash
# ==================================================
# Kampüs Portal - Server İlk Kurulum Scripti
# Bu script sunucuda bir kez çalıştırılır
# ==================================================

set -e

echo "=========================================="
echo "  Kampüs Portal - Server Kurulumu"
echo "=========================================="

# Web dizinini hazırla
echo "[1/5] Web dizini hazırlanıyor..."
rm -rf /var/www/askida-destek/*
cd /var/www/askida-destek

# Git repo'yu klonla
echo "[2/5] Git repository klonlanıyor..."
git clone https://github.com/geldimmix/kampusportal.git .

# İzinleri ayarla
echo "[3/5] Dosya izinleri ayarlanıyor..."
chown -R www-data:www-data /var/www/askida-destek
chmod -R 755 /var/www/askida-destek

# Uploads klasörü için özel izin
mkdir -p public/uploads
chmod -R 775 public/uploads

# Apache'yi yeniden başlat
echo "[4/5] Apache yeniden başlatılıyor..."
systemctl reload apache2

# Veritabanı şemasını yükle
echo "[5/5] Veritabanı şeması yükleniyor..."
sudo -u postgres psql -d askida_destek -f /var/www/askida-destek/database/schema.sql

echo ""
echo "=========================================="
echo "  ✅ Kurulum Tamamlandı!"
echo "=========================================="
echo ""
echo "Web sitesi: https://kampusportal.com.tr"
echo ""

