# Askıda-Destek Projesi - Gereksinim Dokümanı

## 📋 İçindekiler
1. [Proje Amacı](#proje-amacı)
2. [Taraflar ve Roller](#taraflar-ve-roller)
3. [İş Akışı](#iş-akışı)
4. [Sistem Mimarisi](#sistem-mimarisi)
5. [Modüller](#modüller)
6. [Veritabanı Tabloları](#veritabanı-tabloları)
7. [API Uç Noktaları](#api-uç-noktaları)
8. [Güvenlik Gereksinimleri](#güvenlik-gereksinimleri)
9. [Değerlendirme Metrikleri (KPI)](#değerlendirme-metrikleri-kpi)
10. [Kısaltmalar](#kısaltmalar)

---

## Proje Amacı

Bu proje, Konya pilotunda (Selçuk Üniversitesi Vakfı üzerinden) öğrencilerin gıdaya erişimini, tamamen mobil uygulama ve web arayüzü ile izlenebilir kupon/rezervasyon mantığında yönetmeyi amaçlar.

### Hedefler
- Pilot: Selçuk Üniversitesi Vakfı (Konya)
- Ölçekleme: Diğer üniversitelerin "tenant" olarak eklenmesi
- Kategori genişleme: Askıda giyim, kitap, etkinlik vb.

### Pilot Kurum
**Selçuk Üniversitesi Yaşatma ve Geliştirme Vakfı**
- Vakıflar Genel Müdürlüğü sorgulama ekranında kayıtlı tüzel kişilik

---

## Taraflar ve Roller

### 1. Bağışçı (Donor)
| Özellik | Açıklama |
|---------|----------|
| Tip | Bireysel veya kurumsal |
| Kanal | Web/mobil arayüz veya restoran içi QR |
| Hedefleme | Üniversite, şehir veya restoran bazlı |
| Bildirim | Anonim tüketim bildirimi alır |

### 2. Faydalanıcı (Beneficiary)

Faydalanıcılar üç ana kategoriye ayrılır:

#### 2.1 Üniversite Öğrencisi (University Student)
| Özellik | Açıklama |
|---------|----------|
| Doğrulama | SSO (OIDC/SAML) veya öğrenci belgesi |
| Kota | Günlük 3 öğün (tenant politikasına göre) |
| Rezervasyon | 30 dk + 10 dk uzatma hakkı |
| Kullanım | Dinamik QR/OTP ile restoranda |
| Ek Bilgi | Üniversite, fakülte, bölüm, sınıf |

#### 2.2 Lise Öğrencisi (High School Student)
| Özellik | Açıklama |
|---------|----------|
| Doğrulama | e-Devlet veya okul belgesi + veli onayı |
| Kota | Günlük 2 öğün (öğle + akşam) |
| Rezervasyon | 30 dk + 10 dk uzatma hakkı |
| Kullanım | Dinamik QR/OTP ile restoranda |
| Ek Bilgi | Okul, sınıf, veli iletişim |
| Kısıtlama | 18 yaş altı için veli onayı zorunlu |

#### 2.3 Diğer Faydalanıcılar (Other)
| Özellik | Açıklama |
|---------|----------|
| Doğrulama | Kurum referansı veya sosyal hizmet onayı |
| Kota | Tenant politikasına göre değişken |
| Rezervasyon | 30 dk + 10 dk uzatma hakkı |
| Kullanım | Dinamik QR/OTP ile restoranda |
| Ek Bilgi | Referans kurum, durum açıklaması |
| Örnek | Yetimhane, huzurevi, dar gelirli aileler |

### 3. Restoran (Restaurant/Partner)
| Özellik | Açıklama |
|---------|----------|
| Onboarding | KYC + sözleşme + IBAN |
| Tanımlama | Menü, kategori, birim fiyat, günlük kapasite |
| Doğrulama | QR/OTP okuma ve platform sorgusu |
| Ödeme | Hak ediş bazlı (doğrulanmış tüketim) |

### 4. Platform (Uygulama + Sunucu)
| Özellik | Açıklama |
|---------|----------|
| Kimlik | Doğrulama ve yetkilendirme |
| Bağış | Havuz yönetimi, tahsilat |
| Kupon | Üretim, doğrulama, TTL yönetimi |
| Hak ediş | Hesaplama, mutabakat, ödeme emri |

### 5. Selçuk Üniversitesi Vakfı (Tenant)
| Özellik | Açıklama |
|---------|----------|
| Rol | Pilot tenant ve program sahibi |
| Politika | Kota, restoran tipleri, bütçe tavanı |
| Muhasebe | Bağış/hak ediş rapor formatı |
| Denetim | Restoran seçim/denetim komisyonu |

---

## İş Akışı

### Süreç Adımları (Uçtan Uca)

```
1. KURULUM
   └── Selçuk Üniversitesi Vakfı "pilot tenant" olarak tanımlanır
   └── Konya il sınırı ve kampüs bölgeleri (geofence) girilir

2. RESTORAN ONBOARDING
   └── Restoran başvuru formu doldurur
   └── KYC + sözleşme + IBAN tanımı
   └── Menü/kategori ve birim fiyat tanımı
   └── Günlük kapasite bildirimi
   └── Platform incelemesi ve aktivasyon
   └── Vakıf onayı

3. BAĞIŞ KANALI-1 (HAVUZ)
   └── Bağışçı platformda bağış yapar
   └── Tutar "Konya–SÜV Havuzu"na yansır

4. BAĞIŞ KANALI-2 (RESTORAN İÇİ)
   └── Restoran afişindeki QR taranır
   └── Bağışçı platforma yönlendirilir
   └── Bağış platform üzerinden alınır (kasadan değil!)

5. ÖĞRENCİ DOĞRULAMA
   └── SSO veya belge ile doğrulama
   └── Günlük öğün kotası aktive edilir

6. REZERVASYON
   └── Öğrenci restoran/menü seçer
   └── 30 dk rezervasyon oluşturulur
   └── Opsiyonel: +10 dk uzatma
   └── Süre biterse rezervasyon düşer

7. TESLİM/HARCAMA (REDEMPTION)
   └── Öğrenci dinamik QR/OTP üretir
   └── Restoran kodu okur
   └── Platform doğrulama yapar
   └── Ücretsiz satış tamamlanır
   └── Kupon tek kullanımlık tüketilir

8. BİLDİRİM
   └── Bağışçıya anonim bildirim gönderilir
   └── İçerik: tarih-saat, şehir, kategori/menü tipi
   └── Kişisel veri içermez!

9. HAK EDİŞ
   └── Doğrulanmış tüketimler hesaplanır
   └── Vakıf onayı alınır
   └── Günlük/haftalık ödeme emri oluşturulur

10. İZLEME
    └── Anomali kuralları çalışır
    └── Otomatik inceleme kuyruğu
```

### Para Akışı

```
BAĞIŞÇI → SANAL POS → PLATFORM HAVUZ → [Tenant Alt Havuzları]
                                              ↓
                                    [Doğrulanmış Tüketim]
                                              ↓
                              VAKIF ONAYI → RESTORAN ÖDEMESİ
```

### Doğrulama Akışı

```
ÖĞRENCİ → Dinamik QR/OTP üretir
              ↓
RESTORAN → Kodu okur → Platform'a sorgular
              ↓
PLATFORM → Kuponu doğrular → Tek kullanımlık tüketir
              ↓
         Hak ediş kaydı oluşturur
```

---

## Sistem Mimarisi

### Multi-Tenant Yapı
- Her üniversite/vakıf ayrı "tenant"
- Merkezi platform ortak altyapı ve güvenlik sağlar
- Tenant'lar kendi politikalarını yönetir

### Modüller

| Modül | Açıklama |
|-------|----------|
| **Kimlik ve Yetki** | OIDC/OAuth2, RBAC, oturum, cihaz güveni |
| **Faydalanıcı** | Üniversite/Lise/Diğer doğrulama, kota, rezervasyon, kupon cüzdanı, değerlendirme |
| **Bağış** | Havuz, restoran içi, kampanya bazlı, makbuz/teşekkür |
| **İş Ortağı (Restoran)** | Şube, menü, kapasite, doğrulama ekranı, gün sonu raporu |
| **Muhasebe/Hak ediş** | İşlem kayıtları, komisyon, ödeme emirleri, mutabakat |
| **Analitik** | Talep-kapasite dengeleme, anomali, KPI panelleri |
| **Kategori genişleme** | "Destek Kalemi" soyutlaması (yemek/giyim/kitap/etkinlik) |

---

## Veritabanı Tabloları

### Çekirdek Tablolar

| Tablo | Amaç | Kritik Alanlar |
|-------|------|----------------|
| `tenants` | Üniversite/vakıf kiracısı | tenant_id, name, city, policy_set, budget |
| `users` | Tüm kullanıcılar | user_id, tenant_id, role, email, password_hash |
| `beneficiaries` | Faydalanıcı profili | beneficiary_id, user_id, tenant_id, type, verification_status, daily_quota |
| `beneficiary_university` | Üniversite öğrenci detayı | id, beneficiary_id, university, faculty, department, grade, student_no |
| `beneficiary_highschool` | Lise öğrenci detayı | id, beneficiary_id, school, grade, parent_name, parent_phone, parent_consent |
| `beneficiary_other` | Diğer faydalanıcı detayı | id, beneficiary_id, reference_org, status_description, social_worker_id |
| `restaurants` | Restoran/şube | restaurant_id, tenant_id, kyc_status, iban, daily_capacity |
| `menu_items` | Menü/ürün | item_id, restaurant_id, category, name, price, is_available |
| `reservations` | Rezervasyon | reservation_id, student_id, item_id, expires_at, extended, status |
| `vouchers` | Tek kullanımlık kupon | voucher_id, reservation_id, code, ttl, status, signature |
| `donations` | Bağış kayıtları | donation_id, donor_id, tenant_id, restaurant_id, amount, campaign |
| `transactions` | Finansal işlem | tx_id, type, amount, from_account, to_account, timestamp |
| `settlements` | Restoran hak edişi | settlement_id, restaurant_id, period, total, approval_status |
| `ratings` | Memnuniyet | rating_id, student_id, restaurant_id, score, has_comment |
| `audit_logs` | Denetim izi | log_id, user_id, action, entity, old_value, new_value, ip, timestamp |

### Tablo İlişkileri

```
tenants (1) ──── (N) users
tenants (1) ──── (N) beneficiaries
tenants (1) ──── (N) restaurants
tenants (1) ──── (N) donations

users (1) ──── (1) beneficiaries

beneficiaries (1) ──── (0..1) beneficiary_university
beneficiaries (1) ──── (0..1) beneficiary_highschool
beneficiaries (1) ──── (0..1) beneficiary_other

restaurants (1) ──── (N) menu_items
beneficiaries (1) ──── (N) reservations
reservations (1) ──── (1) vouchers

restaurants (1) ──── (N) settlements
beneficiaries (1) ──── (N) ratings
restaurants (1) ──── (N) ratings
```

---

## API Uç Noktaları

### Kimlik (Auth)
```
POST   /api/v1/auth/register      # Kayıt
POST   /api/v1/auth/login         # Giriş
POST   /api/v1/auth/refresh       # Token yenileme
POST   /api/v1/auth/logout        # Çıkış
GET    /api/v1/auth/me            # Profil bilgisi
```

### Faydalanıcı (Beneficiary)
```
# Genel
POST   /api/v1/beneficiaries/register       # Kayıt başvurusu
GET    /api/v1/beneficiaries/me             # Profil bilgisi
GET    /api/v1/beneficiaries/quota          # Kota sorgulama
GET    /api/v1/beneficiaries/reservations   # Rezervasyonlarım
GET    /api/v1/beneficiaries/history        # Kullanım geçmişi

# Üniversite Öğrencisi
POST   /api/v1/beneficiaries/university/verify     # SSO/belge doğrulama
PUT    /api/v1/beneficiaries/university/update     # Bilgi güncelleme

# Lise Öğrencisi
POST   /api/v1/beneficiaries/highschool/verify     # e-Devlet/belge doğrulama
POST   /api/v1/beneficiaries/highschool/parent-consent  # Veli onayı

# Diğer
POST   /api/v1/beneficiaries/other/verify          # Kurum referansı doğrulama
POST   /api/v1/beneficiaries/other/social-approval # Sosyal hizmet onayı
```

### Restoran (Restaurant)
```
GET    /api/v1/restaurants                  # Restoran listesi
GET    /api/v1/restaurants/nearby           # Yakındaki restoranlar
GET    /api/v1/restaurants/{id}             # Restoran detayı
GET    /api/v1/restaurants/{id}/menu        # Menü listesi
```

### Rezervasyon (Reservation)
```
POST   /api/v1/reservations                 # Rezervasyon oluştur
GET    /api/v1/reservations/{id}            # Rezervasyon detayı
POST   /api/v1/reservations/{id}/extend     # Süre uzat (+10 dk)
DELETE /api/v1/reservations/{id}            # İptal
```

### Kupon (Voucher)
```
POST   /api/v1/vouchers/generate            # QR/OTP üret
POST   /api/v1/vouchers/redeem              # Kupon kullan (restoran)
GET    /api/v1/vouchers/{id}/verify         # Doğrulama (restoran)
```

### Bağış (Donation)
```
POST   /api/v1/donations                    # Bağış yap
GET    /api/v1/donations/{id}               # Bağış detayı
GET    /api/v1/donations/{id}/receipt       # Makbuz
GET    /api/v1/donations/history            # Bağış geçmişi
```

### İş Ortağı (Partner/Restaurant Panel)
```
POST   /api/v1/partners/onboard             # Başvuru
PUT    /api/v1/partners/{id}/menu           # Menü güncelle
PUT    /api/v1/partners/{id}/capacity       # Kapasite güncelle
GET    /api/v1/partners/{id}/daily-report   # Gün sonu raporu
GET    /api/v1/partners/{id}/settlements    # Hak edişler
```

### Hak Ediş (Settlement)
```
POST   /api/v1/settlements/calculate        # Hesapla
GET    /api/v1/settlements/{id}             # Detay
POST   /api/v1/settlements/{id}/approve     # Onayla (Vakıf)
POST   /api/v1/settlements/{id}/pay         # Ödeme emri
```

### Yönetim (Admin)
```
GET    /api/v1/admin/dashboard              # Dashboard
GET    /api/v1/admin/users                  # Kullanıcı listesi
GET    /api/v1/admin/restaurants            # Restoran listesi
GET    /api/v1/admin/reports                # Raporlar
GET    /api/v1/admin/anomalies              # Anomali listesi
```

---

## Güvenlik Gereksinimleri

### Temel Prensipler

| Prensip | Uygulama |
|---------|----------|
| **KVKK Uyumu** | Amaç sınırlılık, minimum veri, belirli süre |
| **PCI DSS** | Kart verisi platform tarafından tutulmaz |
| **Para Akışı** | Restoran kasasından para geçmez |
| **Kupon Güvenliği** | Dinamik, TTL'li, imzalı, tek kullanımlık |

### Kontrol Listesi

- [ ] **Bağış Tahsilatı**: Tüm ödemeler platform ödeme altyapısı üzerinden
- [ ] **Tek Kullanımlık Kupon**: Statik QR yerine dinamik QR/OTP + TTL + imza
- [ ] **Hak Ediş Kuralı**: Ödeme sadece doğrulanmış tüketim kayıtlarına göre
- [ ] **RBAC**: Rol tabanlı yetkilendirme
- [ ] **4-Eyes Prensibi**: Kritik işlemlerde iki aşamalı onay
- [ ] **Güçlü Kimlik Doğrulama**: 2FA desteği
- [ ] **Audit Log**: Değişmez denetim izi (zaman, kullanıcı, IP, önce-sonra)
- [ ] **Anomali Tespiti**: Otomatik inceleme kuralları

### Anomali Kuralları

| Kural | Açıklama |
|-------|----------|
| Olağandışı hacim | Şube bazında beklenmedik işlem sayısı |
| Seri tüketim | Kısa sürede art arda kupon kullanımı |
| Cihaz yoğunluğu | Aynı cihazdan çok sayıda işlem |
| Coğrafi tutarsızlık | Fiziksel olarak imkansız konum değişimleri |
| Zaman anomalisi | Çalışma saatleri dışında işlem |

---

## Değerlendirme Metrikleri (KPI)

### Erişim Metrikleri
- Günlük aktif öğrenci sayısı
- Aktif restoran/şube sayısı
- Aktif bağışçı sayısı

### Etki Metrikleri
- Doğrulanmış öğün sayısı (kahvaltı/öğle/akşam)
- Kupon tüketim oranı (%)
- No-show oranı (%)

### Kalite Metrikleri
- Ortalama memnuniyet puanı (1-5)
- Şikâyet oranı (%)
- Şube bazlı iptal/uyumsuzluk

### Finans Metrikleri
- Havuz kullanım hızı (burn rate)
- İşlem başı operasyon maliyeti
- Hak ediş gecikme süresi (gün)

### Güvenlik Metrikleri
- Anomali olay sayısı
- Doğrulama hatası oranı
- Denetimde bulunan uygunsuzluk

---

## Kısaltmalar

| Kısaltma | Açıklama |
|----------|----------|
| **KVKK** | 6698 sayılı Kişisel Verilerin Korunması Kanunu |
| **PCI DSS** | Payment Card Industry Data Security Standard |
| **KYC** | Know Your Customer (iş ortağı kimlik doğrulama) |
| **RBAC** | Role-Based Access Control (rol tabanlı yetkilendirme) |
| **SSO** | Single Sign-On (tek oturum açma) |
| **OIDC** | OpenID Connect (kimlik doğrulama protokolü) |
| **OAuth2** | Yetkilendirme protokolü |
| **OTP** | One-Time Password (tek kullanımlık şifre) |
| **TTL** | Time To Live (geçerlilik süresi) |
| **SLA** | Service Level Agreement (hizmet seviyesi taahhüdü) |
| **2FA** | Two-Factor Authentication (iki faktörlü doğrulama) |
| **DPA** | Data Processing Agreement (veri işleme sözleşmesi) |

---

## Selçuk Üniversitesi Vakfı Entegrasyonu

### Gereksinimler

| Alan | Gereksinim |
|------|------------|
| **Yönetişim** | Program politikası (kota, restoran türleri, bütçe tavanı, yaptırım) |
| **Öğrenci Doğrulama** | SSO (OIDC/SAML) veya öğrenci no + dönemlik doğrulama |
| **Muhasebe** | Bağış/hak ediş rapor formatı mutabakatı |
| **Hukuk/KVKK** | Aydınlatma metni, açık rıza, veri sorumlusu rolleri |
| **Operasyon** | Restoran seçim/denetim komisyonu, SLA |

---

## MVP Kapsamı (Konya Pilot)

### Dahil
- ✅ Selçuk Üniversitesi Vakfı (tek tenant)
- ✅ Sınırlı sayıda restoran
- ✅ Tek kategori (yemek)
- ✅ Temel güvenlik
- ✅ Hak ediş sistemi

### Hariç (Sonraki Fazlar)
- ❌ Çoklu üniversite
- ❌ Giyim/kitap/etkinlik kategorileri
- ❌ Gelişmiş analitik
- ❌ Mobil uygulama (ilk fazda web)

---

## Bağlantı Bilgileri

### Sunucu
- **IP**: 159.89.145.26
- **Web**: http://159.89.145.26

### PostgreSQL
- **Host**: 159.89.145.26
- **Port**: 5432
- **Database**: askida_destek
- **User**: askida_admin
- **Password**: AskidaDestek2024Secure

---

*Son güncelleme: Ocak 2026*

