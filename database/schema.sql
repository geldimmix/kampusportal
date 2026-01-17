-- =====================================================
-- ASKIDA-DESTEK PROJESİ - VERİTABANI ŞEMASI
-- PostgreSQL 16+
-- Multi-Tenant Yapı
-- =====================================================

-- =====================================================
-- TEMİZLİK (Yeniden çalıştırılabilirlik için)
-- =====================================================

-- Tüm tabloları ve tipleri temizle
DROP SCHEMA IF EXISTS public CASCADE;
CREATE SCHEMA public;
GRANT ALL ON SCHEMA public TO askida_admin;
GRANT ALL ON SCHEMA public TO public;

-- Uzantılar
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- =====================================================
-- ENUM TÜRLERİ
-- =====================================================

-- Üniversite türü
CREATE TYPE university_type AS ENUM ('devlet', 'vakif', 'vakif_myo');

-- Kullanıcı rolleri
CREATE TYPE user_role AS ENUM (
    'super_admin',          -- Sistem yöneticisi (tüm yetkiler)
    'university_admin',     -- Üniversite yöneticisi
    'foundation_admin',     -- Vakıf yöneticisi
    'foundation_staff',     -- Vakıf personeli
    'cafeteria_manager',    -- Yemekhane müdürü
    'cafeteria_staff',      -- Yemekhane personeli
    'restaurant_owner',     -- Restoran sahibi
    'restaurant_staff',     -- Restoran personeli
    'donor',                -- Bağışçı
    'beneficiary'           -- Faydalanıcı (öğrenci vb.)
);

-- Faydalanıcı türü
CREATE TYPE beneficiary_type AS ENUM ('university', 'highschool', 'other');

-- Doğrulama durumu
CREATE TYPE verification_status AS ENUM ('pending', 'verified', 'rejected', 'expired');

-- Rezervasyon durumu
CREATE TYPE reservation_status AS ENUM ('active', 'extended', 'used', 'expired', 'cancelled');

-- Kupon durumu
CREATE TYPE voucher_status AS ENUM ('active', 'used', 'expired', 'cancelled');

-- İşlem türü
CREATE TYPE transaction_type AS ENUM ('donation', 'redemption', 'settlement', 'refund', 'transfer');

-- Hak ediş durumu
CREATE TYPE settlement_status AS ENUM ('pending', 'calculated', 'approved', 'paid', 'disputed');

-- Öğün türü
CREATE TYPE meal_type AS ENUM ('breakfast', 'lunch', 'dinner', 'snack');

-- Destek kategorisi
CREATE TYPE support_category AS ENUM ('food', 'clothing', 'book', 'event', 'other');

-- =====================================================
-- 1. ÜNİVERSİTELER TABLOSU
-- =====================================================

CREATE TABLE universities (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    name VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    type university_type NOT NULL DEFAULT 'devlet',
    founded_year INTEGER,
    rector_name VARCHAR(255),
    is_active BOOLEAN DEFAULT false,  -- Sistemde aktif mi?
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_universities_city ON universities(city);
CREATE INDEX idx_universities_type ON universities(type);
CREATE INDEX idx_universities_active ON universities(is_active);

-- =====================================================
-- 2. TENANT (KİRACI) TABLOSU - Vakıflar
-- =====================================================

CREATE TABLE tenants (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    university_id UUID REFERENCES universities(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,  -- Örn: "Selçuk Üniversitesi Yaşatma ve Geliştirme Vakfı"
    tax_number VARCHAR(20),
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(255),
    website VARCHAR(255),
    logo_url VARCHAR(500),
    
    -- Bütçe ve Politika
    total_budget DECIMAL(15,2) DEFAULT 0,
    available_budget DECIMAL(15,2) DEFAULT 0,
    daily_quota_per_beneficiary INTEGER DEFAULT 3,
    max_meal_price DECIMAL(10,2) DEFAULT 100.00,
    
    -- Ayarlar
    settings JSONB DEFAULT '{}',
    is_active BOOLEAN DEFAULT true,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    UNIQUE(university_id)
);

CREATE INDEX idx_tenants_university ON tenants(university_id);
CREATE INDEX idx_tenants_active ON tenants(is_active);

-- =====================================================
-- 3. KULLANICILAR TABLOSU
-- =====================================================

CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    tenant_id UUID REFERENCES tenants(id) ON DELETE SET NULL,
    
    -- Kimlik Bilgileri
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    
    -- Profil
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    avatar_url VARCHAR(500),
    
    -- Rol ve Yetki
    role user_role NOT NULL DEFAULT 'beneficiary',
    permissions JSONB DEFAULT '[]',  -- Ek yetkiler
    
    -- Güvenlik
    email_verified BOOLEAN DEFAULT false,
    phone_verified BOOLEAN DEFAULT false,
    two_factor_enabled BOOLEAN DEFAULT false,
    two_factor_secret VARCHAR(255),
    
    -- Oturum
    last_login_at TIMESTAMP WITH TIME ZONE,
    last_login_ip VARCHAR(45),
    failed_login_attempts INTEGER DEFAULT 0,
    locked_until TIMESTAMP WITH TIME ZONE,
    
    -- Durum
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_tenant ON users(tenant_id);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_users_active ON users(is_active);

-- =====================================================
-- 4. ROL YETKİLERİ TABLOSU
-- =====================================================

CREATE TABLE role_permissions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    role user_role NOT NULL,
    permission VARCHAR(100) NOT NULL,  -- Örn: 'donations.view', 'settlements.approve'
    description TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    UNIQUE(role, permission)
);

-- Varsayılan yetkileri ekle
INSERT INTO role_permissions (role, permission, description) VALUES
-- Super Admin (tüm yetkiler)
('super_admin', '*', 'Tüm yetkiler'),

-- University Admin
('university_admin', 'university.manage', 'Üniversite yönetimi'),
('university_admin', 'tenants.view', 'Vakıfları görüntüleme'),

-- Foundation Admin
('foundation_admin', 'tenant.manage', 'Vakıf yönetimi'),
('foundation_admin', 'users.manage', 'Kullanıcı yönetimi'),
('foundation_admin', 'donations.view', 'Bağışları görüntüleme'),
('foundation_admin', 'donations.manage', 'Bağış yönetimi'),
('foundation_admin', 'beneficiaries.manage', 'Faydalanıcı yönetimi'),
('foundation_admin', 'restaurants.manage', 'Restoran yönetimi'),
('foundation_admin', 'cafeterias.manage', 'Yemekhane yönetimi'),
('foundation_admin', 'settlements.view', 'Hak edişleri görüntüleme'),
('foundation_admin', 'settlements.approve', 'Hak ediş onaylama'),
('foundation_admin', 'reports.view', 'Raporları görüntüleme'),
('foundation_admin', 'policies.manage', 'Politika yönetimi'),

-- Foundation Staff
('foundation_staff', 'donations.view', 'Bağışları görüntüleme'),
('foundation_staff', 'beneficiaries.view', 'Faydalanıcıları görüntüleme'),
('foundation_staff', 'beneficiaries.verify', 'Faydalanıcı doğrulama'),
('foundation_staff', 'restaurants.view', 'Restoranları görüntüleme'),
('foundation_staff', 'reports.view', 'Raporları görüntüleme'),

-- Cafeteria Manager
('cafeteria_manager', 'cafeteria.manage', 'Yemekhane yönetimi'),
('cafeteria_manager', 'menu.manage', 'Menü yönetimi'),
('cafeteria_manager', 'capacity.manage', 'Kapasite yönetimi'),
('cafeteria_manager', 'vouchers.redeem', 'Kupon onaylama'),
('cafeteria_manager', 'reports.view', 'Raporları görüntüleme'),

-- Cafeteria Staff
('cafeteria_staff', 'vouchers.redeem', 'Kupon onaylama'),
('cafeteria_staff', 'menu.view', 'Menüyü görüntüleme'),

-- Restaurant Owner
('restaurant_owner', 'restaurant.manage', 'Restoran yönetimi'),
('restaurant_owner', 'menu.manage', 'Menü yönetimi'),
('restaurant_owner', 'capacity.manage', 'Kapasite yönetimi'),
('restaurant_owner', 'vouchers.redeem', 'Kupon onaylama'),
('restaurant_owner', 'settlements.view', 'Hak edişleri görüntüleme'),
('restaurant_owner', 'reports.view', 'Raporları görüntüleme'),

-- Restaurant Staff
('restaurant_staff', 'vouchers.redeem', 'Kupon onaylama'),
('restaurant_staff', 'menu.view', 'Menüyü görüntüleme'),

-- Donor
('donor', 'donations.create', 'Bağış yapma'),
('donor', 'donations.view_own', 'Kendi bağışlarını görüntüleme'),
('donor', 'profile.manage', 'Profil yönetimi'),

-- Beneficiary
('beneficiary', 'reservations.create', 'Rezervasyon oluşturma'),
('beneficiary', 'reservations.view_own', 'Kendi rezervasyonlarını görüntüleme'),
('beneficiary', 'vouchers.generate', 'Kupon oluşturma'),
('beneficiary', 'restaurants.view', 'Restoranları görüntüleme'),
('beneficiary', 'profile.manage', 'Profil yönetimi'),
('beneficiary', 'ratings.create', 'Değerlendirme yapma');

-- =====================================================
-- 5. FAYDALANICI (BENEFİCİARY) TABLOLARI
-- =====================================================

-- Ana faydalanıcı tablosu
CREATE TABLE beneficiaries (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    tenant_id UUID NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
    
    type beneficiary_type NOT NULL,
    verification_status verification_status DEFAULT 'pending',
    verified_at TIMESTAMP WITH TIME ZONE,
    verified_by UUID REFERENCES users(id),
    
    -- Kota
    daily_quota INTEGER DEFAULT 3,
    used_quota_today INTEGER DEFAULT 0,
    quota_reset_at DATE DEFAULT CURRENT_DATE,
    
    -- İstatistik
    total_meals_received INTEGER DEFAULT 0,
    
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    UNIQUE(user_id)
);

CREATE INDEX idx_beneficiaries_user ON beneficiaries(user_id);
CREATE INDEX idx_beneficiaries_tenant ON beneficiaries(tenant_id);
CREATE INDEX idx_beneficiaries_type ON beneficiaries(type);
CREATE INDEX idx_beneficiaries_status ON beneficiaries(verification_status);

-- Üniversite öğrencisi detayları
CREATE TABLE beneficiary_university (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    beneficiary_id UUID NOT NULL REFERENCES beneficiaries(id) ON DELETE CASCADE,
    
    university_id UUID REFERENCES universities(id),
    student_number VARCHAR(50),
    faculty VARCHAR(255),
    department VARCHAR(255),
    grade INTEGER,  -- 1, 2, 3, 4, 5 (yüksek lisans), 6 (doktora)
    enrollment_year INTEGER,
    expected_graduation INTEGER,
    
    -- SSO bilgileri
    sso_id VARCHAR(255),
    sso_provider VARCHAR(100),
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    UNIQUE(beneficiary_id),
    UNIQUE(university_id, student_number)
);

-- Lise öğrencisi detayları
CREATE TABLE beneficiary_highschool (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    beneficiary_id UUID NOT NULL REFERENCES beneficiaries(id) ON DELETE CASCADE,
    
    school_name VARCHAR(255) NOT NULL,
    school_city VARCHAR(100),
    grade INTEGER,  -- 9, 10, 11, 12
    
    -- Veli bilgileri
    parent_name VARCHAR(255),
    parent_phone VARCHAR(20),
    parent_email VARCHAR(255),
    parent_tc_no VARCHAR(11),
    parent_consent BOOLEAN DEFAULT false,
    parent_consent_date TIMESTAMP WITH TIME ZONE,
    
    -- e-Devlet doğrulama
    e_devlet_verified BOOLEAN DEFAULT false,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    UNIQUE(beneficiary_id)
);

-- Diğer faydalanıcı detayları
CREATE TABLE beneficiary_other (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    beneficiary_id UUID NOT NULL REFERENCES beneficiaries(id) ON DELETE CASCADE,
    
    reference_organization VARCHAR(255),  -- Referans kurum
    reference_contact VARCHAR(255),       -- Kurum iletişim
    status_description TEXT,              -- Durum açıklaması
    
    -- Sosyal hizmet onayı
    social_worker_name VARCHAR(255),
    social_worker_approval BOOLEAN DEFAULT false,
    social_worker_approval_date TIMESTAMP WITH TIME ZONE,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    UNIQUE(beneficiary_id)
);

-- =====================================================
-- 6. BAĞIŞÇI TABLOSU
-- =====================================================

CREATE TABLE donors (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    
    -- Tür
    is_corporate BOOLEAN DEFAULT false,
    company_name VARCHAR(255),
    tax_number VARCHAR(20),
    
    -- İstatistik
    total_donated DECIMAL(15,2) DEFAULT 0,
    total_donations_count INTEGER DEFAULT 0,
    
    -- Tercihler
    anonymous_by_default BOOLEAN DEFAULT false,
    receive_notifications BOOLEAN DEFAULT true,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    UNIQUE(user_id)
);

-- =====================================================
-- 7. YEMEKHANE TABLOSU (Üniversite Yemekhaneleri)
-- =====================================================

CREATE TABLE cafeterias (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    tenant_id UUID NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
    university_id UUID REFERENCES universities(id),
    
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255),  -- Kampüs içi konum
    
    -- Kapasite
    daily_capacity INTEGER DEFAULT 500,
    current_available INTEGER DEFAULT 500,
    
    -- Çalışma saatleri
    breakfast_start TIME,
    breakfast_end TIME,
    lunch_start TIME,
    lunch_end TIME,
    dinner_start TIME,
    dinner_end TIME,
    
    -- Konum
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    
    -- Durum
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_cafeterias_tenant ON cafeterias(tenant_id);
CREATE INDEX idx_cafeterias_university ON cafeterias(university_id);

-- =====================================================
-- 8. RESTORAN TABLOSU (Harici Restoranlar)
-- =====================================================

CREATE TABLE restaurants (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    tenant_id UUID NOT NULL REFERENCES tenants(id) ON DELETE CASCADE,
    owner_id UUID REFERENCES users(id),
    
    -- Temel bilgiler
    name VARCHAR(255) NOT NULL,
    description TEXT,
    
    -- KYC
    tax_number VARCHAR(20),
    trade_name VARCHAR(255),
    kyc_status verification_status DEFAULT 'pending',
    kyc_verified_at TIMESTAMP WITH TIME ZONE,
    kyc_documents JSONB DEFAULT '[]',
    
    -- Ödeme
    iban VARCHAR(34),
    bank_name VARCHAR(100),
    account_holder VARCHAR(255),
    
    -- Adres
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    district VARCHAR(100),
    postal_code VARCHAR(10),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    
    -- İletişim
    phone VARCHAR(20),
    email VARCHAR(255),
    
    -- Kapasite
    daily_capacity INTEGER DEFAULT 100,
    current_available INTEGER DEFAULT 100,
    
    -- Çalışma saatleri
    working_hours JSONB DEFAULT '{}',
    
    -- Sözleşme
    contract_start DATE,
    contract_end DATE,
    commission_rate DECIMAL(5,2) DEFAULT 0,  -- Yüzde
    
    -- İstatistik
    total_meals_served INTEGER DEFAULT 0,
    average_rating DECIMAL(3,2) DEFAULT 0,
    total_ratings INTEGER DEFAULT 0,
    
    -- Durum
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_restaurants_tenant ON restaurants(tenant_id);
CREATE INDEX idx_restaurants_city ON restaurants(city);
CREATE INDEX idx_restaurants_kyc ON restaurants(kyc_status);
CREATE INDEX idx_restaurants_active ON restaurants(is_active);
CREATE INDEX idx_restaurants_location ON restaurants USING GIST (
    point(longitude, latitude)
);

-- =====================================================
-- 9. MENÜ ÖĞELERİ TABLOSU
-- =====================================================

CREATE TABLE menu_items (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    
    -- Yemekhane veya restoran
    cafeteria_id UUID REFERENCES cafeterias(id) ON DELETE CASCADE,
    restaurant_id UUID REFERENCES restaurants(id) ON DELETE CASCADE,
    
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category support_category DEFAULT 'food',
    meal_type meal_type,
    
    price DECIMAL(10,2) NOT NULL,
    
    -- Görsel
    image_url VARCHAR(500),
    
    -- Besin değerleri (opsiyonel)
    calories INTEGER,
    allergens JSONB DEFAULT '[]',
    
    -- Stok
    daily_stock INTEGER,
    current_stock INTEGER,
    
    is_available BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    -- Sadece biri olabilir
    CONSTRAINT chk_menu_item_owner CHECK (
        (cafeteria_id IS NOT NULL AND restaurant_id IS NULL) OR
        (cafeteria_id IS NULL AND restaurant_id IS NOT NULL)
    )
);

CREATE INDEX idx_menu_items_cafeteria ON menu_items(cafeteria_id);
CREATE INDEX idx_menu_items_restaurant ON menu_items(restaurant_id);
CREATE INDEX idx_menu_items_category ON menu_items(category);
CREATE INDEX idx_menu_items_available ON menu_items(is_available);

-- =====================================================
-- 10. BAĞIŞ TABLOSU
-- =====================================================

CREATE TABLE donations (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    donor_id UUID REFERENCES donors(id),
    tenant_id UUID NOT NULL REFERENCES tenants(id),
    
    -- Hedefleme (opsiyonel)
    target_restaurant_id UUID REFERENCES restaurants(id),
    target_cafeteria_id UUID REFERENCES cafeterias(id),
    target_category support_category,
    
    -- Tutar
    amount DECIMAL(15,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'TRY',
    
    -- Kullanım durumu
    used_amount DECIMAL(15,2) DEFAULT 0,
    remaining_amount DECIMAL(15,2),
    
    -- Ödeme
    payment_method VARCHAR(50),
    payment_reference VARCHAR(255),
    payment_status VARCHAR(50) DEFAULT 'pending',
    
    -- Anonim
    is_anonymous BOOLEAN DEFAULT false,
    
    -- Kampanya
    campaign_code VARCHAR(50),
    
    -- Makbuz
    receipt_number VARCHAR(50),
    receipt_url VARCHAR(500),
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_donations_donor ON donations(donor_id);
CREATE INDEX idx_donations_tenant ON donations(tenant_id);
CREATE INDEX idx_donations_status ON donations(payment_status);
CREATE INDEX idx_donations_date ON donations(created_at);

-- remaining_amount otomatik hesaplama
CREATE OR REPLACE FUNCTION calculate_remaining_donation()
RETURNS TRIGGER AS $$
BEGIN
    NEW.remaining_amount := NEW.amount - COALESCE(NEW.used_amount, 0);
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_donation_remaining
    BEFORE INSERT OR UPDATE ON donations
    FOR EACH ROW
    EXECUTE FUNCTION calculate_remaining_donation();

-- =====================================================
-- 11. REZERVASYON TABLOSU
-- =====================================================

CREATE TABLE reservations (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    beneficiary_id UUID NOT NULL REFERENCES beneficiaries(id),
    
    -- Hedef
    cafeteria_id UUID REFERENCES cafeterias(id),
    restaurant_id UUID REFERENCES restaurants(id),
    menu_item_id UUID REFERENCES menu_items(id),
    
    -- Zaman
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    expires_at TIMESTAMP WITH TIME ZONE NOT NULL,
    extended BOOLEAN DEFAULT false,
    extended_at TIMESTAMP WITH TIME ZONE,
    used_at TIMESTAMP WITH TIME ZONE,
    
    -- Durum
    status reservation_status DEFAULT 'active',
    
    -- Fiyat (o anki)
    price_at_reservation DECIMAL(10,2),
    
    -- Sadece biri olabilir
    CONSTRAINT chk_reservation_location CHECK (
        (cafeteria_id IS NOT NULL AND restaurant_id IS NULL) OR
        (cafeteria_id IS NULL AND restaurant_id IS NOT NULL)
    )
);

CREATE INDEX idx_reservations_beneficiary ON reservations(beneficiary_id);
CREATE INDEX idx_reservations_cafeteria ON reservations(cafeteria_id);
CREATE INDEX idx_reservations_restaurant ON reservations(restaurant_id);
CREATE INDEX idx_reservations_status ON reservations(status);
CREATE INDEX idx_reservations_expires ON reservations(expires_at);

-- =====================================================
-- 12. KUPON (VOUCHER) TABLOSU
-- =====================================================

CREATE TABLE vouchers (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    reservation_id UUID NOT NULL REFERENCES reservations(id) ON DELETE CASCADE,
    
    -- Kod
    code VARCHAR(20) NOT NULL UNIQUE,
    qr_data TEXT,  -- QR için şifreli veri
    otp_code VARCHAR(6),
    
    -- Güvenlik
    signature VARCHAR(255),  -- HMAC imza
    
    -- Süre
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    expires_at TIMESTAMP WITH TIME ZONE NOT NULL,
    used_at TIMESTAMP WITH TIME ZONE,
    
    -- Kullanım
    redeemed_by UUID REFERENCES users(id),  -- Personel
    redeemed_at_location VARCHAR(255),
    
    status voucher_status DEFAULT 'active'
);

CREATE INDEX idx_vouchers_reservation ON vouchers(reservation_id);
CREATE INDEX idx_vouchers_code ON vouchers(code);
CREATE INDEX idx_vouchers_status ON vouchers(status);
CREATE INDEX idx_vouchers_expires ON vouchers(expires_at);

-- =====================================================
-- 13. HAK EDİŞ (SETTLEMENT) TABLOSU
-- =====================================================

CREATE TABLE settlements (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    tenant_id UUID NOT NULL REFERENCES tenants(id),
    
    -- Hedef
    cafeteria_id UUID REFERENCES cafeterias(id),
    restaurant_id UUID REFERENCES restaurants(id),
    
    -- Dönem
    period_start DATE NOT NULL,
    period_end DATE NOT NULL,
    
    -- Tutarlar
    gross_amount DECIMAL(15,2) NOT NULL,
    commission_amount DECIMAL(15,2) DEFAULT 0,
    net_amount DECIMAL(15,2) NOT NULL,
    
    -- Detay
    total_meals INTEGER NOT NULL,
    details JSONB DEFAULT '{}',
    
    -- Onay süreci
    status settlement_status DEFAULT 'pending',
    calculated_at TIMESTAMP WITH TIME ZONE,
    calculated_by UUID REFERENCES users(id),
    approved_at TIMESTAMP WITH TIME ZONE,
    approved_by UUID REFERENCES users(id),
    paid_at TIMESTAMP WITH TIME ZONE,
    
    -- Ödeme
    payment_reference VARCHAR(255),
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    CONSTRAINT chk_settlement_location CHECK (
        (cafeteria_id IS NOT NULL AND restaurant_id IS NULL) OR
        (cafeteria_id IS NULL AND restaurant_id IS NOT NULL)
    )
);

CREATE INDEX idx_settlements_tenant ON settlements(tenant_id);
CREATE INDEX idx_settlements_cafeteria ON settlements(cafeteria_id);
CREATE INDEX idx_settlements_restaurant ON settlements(restaurant_id);
CREATE INDEX idx_settlements_status ON settlements(status);
CREATE INDEX idx_settlements_period ON settlements(period_start, period_end);

-- =====================================================
-- 14. İŞLEM (TRANSACTION) TABLOSU
-- =====================================================

CREATE TABLE transactions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    tenant_id UUID NOT NULL REFERENCES tenants(id),
    
    type transaction_type NOT NULL,
    
    -- İlişkili kayıtlar
    donation_id UUID REFERENCES donations(id),
    voucher_id UUID REFERENCES vouchers(id),
    settlement_id UUID REFERENCES settlements(id),
    
    -- Tutar
    amount DECIMAL(15,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'TRY',
    
    -- Açıklama
    description TEXT,
    
    -- Meta
    metadata JSONB DEFAULT '{}',
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_transactions_tenant ON transactions(tenant_id);
CREATE INDEX idx_transactions_type ON transactions(type);
CREATE INDEX idx_transactions_date ON transactions(created_at);

-- =====================================================
-- 15. DEĞERLENDİRME (RATING) TABLOSU
-- =====================================================

CREATE TABLE ratings (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    beneficiary_id UUID NOT NULL REFERENCES beneficiaries(id),
    voucher_id UUID REFERENCES vouchers(id),
    
    cafeteria_id UUID REFERENCES cafeterias(id),
    restaurant_id UUID REFERENCES restaurants(id),
    
    score INTEGER NOT NULL CHECK (score >= 1 AND score <= 5),
    comment TEXT,
    has_comment BOOLEAN DEFAULT false,
    
    -- Yanıt
    response TEXT,
    responded_at TIMESTAMP WITH TIME ZONE,
    responded_by UUID REFERENCES users(id),
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    
    CONSTRAINT chk_rating_location CHECK (
        (cafeteria_id IS NOT NULL AND restaurant_id IS NULL) OR
        (cafeteria_id IS NULL AND restaurant_id IS NOT NULL)
    )
);

CREATE INDEX idx_ratings_beneficiary ON ratings(beneficiary_id);
CREATE INDEX idx_ratings_cafeteria ON ratings(cafeteria_id);
CREATE INDEX idx_ratings_restaurant ON ratings(restaurant_id);

-- =====================================================
-- 16. DENETİM İZİ (AUDIT LOG) TABLOSU
-- =====================================================

CREATE TABLE audit_logs (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    tenant_id UUID REFERENCES tenants(id),
    user_id UUID REFERENCES users(id),
    
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100),
    entity_id UUID,
    
    old_values JSONB,
    new_values JSONB,
    
    ip_address VARCHAR(45),
    user_agent TEXT,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_audit_logs_tenant ON audit_logs(tenant_id);
CREATE INDEX idx_audit_logs_user ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_action ON audit_logs(action);
CREATE INDEX idx_audit_logs_entity ON audit_logs(entity_type, entity_id);
CREATE INDEX idx_audit_logs_date ON audit_logs(created_at);

-- =====================================================
-- 17. BİLDİRİM TABLOSU
-- =====================================================

CREATE TABLE notifications (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50),  -- donation_used, reservation_reminder, etc.
    
    data JSONB DEFAULT '{}',
    
    read BOOLEAN DEFAULT false,
    read_at TIMESTAMP WITH TIME ZONE,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_notifications_user ON notifications(user_id);
CREATE INDEX idx_notifications_read ON notifications(read);
CREATE INDEX idx_notifications_date ON notifications(created_at);

-- =====================================================
-- 18. OTURUM TABLOSU
-- =====================================================

CREATE TABLE sessions (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    user_id UUID NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    
    token_hash VARCHAR(255) NOT NULL,
    refresh_token_hash VARCHAR(255),
    
    ip_address VARCHAR(45),
    user_agent TEXT,
    device_info JSONB,
    
    expires_at TIMESTAMP WITH TIME ZONE NOT NULL,
    revoked BOOLEAN DEFAULT false,
    revoked_at TIMESTAMP WITH TIME ZONE,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_sessions_user ON sessions(user_id);
CREATE INDEX idx_sessions_token ON sessions(token_hash);
CREATE INDEX idx_sessions_expires ON sessions(expires_at);

-- =====================================================
-- 19. ANOMALİ KAYITLARI TABLOSU
-- =====================================================

CREATE TABLE anomalies (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    tenant_id UUID REFERENCES tenants(id),
    
    type VARCHAR(100) NOT NULL,  -- rapid_redemption, unusual_volume, etc.
    severity VARCHAR(20) DEFAULT 'medium',  -- low, medium, high, critical
    
    entity_type VARCHAR(100),
    entity_id UUID,
    
    description TEXT,
    data JSONB,
    
    -- İnceleme
    reviewed BOOLEAN DEFAULT false,
    reviewed_at TIMESTAMP WITH TIME ZONE,
    reviewed_by UUID REFERENCES users(id),
    resolution TEXT,
    
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

CREATE INDEX idx_anomalies_tenant ON anomalies(tenant_id);
CREATE INDEX idx_anomalies_type ON anomalies(type);
CREATE INDEX idx_anomalies_severity ON anomalies(severity);
CREATE INDEX idx_anomalies_reviewed ON anomalies(reviewed);

-- =====================================================
-- YARDIMCI FONKSİYONLAR
-- =====================================================

-- Günlük kota sıfırlama fonksiyonu
CREATE OR REPLACE FUNCTION reset_daily_quotas()
RETURNS void AS $$
BEGIN
    UPDATE beneficiaries 
    SET used_quota_today = 0, 
        quota_reset_at = CURRENT_DATE
    WHERE quota_reset_at < CURRENT_DATE;
END;
$$ LANGUAGE plpgsql;

-- Süresi dolmuş rezervasyonları işaretleme
CREATE OR REPLACE FUNCTION expire_reservations()
RETURNS void AS $$
BEGIN
    UPDATE reservations 
    SET status = 'expired'
    WHERE status = 'active' 
      AND expires_at < NOW();
    
    UPDATE vouchers 
    SET status = 'expired'
    WHERE status = 'active' 
      AND expires_at < NOW();
END;
$$ LANGUAGE plpgsql;

-- Restoran ortalama puanını güncelleme
CREATE OR REPLACE FUNCTION update_restaurant_rating()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE restaurants 
    SET average_rating = (
            SELECT AVG(score)::DECIMAL(3,2) 
            FROM ratings 
            WHERE restaurant_id = NEW.restaurant_id
        ),
        total_ratings = (
            SELECT COUNT(*) 
            FROM ratings 
            WHERE restaurant_id = NEW.restaurant_id
        )
    WHERE id = NEW.restaurant_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_restaurant_rating
    AFTER INSERT ON ratings
    FOR EACH ROW
    WHEN (NEW.restaurant_id IS NOT NULL)
    EXECUTE FUNCTION update_restaurant_rating();

-- updated_at otomatik güncelleme
CREATE OR REPLACE FUNCTION update_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Tüm tablolara updated_at trigger ekle
DO $$
DECLARE
    t text;
BEGIN
    FOR t IN 
        SELECT table_name 
        FROM information_schema.columns 
        WHERE column_name = 'updated_at' 
          AND table_schema = 'public'
    LOOP
        EXECUTE format('
            DROP TRIGGER IF EXISTS trg_updated_at ON %I;
            CREATE TRIGGER trg_updated_at
                BEFORE UPDATE ON %I
                FOR EACH ROW
                EXECUTE FUNCTION update_updated_at();
        ', t, t);
    END LOOP;
END;
$$ LANGUAGE plpgsql;

-- =====================================================
-- VERİ: TÜRKİYE ÜNİVERSİTELERİ
-- =====================================================

INSERT INTO universities (name, city, type, founded_year, rector_name) VALUES
('ABDULLAH GÜL ÜNİVERSİTESİ', 'KAYSERİ', 'devlet', 2010, 'PROF.DR. CENGİZ YILMAZ'),
('ACIBADEM MEHMET ALİ AYDINLAR ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2007, 'PROF.DR. AHMET ŞAHİN'),
('ADANA ALPARSLAN TÜRKEŞ BİLİM VE TEKNOLOJİ ÜNİVERSİTESİ', 'ADANA', 'devlet', 2011, 'PROF.DR. ADNAN SÖZEN'),
('ADIYAMAN ÜNİVERSİTESİ', 'ADIYAMAN', 'devlet', 2006, 'PROF.DR. MEHMET KELLEŞ'),
('AFYON KOCATEPE ÜNİVERSİTESİ', 'AFYONKARAHİSAR', 'devlet', 1992, 'PROF.DR. MEHMET KARAKAŞ'),
('AFYONKARAHİSAR SAĞLIK BİLİMLERİ ÜNİVERSİTESİ', 'AFYONKARAHİSAR', 'devlet', 2018, 'PROF.DR. ADEM ASLAN'),
('AĞRI İBRAHİM ÇEÇEN ÜNİVERSİTESİ', 'AĞRI', 'devlet', 2007, 'PROF.DR. İLHAMİ GÜLÇİN'),
('AKDENİZ ÜNİVERSİTESİ', 'ANTALYA', 'devlet', 1982, 'PROF.DR. ÖZLENEN ÖZKAN'),
('AKSARAY ÜNİVERSİTESİ', 'AKSARAY', 'devlet', 2006, 'PROF.DR. ALPAY ARIBAŞ'),
('ALANYA ALAADDİN KEYKUBAT ÜNİVERSİTESİ', 'ANTALYA', 'devlet', 2015, 'PROF.DR. KENAN AHMET TÜRKDOĞAN'),
('ALANYA ÜNİVERSİTESİ', 'ANTALYA', 'vakif', 2011, 'PROF.DR. TURAN SAĞER'),
('ALTINBAŞ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2008, 'PROF.DR. CEMAL İBİŞ'),
('AMASYA ÜNİVERSİTESİ', 'AMASYA', 'devlet', 2006, 'PROF.DR. AHMET HAKKI TURABİ'),
('ANADOLU ÜNİVERSİTESİ', 'ESKİŞEHİR', 'devlet', 1973, 'PROF.DR. YUSUF ADIGÜZEL'),
('ANKA TEKNOLOJİ ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2013, NULL),
('ANKARA BİLİM ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2020, 'PROF.DR. YAVUZ DEMİR'),
('ANKARA HACI BAYRAM VELİ ÜNİVERSİTESİ', 'ANKARA', 'devlet', 2018, 'PROF.DR. MEHMET NACİ BOSTANCI'),
('ANKARA MEDİPOL ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2018, 'PROF.DR. TOLGA TOLUNAY'),
('ANKARA MÜZİK VE GÜZEL SANATLAR ÜNİVERSİTESİ', 'ANKARA', 'devlet', 2017, 'PROF.DR. ERHAN ÖZDEN'),
('ANKARA SOSYAL BİLİMLER ÜNİVERSİTESİ', 'ANKARA', 'devlet', 2013, 'PROF.DR. MUSA KAZIM ARICAN'),
('ANKARA ÜNİVERSİTESİ', 'ANKARA', 'devlet', 1946, 'PROF.DR. NECDET ÜNÜVAR'),
('ANKARA YILDIRIM BEYAZIT ÜNİVERSİTESİ', 'ANKARA', 'devlet', 2010, 'PROF.DR. ALİ CENGİZ KÖSEOĞLU'),
('ANTALYA BELEK ÜNİVERSİTESİ', 'ANTALYA', 'vakif', 2015, 'PROF.DR. ABDULLAH KUZU'),
('ANTALYA BİLİM ÜNİVERSİTESİ', 'ANTALYA', 'vakif', 2010, 'PROF.DR. SEMİH EKERCİN'),
('ARDAHAN ÜNİVERSİTESİ', 'ARDAHAN', 'devlet', 2008, 'PROF.DR. ÖZTÜRK EMİROĞLU'),
('ARTVİN ÇORUH ÜNİVERSİTESİ', 'ARTVİN', 'devlet', 2007, 'PROF.DR. İBRAHİM AYDIN'),
('ATAŞEHİR ADIGÜZEL MESLEK YÜKSEKOKULU', 'İSTANBUL', 'vakif_myo', 2014, NULL),
('ATATÜRK ÜNİVERSİTESİ', 'ERZURUM', 'devlet', 1957, 'PROF.DR. AHMET HACIMÜFTÜOĞLU'),
('ATILIM ÜNİVERSİTESİ', 'ANKARA', 'vakif', 1997, 'PROF.DR. SERKAN ERYILMAZ'),
('AVRASYA ÜNİVERSİTESİ', 'TRABZON', 'vakif', 2010, 'PROF.DR. MAHİR KADAKAL'),
('AYDIN ADNAN MENDERES ÜNİVERSİTESİ', 'AYDIN', 'devlet', 1992, 'PROF.DR. BÜLENT KENT'),
('BAHÇEŞEHİR ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1998, 'PROF.DR. ESRA HATİPOĞLU'),
('BALIKESİR ÜNİVERSİTESİ', 'BALIKESİR', 'devlet', 1992, 'PROF.DR. YÜCEL OĞURLU'),
('BANDIRMA ONYEDİ EYLÜL ÜNİVERSİTESİ', 'BALIKESİR', 'devlet', 2015, 'PROF.DR. İSMAİL BOZ'),
('BARTIN ÜNİVERSİTESİ', 'BARTIN', 'devlet', 2008, 'PROF.DR. AHMET AKKAYA'),
('BAŞKENT ÜNİVERSİTESİ', 'ANKARA', 'vakif', 1994, 'PROF.DR. HAKAN ÖZKARDEŞ'),
('BATMAN ÜNİVERSİTESİ', 'BATMAN', 'devlet', 2007, 'PROF.DR. İDRİS DEMİR'),
('BAYBURT ÜNİVERSİTESİ', 'BAYBURT', 'devlet', 2008, 'PROF.DR. MUTLU TÜRKMEN'),
('BEYKOZ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2016, 'PROF.DR. İSMAİL BURAK KÜNTAY'),
('BEZM-İ ÂLEM VAKIF ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2010, 'PROF.DR. ADEM AKÇAKAYA'),
('BİLECİK ŞEYH EDEBALİ ÜNİVERSİTESİ', 'BİLECİK', 'devlet', 2007, 'PROF.DR. ZAFER ASIM KAPLANCIKLI'),
('BİNGÖL ÜNİVERSİTESİ', 'BİNGÖL', 'devlet', 2007, 'PROF.DR. ERDAL ÇELİK'),
('BİRUNİ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2014, 'PROF.DR. ADNAN YÜKSEL'),
('BİTLİS EREN ÜNİVERSİTESİ', 'BİTLİS', 'devlet', 2007, 'PROF.DR. NECMETTİN ELMASTAŞ'),
('BOĞAZİÇİ ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 1971, 'PROF.DR. MEHMET NACİ İNCİ'),
('BOLU ABANT İZZET BAYSAL ÜNİVERSİTESİ', 'BOLU', 'devlet', 1992, 'PROF.DR. FARUK YİĞİT'),
('BURDUR MEHMET AKİF ERSOY ÜNİVERSİTESİ', 'BURDUR', 'devlet', 2006, 'PROF.DR. HÜSEYİN DALGAR'),
('BURSA TEKNİK ÜNİVERSİTESİ', 'BURSA', 'devlet', 2010, 'PROF.DR. NACİ ÇAĞLAR'),
('BURSA ULUDAĞ ÜNİVERSİTESİ', 'BURSA', 'devlet', 1975, 'PROF.DR. FERUDUN YILMAZ'),
('ÇAĞ ÜNİVERSİTESİ', 'MERSİN', 'vakif', 1997, 'PROF.DR. MURAT KOÇ'),
('ÇANAKKALE ONSEKİZ MART ÜNİVERSİTESİ', 'ÇANAKKALE', 'devlet', 1992, 'PROF.DR. RAMAZAN CÜNEYT ERENOĞLU'),
('ÇANKAYA ÜNİVERSİTESİ', 'ANKARA', 'vakif', 1997, 'PROF.DR. HADİ HAKAN MARAŞ'),
('ÇANKIRI KARATEKİN ÜNİVERSİTESİ', 'ÇANKIRI', 'devlet', 2007, 'PROF.DR. MEVLÜT KARATAŞ'),
('ÇUKUROVA ÜNİVERSİTESİ', 'ADANA', 'devlet', 1973, 'PROF.DR. HAMİT EMRAH BERİŞ'),
('DEMİROĞLU BİLİM ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2006, 'PROF.DR. ÇAVLAN ÇİFTÇİ'),
('DİCLE ÜNİVERSİTESİ', 'DİYARBAKIR', 'devlet', 1973, 'PROF.DR. KAMURAN ERONAT'),
('DOĞUŞ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1997, 'PROF.DR. AHMET ALKAN ÇELİK'),
('DOKUZ EYLÜL ÜNİVERSİTESİ', 'İZMİR', 'devlet', 1982, 'PROF.DR. BAYRAM YILMAZ'),
('DÜZCE ÜNİVERSİTESİ', 'DÜZCE', 'devlet', 2006, 'PROF.DR. NEDİM SÖZBİR'),
('EGE ÜNİVERSİTESİ', 'İZMİR', 'devlet', 1955, 'PROF.DR. MUSA ALCI'),
('ERCİYES ÜNİVERSİTESİ', 'KAYSERİ', 'devlet', 1982, 'PROF.DR. FATİH ALTUN'),
('ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ', 'ERZİNCAN', 'devlet', 2006, 'PROF.DR. AKIN LEVENT'),
('ERZURUM TEKNİK ÜNİVERSİTESİ', 'ERZURUM', 'devlet', 2010, 'PROF.DR. BÜLENT ÇAKMAK'),
('ESKİŞEHİR OSMANGAZİ ÜNİVERSİTESİ', 'ESKİŞEHİR', 'devlet', 1993, 'PROF.DR. KAMİL ÇOLAK'),
('ESKİŞEHİR TEKNİK ÜNİVERSİTESİ', 'ESKİŞEHİR', 'devlet', 2018, 'PROF.DR. ADNAN ÖZCAN'),
('FATİH SULTAN MEHMET VAKIF ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2010, 'PROF.DR. NEVZAT ŞİMŞEK'),
('FENERBAHÇE ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2016, 'PROF.DR. FATMA KANCA'),
('FIRAT ÜNİVERSİTESİ', 'ELAZIĞ', 'devlet', 1975, 'PROF.DR. FAHRETTİN GÖKTAŞ'),
('GALATASARAY ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 1994, 'PROF.DR. ABDURRAHMAN MUHAMMED ULUDAĞ'),
('GAZİ ÜNİVERSİTESİ', 'ANKARA', 'devlet', 1982, 'PROF.DR. UĞUR ÜNAL'),
('GAZİANTEP İSLAM BİLİM VE TEKNOLOJİ ÜNİVERSİTESİ', 'GAZİANTEP', 'devlet', 2018, 'PROF.DR. ŞEHMUS DEMİR'),
('GAZİANTEP ÜNİVERSİTESİ', 'GAZİANTEP', 'devlet', 1987, 'PROF.DR. SAİT MESUT DOĞAN'),
('GEBZE TEKNİK ÜNİVERSİTESİ', 'KOCAELİ', 'devlet', 1992, 'PROF.DR. HACI ALİ MANTAR'),
('GİRESUN ÜNİVERSİTESİ', 'GİRESUN', 'devlet', 2006, 'PROF.DR. YILMAZ CAN'),
('GÜMÜŞHANE ÜNİVERSİTESİ', 'GÜMÜŞHANE', 'devlet', 2008, 'PROF.DR. OKTAY YILDIZ'),
('HACETTEPE ÜNİVERSİTESİ', 'ANKARA', 'devlet', 1967, 'PROF.DR. MEHMET CAHİT GÜRAN'),
('HAKKARİ ÜNİVERSİTESİ', 'HAKKARİ', 'devlet', 2008, 'PROF.DR. MUSA GENÇCELEP'),
('HALİÇ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1998, 'PROF.DR. ENES ERYARSOY'),
('HARRAN ÜNİVERSİTESİ', 'ŞANLIURFA', 'devlet', 1992, 'PROF.DR. MEHMET TAHİR GÜLLÜOĞLU'),
('HASAN KALYONCU ÜNİVERSİTESİ', 'GAZİANTEP', 'vakif', 2008, 'PROF.DR. GÜL RENGİN KÜÇÜKERDOĞAN'),
('HATAY MUSTAFA KEMAL ÜNİVERSİTESİ', 'HATAY', 'devlet', 1992, 'PROF.DR. VEYSEL EREN'),
('HİTİT ÜNİVERSİTESİ', 'ÇORUM', 'devlet', 2006, 'PROF.DR. ALİ OSMAN ÖZTÜRK'),
('IĞDIR ÜNİVERSİTESİ', 'IĞDIR', 'devlet', 2008, 'PROF.DR. EKREM GÜREL'),
('ISPARTA UYGULAMALI BİLİMLER ÜNİVERSİTESİ', 'ISPARTA', 'devlet', 2018, 'PROF.DR. YILMAZ ÇATAL'),
('IŞIK ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1996, 'PROF.DR. HASAN BÜLENT KAHRAMAN'),
('İBN HALDUN ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2015, 'PROF.DR. ATİLLA ARKAN'),
('İHSAN DOĞRAMACI BİLKENT ÜNİVERSİTESİ', 'ANKARA', 'vakif', 1985, 'PROF.DR. ABDURRAHMAN KÜRŞAT AYDOĞAN'),
('İNÖNÜ ÜNİVERSİTESİ', 'MALATYA', 'devlet', 1975, 'PROF.DR. NUSRET AKPOLAT'),
('İSKENDERUN TEKNİK ÜNİVERSİTESİ', 'HATAY', 'devlet', 2015, 'PROF.DR. MEHMET DURUEL'),
('İSTANBUL AREL ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2007, 'PROF.DR. ERSİN GÖSE'),
('İSTANBUL ATLAS ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2018, 'PROF.DR. ERSOY KOCABIÇAK'),
('İSTANBUL AYDIN ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2007, 'PROF.DR. İBRAHİM HAKKI AYDIN'),
('İSTANBUL BEYKENT ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1997, 'PROF.DR. VOLKAN ÖNGEL'),
('İSTANBUL BİLGİ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1996, 'PROF.DR. MUSTAFA EGE YAZGAN'),
('İSTANBUL ESENYURT ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2013, 'PROF.DR. SÜLEYMAN ÖZDEMİR'),
('İSTANBUL GALATA ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2019, 'PROF.DR. ŞEYMA AYDINOĞLU'),
('İSTANBUL GEDİK ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2011, 'PROF.DR. FERİHA ERFAN KUYUMCU'),
('İSTANBUL GELİŞİM ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2011, 'PROF.DR. BAHRİ ŞAHİN'),
('İSTANBUL KENT ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2016, 'PROF.DR. MEHMET NECMETTİN ATSÜ'),
('İSTANBUL KÜLTÜR ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1997, 'PROF.DR. FADİME YÜKSEKTEPE'),
('İSTANBUL MEDENİYET ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 2010, 'PROF.DR. GÜLFETTİN ÇELİK'),
('İSTANBUL MEDİPOL ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2009, 'PROF.DR. BAHADIR KÜRŞAT GÜNTÜRK'),
('İSTANBUL NİŞANTAŞI ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2009, 'PROF.DR. AYŞEGÜL KOMSUOĞLU ÇITIPITIOĞLU'),
('İSTANBUL OKAN ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1999, 'PROF.DR. GÜLİZ MUĞAN'),
('İSTANBUL RUMELİ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2015, 'PROF.DR. MUSTAFA KARA'),
('İSTANBUL SABAHATTİN ZAİM ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2010, 'PROF.DR. AHMET CEVAT ACAR'),
('İSTANBUL SAĞLIK VE SOSYAL BİLİMLER MESLEK YÜKSEKOKULU', 'İSTANBUL', 'vakif_myo', 2010, NULL),
('İSTANBUL SAĞLIK VE TEKNOLOJİ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2018, 'PROF.DR. BUĞRA ÖZEN'),
('İSTANBUL ŞİŞLİ MESLEK YÜKSEKOKULU', 'İSTANBUL', 'vakif_myo', 2012, NULL),
('İSTANBUL TEKNİK ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 1944, 'PROF.DR. HASAN MANDAL'),
('İSTANBUL TİCARET ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2001, 'PROF.DR. NECİP ŞİMŞEK'),
('İSTANBUL TOPKAPI ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2016, 'PROF.DR. EMRE ALKİN'),
('İSTANBUL ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 1933, 'PROF.DR. OSMAN BÜLENT ZÜLFİKAR'),
('İSTANBUL ÜNİVERSİTESİ-CERRAHPAŞA', 'İSTANBUL', 'devlet', 2018, 'PROF.DR. NURİ AYDIN'),
('İSTANBUL YENİ YÜZYIL ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2009, 'PROF.DR. İLHAN YAŞAR HACISALİHOĞLU'),
('İSTANBUL 29 MAYIS ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2010, 'PROF.DR. MUSTAFA SİNANOĞLU'),
('İSTİNYE ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2015, 'PROF.DR. ERKAN İBİŞ'),
('İZMİR BAKIRÇAY ÜNİVERSİTESİ', 'İZMİR', 'devlet', 2016, 'PROF.DR. RASİM AKPINAR'),
('İZMİR DEMOKRASİ ÜNİVERSİTESİ', 'İZMİR', 'devlet', 2016, 'PROF.DR. SELİM KARAHASANOĞLU'),
('İZMİR EKONOMİ ÜNİVERSİTESİ', 'İZMİR', 'vakif', 2001, 'PROF.DR. YUSUF HAKAN ABACIOĞLU'),
('İZMİR KATİP ÇELEBİ ÜNİVERSİTESİ', 'İZMİR', 'devlet', 2010, 'PROF.DR. SAFFET KÖSE'),
('İZMİR KAVRAM MESLEK YÜKSEKOKULU', 'İZMİR', 'vakif_myo', 2008, NULL),
('İZMİR TINAZTEPE ÜNİVERSİTESİ', 'İZMİR', 'vakif', 2018, 'PROF.DR. MUSTAFA GÜVENÇER'),
('İZMİR YÜKSEK TEKNOLOJİ ENSTİTÜSÜ', 'İZMİR', 'devlet', 1992, 'PROF.DR. YUSUF BARAN'),
('KADİR HAS ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1997, 'PROF.DR. AYŞE BAŞAR'),
('KAFKAS ÜNİVERSİTESİ', 'KARS', 'devlet', 1992, 'PROF.DR. HÜSNÜ KAPU'),
('KAHRAMANMARAŞ İSTİKLAL ÜNİVERSİTESİ', 'KAHRAMANMARAŞ', 'devlet', 2018, 'PROF.DR. İSMAİL BAKAN'),
('KAHRAMANMARAŞ SÜTÇÜ İMAM ÜNİVERSİTESİ', 'KAHRAMANMARAŞ', 'devlet', 1992, 'PROF.DR. ALPTEKİN YASIM'),
('KAPADOKYA ÜNİVERSİTESİ', 'NEVŞEHİR', 'vakif', 2005, 'PROF.DR. HASAN ALİ KARASAR'),
('KARABÜK ÜNİVERSİTESİ', 'KARABÜK', 'devlet', 2007, 'PROF.DR. FATİH KIRIŞIK'),
('KARADENİZ TEKNİK ÜNİVERSİTESİ', 'TRABZON', 'devlet', 1955, 'PROF.DR. HAMDULLAH ÇUVALCI'),
('KARAMANOĞLU MEHMETBEY ÜNİVERSİTESİ', 'KARAMAN', 'devlet', 2007, 'PROF.DR. MEHMET GAVGALI'),
('KASTAMONU ÜNİVERSİTESİ', 'KASTAMONU', 'devlet', 2006, 'PROF.DR. AHMET HAMDİ TOPAL'),
('KAYSERİ ÜNİVERSİTESİ', 'KAYSERİ', 'devlet', 2018, 'PROF.DR. KURTULUŞ KARAMUSTAFA'),
('KIRIKKALE ÜNİVERSİTESİ', 'KIRIKKALE', 'devlet', 1992, 'PROF.DR. ERSAN ASLAN'),
('KIRKLARELİ ÜNİVERSİTESİ', 'KIRKLARELİ', 'devlet', 2007, 'PROF.DR. RENGİN AK'),
('KIRŞEHİR AHİ EVRAN ÜNİVERSİTESİ', 'KIRŞEHİR', 'devlet', 2006, 'PROF.DR. MUSTAFA KASIM KARAHOCAGİL'),
('KİLİS 7 ARALIK ÜNİVERSİTESİ', 'KİLİS', 'devlet', 2007, 'PROF.DR. ZEKERİYA AKMAN'),
('KOCAELİ SAĞLIK VE TEKNOLOJİ ÜNİVERSİTESİ', 'KOCAELİ', 'vakif', 2020, 'PROF.DR. MUZAFFER ELMAS'),
('KOCAELİ ÜNİVERSİTESİ', 'KOCAELİ', 'devlet', 1992, 'PROF.DR. NUH ZAFER CANTÜRK'),
('KOÇ ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1992, 'PROF.DR. METİN SİTTİ'),
('KONYA GIDA VE TARIM ÜNİVERSİTESİ', 'KONYA', 'vakif', 2013, 'PROF.DR. EROL TURAN'),
('KONYA TEKNİK ÜNİVERSİTESİ', 'KONYA', 'devlet', 2018, 'PROF.DR. OSMAN NURİ ÇELİK'),
('KTO KARATAY ÜNİVERSİTESİ', 'KONYA', 'vakif', 2009, 'PROF.DR. FEVZİ RİFAT ORTAÇ'),
('KÜTAHYA DUMLUPINAR ÜNİVERSİTESİ', 'KÜTAHYA', 'devlet', 1992, 'PROF.DR. SÜLEYMAN KIZILTOPRAK'),
('KÜTAHYA SAĞLIK BİLİMLERİ ÜNİVERSİTESİ', 'KÜTAHYA', 'devlet', 2018, 'PROF.DR. AHMET TEKİN'),
('LOKMAN HEKİM ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2017, 'PROF.DR. FATİH GÜLTEKİN'),
('MALATYA TURGUT ÖZAL ÜNİVERSİTESİ', 'MALATYA', 'devlet', 2018, 'PROF.DR. RECEP BENTLİ'),
('MALTEPE ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1997, 'PROF.DR. EDİBE SÖZEN'),
('MANİSA CELÂL BAYAR ÜNİVERSİTESİ', 'MANİSA', 'devlet', 1992, 'PROF.DR. RANA KİBAR'),
('MARDİN ARTUKLU ÜNİVERSİTESİ', 'MARDİN', 'devlet', 2007, 'PROF.DR. İBRAHİM ÖZCOŞAR'),
('MARMARA ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 1982, 'PROF.DR. MEHMET EMİN OKUR'),
('MEF ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2012, 'PROF.DR. MUHAMMED ŞAHİN'),
('MERSİN ÜNİVERSİTESİ', 'MERSİN', 'devlet', 1992, 'PROF.DR. EROL YAŞAR'),
('MİMAR SİNAN GÜZEL SANATLAR ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 1982, 'PROF.DR. AHMET SACİT AÇIKGÖZOĞLU'),
('MUDANYA ÜNİVERSİTESİ', 'BURSA', 'vakif', 2022, NULL),
('MUĞLA SITKI KOÇMAN ÜNİVERSİTESİ', 'MUĞLA', 'devlet', 1992, 'PROF.DR. TURHAN KAÇAR'),
('MUNZUR ÜNİVERSİTESİ', 'TUNCELİ', 'devlet', 2008, 'PROF.DR. KENAN PEKER'),
('MUŞ ALPARSLAN ÜNİVERSİTESİ', 'MUŞ', 'devlet', 2007, 'PROF.DR. MUSTAFA ALİCAN'),
('NECMETTİN ERBAKAN ÜNİVERSİTESİ', 'KONYA', 'devlet', 2012, 'PROF.DR. CEM ZORLU'),
('NEVŞEHİR HACI BEKTAŞ VELİ ÜNİVERSİTESİ', 'NEVŞEHİR', 'devlet', 2007, 'PROF.DR. SEMİH AKTEKİN'),
('NİĞDE ÖMER HALİSDEMİR ÜNİVERSİTESİ', 'NİĞDE', 'devlet', 1992, 'PROF.DR. HASAN USLU'),
('NUH NACİ YAZGAN ÜNİVERSİTESİ', 'KAYSERİ', 'vakif', 2009, 'PROF.DR. AHMET FAZIL ÖZSOYLU'),
('ONDOKUZ MAYIS ÜNİVERSİTESİ', 'SAMSUN', 'devlet', 1975, 'PROF.DR. FATMA AYDIN'),
('ORDU ÜNİVERSİTESİ', 'ORDU', 'devlet', 2006, 'PROF.DR. ORHAN BAŞ'),
('ORTA DOĞU TEKNİK ÜNİVERSİTESİ', 'ANKARA', 'devlet', 1956, 'PROF.DR. AHMET YOZGATLIGİL'),
('OSMANİYE KORKUT ATA ÜNİVERSİTESİ', 'OSMANİYE', 'devlet', 2007, 'PROF.DR. TURGAY UZUN'),
('OSTİM TEKNİK ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2017, 'PROF.DR. MURAT ALİ YÜLEK'),
('ÖZYEĞİN ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2007, 'PROF.DR. BARIŞ TAN'),
('PAMUKKALE ÜNİVERSİTESİ', 'DENİZLİ', 'devlet', 1992, 'PROF.DR. MAHMUD GÜNGÖR'),
('PİRİ REİS ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2008, 'PROF.DR. NAFİZ ARICA'),
('RECEP TAYYİP ERDOĞAN ÜNİVERSİTESİ', 'RİZE', 'devlet', 2006, 'PROF.DR. YUSUF YILMAZ'),
('SABANCI ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1996, 'PROF.DR. YUSUF LEBLEBİCİ'),
('SAĞLIK BİLİMLERİ ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 2015, 'PROF.DR. KEMALETTİN AYDIN'),
('SAKARYA UYGULAMALI BİLİMLER ÜNİVERSİTESİ', 'SAKARYA', 'devlet', 2018, 'PROF.DR. MEHMET SARIBIYIK'),
('SAKARYA ÜNİVERSİTESİ', 'SAKARYA', 'devlet', 1992, 'PROF.DR. HAMZA AL'),
('SAMSUN ÜNİVERSİTESİ', 'SAMSUN', 'devlet', 2018, 'PROF.DR. MAHMUT AYDIN'),
('SANKO ÜNİVERSİTESİ', 'GAZİANTEP', 'vakif', 2013, 'PROF.DR. GÜNER DAĞLI'),
('SELÇUK ÜNİVERSİTESİ', 'KONYA', 'devlet', 1975, 'PROF.DR. HÜSEYİN YILMAZ'),
('SİİRT ÜNİVERSİTESİ', 'SİİRT', 'devlet', 2007, 'PROF.DR. NİHAT ŞINDAK'),
('SİNOP ÜNİVERSİTESİ', 'SİNOP', 'devlet', 2007, 'PROF.DR. ŞAKİR TAŞDEMİR'),
('SİVAS BİLİM VE TEKNOLOJİ ÜNİVERSİTESİ', 'SİVAS', 'devlet', 2018, 'PROF.DR. MEHMET KUL'),
('SİVAS CUMHURİYET ÜNİVERSİTESİ', 'SİVAS', 'devlet', 1974, 'PROF.DR. AHMET ŞENGÖNÜL'),
('SÜLEYMAN DEMİREL ÜNİVERSİTESİ', 'ISPARTA', 'devlet', 1992, 'PROF.DR. MEHMET SALTAN'),
('ŞIRNAK ÜNİVERSİTESİ', 'ŞIRNAK', 'devlet', 2008, 'PROF.DR. ABDURRAHİM ALKIŞ'),
('TARSUS ÜNİVERSİTESİ', 'MERSİN', 'devlet', 2018, 'PROF.DR. ALİ ÖZEN'),
('TED ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2009, 'PROF.DR. İHSAN SABUNCUOĞLU'),
('TEKİRDAĞ NAMIK KEMAL ÜNİVERSİTESİ', 'TEKİRDAĞ', 'devlet', 2006, 'PROF.DR. MÜMİN ŞAHİN'),
('TOBB EKONOMİ VE TEKNOLOJİ ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2003, 'PROF.DR. YUSUF SARINAY'),
('TOKAT GAZİOSMANPAŞA ÜNİVERSİTESİ', 'TOKAT', 'devlet', 1992, 'PROF.DR. FATİH YILMAZ'),
('TOROS ÜNİVERSİTESİ', 'MERSİN', 'vakif', 2009, 'PROF.DR. ÖMER ARIÖZ'),
('TRABZON ÜNİVERSİTESİ', 'TRABZON', 'devlet', 2018, 'PROF.DR. EMİN AŞIKKUTLU'),
('TRAKYA ÜNİVERSİTESİ', 'EDİRNE', 'devlet', 1982, 'PROF.DR. MUSTAFA HATİPLER'),
('TÜRK HAVA KURUMU ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2011, 'PROF.DR. RAHMİ ER'),
('TÜRK-ALMAN ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 2010, 'PROF.DR. CEMAL YILDIZ'),
('TÜRKİYE ULUSLARARASI İSLAM, BİLİM VE TEKNOLOJİ ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 2015, 'PROF.DR. MEHMET GÖRMEZ'),
('TÜRK-JAPON BİLİM VE TEKNOLOJİ ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 2017, NULL),
('UFUK ÜNİVERSİTESİ', 'ANKARA', 'vakif', 1999, 'PROF.DR. AHMET HAKAN HALİLOĞLU'),
('UŞAK ÜNİVERSİTESİ', 'UŞAK', 'devlet', 2006, 'PROF.DR. AHMET DEMİR'),
('ÜSKÜDAR ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 2011, 'PROF.DR. NAZİFE GÜNGÖR'),
('VAN YÜZÜNCÜ YIL ÜNİVERSİTESİ', 'VAN', 'devlet', 1982, 'PROF.DR. HAMDULLAH ŞEVLİ'),
('YALOVA ÜNİVERSİTESİ', 'YALOVA', 'devlet', 2008, 'PROF.DR. MEHMET BAHÇEKAPILI'),
('YAŞAR ÜNİVERSİTESİ', 'İZMİR', 'vakif', 2001, 'PROF.DR. LEVENT KANDİLLER'),
('YEDİTEPE ÜNİVERSİTESİ', 'İSTANBUL', 'vakif', 1996, 'PROF.DR. MEHMET DURMAN'),
('YILDIZ TEKNİK ÜNİVERSİTESİ', 'İSTANBUL', 'devlet', 1982, 'PROF.DR. EYÜP DEBİK'),
('YOZGAT BOZOK ÜNİVERSİTESİ', 'YOZGAT', 'devlet', 2006, 'PROF.DR. EVREN YAŞAR'),
('YÜKSEK İHTİSAS ÜNİVERSİTESİ', 'ANKARA', 'vakif', 2013, 'PROF.DR. ŞEBNEM KAVAKLI'),
('ZONGULDAK BÜLENT ECEVİT ÜNİVERSİTESİ', 'ZONGULDAK', 'devlet', 1992, 'PROF.DR. İSMAİL HAKKI ÖZÖLÇER');

-- =====================================================
-- PİLOT KURULUM: SELÇUK ÜNİVERSİTESİ VAKFI
-- =====================================================

-- Selçuk Üniversitesi'ni aktif et
UPDATE universities SET is_active = true WHERE name = 'SELÇUK ÜNİVERSİTESİ';

-- Selçuk Üniversitesi Vakfı tenant'ı oluştur
INSERT INTO tenants (university_id, name, daily_quota_per_beneficiary, max_meal_price, is_active)
SELECT id, 'Selçuk Üniversitesi Yaşatma ve Geliştirme Vakfı', 3, 100.00, true
FROM universities WHERE name = 'SELÇUK ÜNİVERSİTESİ';

-- Varsayılan super admin kullanıcısı (şifre: Admin123!)
INSERT INTO users (tenant_id, email, password_hash, first_name, last_name, role, email_verified, is_active)
SELECT t.id, 'admin@kampusportal.com.tr', 
       crypt('Admin123!', gen_salt('bf')),
       'Sistem', 'Yöneticisi', 'super_admin', true, true
FROM tenants t 
WHERE t.name = 'Selçuk Üniversitesi Yaşatma ve Geliştirme Vakfı';

-- Varsayılan vakıf admin kullanıcısı (şifre: Vakif123!)
INSERT INTO users (tenant_id, email, password_hash, first_name, last_name, role, email_verified, is_active)
SELECT t.id, 'vakif@kampusportal.com.tr',
       crypt('Vakif123!', gen_salt('bf')),
       'Vakıf', 'Yöneticisi', 'foundation_admin', true, true
FROM tenants t 
WHERE t.name = 'Selçuk Üniversitesi Yaşatma ve Geliştirme Vakfı';

-- =====================================================
-- ŞEMA SONU
-- =====================================================

