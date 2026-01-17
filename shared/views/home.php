<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampüs Portal - Öğrenci Destek Platformu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #1e40af;
            --primary-light: #3b82f6;
            --secondary: #059669;
            --text: #1f2937;
            --text-light: #6b7280;
            --bg: #ffffff;
            --bg-alt: #f9fafb;
            --border: #e5e7eb;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text);
            line-height: 1.6;
            background: var(--bg);
        }
        
        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
        }
        
        /* Header */
        header {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text);
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 20px;
        }
        
        nav {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        
        nav a {
            color: var(--text-light);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        nav a:hover {
            color: var(--text);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .btn-outline {
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text);
        }
        
        .btn-outline:hover {
            background: var(--bg-alt);
            border-color: var(--text-light);
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-light);
        }
        
        .btn-secondary {
            background: var(--secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            opacity: 0.9;
        }
        
        .header-buttons {
            display: flex;
            gap: 12px;
        }
        
        /* Hero */
        .hero {
            padding: 80px 0;
            background: var(--bg-alt);
            border-bottom: 1px solid var(--border);
        }
        
        .hero-content {
            max-width: 640px;
        }
        
        .hero-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #dbeafe;
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            border-radius: 20px;
            margin-bottom: 24px;
        }
        
        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            line-height: 1.15;
            margin-bottom: 20px;
            color: var(--text);
        }
        
        .hero p {
            font-size: 18px;
            color: var(--text-light);
            margin-bottom: 32px;
            line-height: 1.7;
        }
        
        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .hero-buttons .btn {
            padding: 14px 28px;
            font-size: 16px;
        }
        
        /* Stats */
        .stats {
            padding: 48px 0;
            background: var(--bg);
            border-bottom: 1px solid var(--border);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 48px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 14px;
            color: var(--text-light);
        }
        
        /* How it works */
        .how-it-works {
            padding: 80px 0;
        }
        
        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 60px;
        }
        
        .section-header h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        
        .section-header p {
            color: var(--text-light);
            font-size: 17px;
        }
        
        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
        }
        
        .step {
            padding: 32px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
        }
        
        .step-number {
            width: 48px;
            height: 48px;
            background: var(--bg-alt);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .step h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        
        .step p {
            color: var(--text-light);
            font-size: 15px;
        }
        
        /* Features */
        .features {
            padding: 80px 0;
            background: var(--bg-alt);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .feature {
            display: flex;
            gap: 20px;
            padding: 28px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
        }
        
        .feature-icon {
            width: 48px;
            height: 48px;
            background: #dbeafe;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .feature h3 {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .feature p {
            color: var(--text-light);
            font-size: 14px;
        }
        
        /* Pilot Info */
        .pilot {
            padding: 80px 0;
            text-align: center;
        }
        
        .pilot-card {
            max-width: 700px;
            margin: 0 auto;
            padding: 48px;
            background: var(--bg-alt);
            border: 1px solid var(--border);
            border-radius: 16px;
        }
        
        .pilot-card h2 {
            font-size: 24px;
            margin-bottom: 16px;
        }
        
        .pilot-card p {
            color: var(--text-light);
            margin-bottom: 32px;
        }
        
        .university-logo {
            font-size: 14px;
            color: var(--text-light);
            margin-top: 24px;
        }
        
        .university-logo strong {
            color: var(--text);
        }
        
        /* CTA */
        .cta {
            padding: 80px 0;
            background: var(--primary);
            color: white;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 32px;
            margin-bottom: 16px;
        }
        
        .cta p {
            opacity: 0.9;
            margin-bottom: 32px;
            font-size: 17px;
        }
        
        .cta .btn {
            background: white;
            color: var(--primary);
        }
        
        .cta .btn:hover {
            background: var(--bg-alt);
        }
        
        /* Footer */
        footer {
            padding: 48px 0;
            background: var(--bg);
            border-top: 1px solid var(--border);
        }
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer-text {
            color: var(--text-light);
            font-size: 14px;
        }
        
        .footer-links {
            display: flex;
            gap: 24px;
        }
        
        .footer-links a {
            color: var(--text-light);
            text-decoration: none;
            font-size: 14px;
        }
        
        .footer-links a:hover {
            color: var(--text);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 32px;
            }
            
            .steps, .features-grid {
                grid-template-columns: 1fr;
            }
            
            nav {
                display: none;
            }
            
            .footer-content {
                flex-direction: column;
                gap: 24px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-inner">
                <a href="/" class="logo">
                    <div class="logo-icon">KP</div>
                    <span class="logo-text">Kampüs Portal</span>
                </a>
                
                <nav>
                    <a href="#nasil-calisir">Nasıl Çalışır?</a>
                    <a href="#ozellikler">Özellikler</a>
                    <a href="#pilot">Pilot Proje</a>
                    <a href="#iletisim">İletişim</a>
                </nav>
                
                <div class="header-buttons">
                    <a href="/giris" class="btn btn-outline">Giriş Yap</a>
                    <a href="/kayit" class="btn btn-primary">Kayıt Ol</a>
                </div>
            </div>
        </div>
    </header>
    
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">Selçuk Üniversitesi Vakfı Pilot Projesi</span>
                <h1>Üniversite Öğrencilerine Yemek Desteği</h1>
                <p>
                    Bağışçılar ve öğrencileri bir araya getiren güvenli platform. 
                    Bağışlarınız doğrudan ihtiyaç sahibi öğrencilere ulaşır.
                </p>
                <div class="hero-buttons">
                    <a href="/kayit?rol=bagisci" class="btn btn-secondary">Bağış Yap</a>
                    <a href="/kayit?rol=ogrenci" class="btn btn-outline">Öğrenci Kaydı</a>
                </div>
            </div>
        </div>
    </section>
    
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">207</div>
                    <div class="stat-label">Kayıtlı Üniversite</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">1</div>
                    <div class="stat-label">Aktif Pilot Bölge</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">0</div>
                    <div class="stat-label">Dağıtılan Öğün</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">%100</div>
                    <div class="stat-label">Şeffaflık</div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="how-it-works" id="nasil-calisir">
        <div class="container">
            <div class="section-header">
                <h2>Nasıl Çalışır?</h2>
                <p>Üç basit adımda öğrencilere destek olun veya destekten yararlanın.</p>
            </div>
            
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Kayıt Olun</h3>
                    <p>Bağışçı veya öğrenci olarak platforma kayıt olun. Öğrenciler üniversite kimliği ile doğrulanır.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Bağış veya Rezervasyon</h3>
                    <p>Bağışçılar istedikleri tutarda bağış yapar. Öğrenciler anlaşmalı restoranlardan rezervasyon oluşturur.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Kullanım</h3>
                    <p>Öğrenci restoranda QR kodu gösterir, ücretsiz yemeğini alır. Bağışçıya anonim bildirim gider.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="features" id="ozellikler">
        <div class="container">
            <div class="section-header">
                <h2>Platform Özellikleri</h2>
                <p>Güvenli, şeffaf ve kullanımı kolay bir sistem.</p>
            </div>
            
            <div class="features-grid">
                <div class="feature">
                    <div class="feature-icon">🔒</div>
                    <div>
                        <h3>Güvenli Ödeme</h3>
                        <p>Tüm bağışlar güvenli ödeme altyapısı üzerinden alınır. Restoran kasasından para geçmez.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">📱</div>
                    <div>
                        <h3>Tek Kullanımlık QR</h3>
                        <p>Her öğün için dinamik QR kod üretilir. Kopyalanamaz, paylaşılamaz.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">📊</div>
                    <div>
                        <h3>Şeffaf Takip</h3>
                        <p>Bağışçılar bağışlarının kullanımını anonim olarak takip edebilir.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">🏛️</div>
                    <div>
                        <h3>Vakıf Denetimi</h3>
                        <p>Her üniversite vakfı kendi bütçesini ve politikasını yönetir.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">✅</div>
                    <div>
                        <h3>Doğrulanmış Öğrenciler</h3>
                        <p>Sadece aktif öğrenciler sistemden faydalanabilir. Kimlik doğrulaması yapılır.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">💳</div>
                    <div>
                        <h3>Hak Ediş Sistemi</h3>
                        <p>Restoranlar yalnızca doğrulanmış öğünler için ödeme alır.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="pilot" id="pilot">
        <div class="container">
            <div class="pilot-card">
                <h2>Pilot Proje: Konya</h2>
                <p>
                    Selçuk Üniversitesi Yaşatma ve Geliştirme Vakfı işbirliğiyle 
                    Konya'da pilot uygulama olarak başlatılmıştır. 
                    Başarılı sonuçların ardından diğer üniversitelere yaygınlaştırılacaktır.
                </p>
                <a href="/kayit" class="btn btn-primary">Pilot Projeye Katıl</a>
                <p class="university-logo">
                    <strong>Selçuk Üniversitesi Yaşatma ve Geliştirme Vakfı</strong>
                </p>
            </div>
        </div>
    </section>
    
    <section class="cta">
        <div class="container">
            <h2>Bir Öğrencinin Gününe Işık Olun</h2>
            <p>Yapacağınız bağış, bir öğrencinin karnını doyuracak.</p>
            <a href="/kayit?rol=bagisci" class="btn">Hemen Bağış Yap</a>
        </div>
    </section>
    
    <footer id="iletisim">
        <div class="container">
            <div class="footer-content">
                <div class="footer-text">
                    © <?= date('Y') ?> Kampüs Portal. Tüm hakları saklıdır.
                </div>
                <div class="footer-links">
                    <a href="#">Gizlilik Politikası</a>
                    <a href="#">Kullanım Şartları</a>
                    <a href="#">KVKK</a>
                    <a href="mailto:info@kampusportal.com.tr">İletişim</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
