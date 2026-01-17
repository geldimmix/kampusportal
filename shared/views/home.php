<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kampüs Portal - Askıda Destek</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #ec4899;
            --dark: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
        }
        
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            color: var(--light);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        header {
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }
        
        .nav-links a {
            color: var(--light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3);
        }
        
        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        
        /* Hero */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
            opacity: 0.15;
            top: -200px;
            right: -200px;
            border-radius: 50%;
        }
        
        .hero::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--secondary) 0%, transparent 70%);
            opacity: 0.1;
            bottom: -100px;
            left: -100px;
            border-radius: 50%;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 700px;
        }
        
        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 24px;
        }
        
        .hero h1 span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero p {
            font-size: 1.25rem;
            color: var(--gray);
            margin-bottom: 40px;
            line-height: 1.7;
        }
        
        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        /* Stats */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-top: 80px;
        }
        
        .stat-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-label {
            color: var(--gray);
            margin-top: 8px;
        }
        
        /* Features */
        .features {
            padding: 120px 0;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 60px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        
        .feature-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 40px;
            transition: all 0.3s;
        }
        
        .feature-card:hover {
            background: rgba(255,255,255,0.08);
            border-color: var(--primary);
            transform: translateY(-10px);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 1.25rem;
            margin-bottom: 12px;
        }
        
        .feature-card p {
            color: var(--gray);
            line-height: 1.6;
        }
        
        /* Footer */
        footer {
            padding: 40px 0;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            color: var(--gray);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">🎓 Kampüs Portal</div>
                <div class="nav-links">
                    <a href="#nasil-calisir">Nasıl Çalışır?</a>
                    <a href="#ozellikler">Özellikler</a>
                    <a href="#hakkinda">Hakkında</a>
                    <a href="/giris" class="btn btn-outline">Giriş Yap</a>
                    <a href="/kayit" class="btn btn-primary">Kayıt Ol</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Askıda <span>Yemek</span>,<br>Gönüllerde <span>Umut</span></h1>
                    <p>
                        Üniversite öğrencilerine yemek desteği sağlayan sosyal sorumluluk platformu. 
                        Bağışçılar ve öğrencileri bir araya getiriyoruz.
                    </p>
                    <div class="hero-buttons">
                        <a href="/kayit?rol=bagisci" class="btn btn-primary">🤝 Bağış Yap</a>
                        <a href="/kayit?rol=ogrenci" class="btn btn-outline">📚 Öğrenci Kaydı</a>
                    </div>
                </div>
                
                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-number">207</div>
                        <div class="stat-label">Üniversite</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">1</div>
                        <div class="stat-label">Aktif Pilot</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Öğün Dağıtıldı</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Bağışçı</div>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="features" id="ozellikler">
            <div class="container">
                <h2 class="section-title">Nasıl Çalışır?</h2>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🎁</div>
                        <h3>Bağış Yap</h3>
                        <p>İstediğin üniversiteye, şehre veya restorana özel bağış yap. Bağışın doğrudan öğrencilere ulaşır.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3>Rezervasyon Yap</h3>
                        <p>Öğrenci olarak sisteme kayıt ol, yakınındaki restoranları gör ve 30 dakikalık rezervasyon oluştur.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🔐</div>
                        <h3>QR ile Kullan</h3>
                        <p>Restoranda tek kullanımlık QR kodunu göster, ücretsiz yemeğini al. Güvenli ve hızlı.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Şeffaf Takip</h3>
                        <p>Bağışçılar, bağışlarının ne zaman ve nerede kullanıldığını anonim olarak takip edebilir.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">🏫</div>
                        <h3>Üniversite Yemekhaneleri</h3>
                        <p>Vakıflar kendi yemekhanelerini de sisteme ekleyebilir ve öğrencilere hizmet sunabilir.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3>Hak Ediş Sistemi</h3>
                        <p>Restoranlar yalnızca doğrulanmış öğünler için ödeme alır. Şeffaf ve güvenilir.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Kampüs Portal - Selçuk Üniversitesi Vakfı Pilot Projesi</p>
        </div>
    </footer>
</body>
</html>

