<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Kampüs Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --ink: #0f1419;
            --ink-light: #536471;
            --surface: #ffffff;
            --surface-dim: #f7f9fa;
            --teal: #0d9488;
            --border: #e1e8ed;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--surface-dim);
            min-height: 100vh;
        }
        
        .navbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--ink);
        }
        
        .logo-mark {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--teal) 0%, #0f766e 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 20px;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .user-name {
            font-weight: 500;
            color: var(--ink);
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--ink);
        }
        
        .btn-outline:hover {
            background: var(--surface-dim);
        }
        
        .main {
            max-width: 800px;
            margin: 60px auto;
            padding: 0 24px;
        }
        
        .welcome-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 48px;
            text-align: center;
        }
        
        .welcome-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(13,148,136,0.1) 0%, rgba(13,148,136,0.05) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 24px;
        }
        
        .welcome-card h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--ink);
        }
        
        .welcome-card p {
            font-size: 16px;
            color: var(--ink-light);
            margin-bottom: 32px;
            line-height: 1.6;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #fef3c7;
            color: #92400e;
            border-radius: 100px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-badge svg {
            width: 18px;
            height: 18px;
        }
        
        .info-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 40px;
        }
        
        .info-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            text-align: left;
        }
        
        .info-card h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--ink-light);
            margin-bottom: 8px;
        }
        
        .info-card p {
            font-size: 16px;
            font-weight: 600;
            color: var(--ink);
            margin: 0;
        }
        
        @media (max-width: 600px) {
            .info-cards {
                grid-template-columns: 1fr;
            }
            
            .welcome-card {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">
            <div class="logo-mark">K</div>
            <span class="logo-text">Kampüs Portal</span>
        </a>
        
        <div class="user-menu">
            <span class="user-name" id="userName">Yükleniyor...</span>
            <a href="/cikis" class="btn btn-outline">Çıkış Yap</a>
        </div>
    </nav>
    
    <main class="main">
        <div class="welcome-card">
            <div class="welcome-icon">🎉</div>
            <h1>Hoş Geldiniz!</h1>
            <p>
                Kampüs Portal'a başarıyla kayıt oldunuz. 
                Hesabınız şu anda inceleme aşamasındadır. 
                Doğrulama tamamlandığında size bildirim göndereceğiz.
            </p>
            
            <div class="status-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
                Hesap Doğrulama Bekleniyor
            </div>
        </div>
        
        <div class="info-cards">
            <div class="info-card">
                <h3>Hesap Türü</h3>
                <p id="accountType">-</p>
            </div>
            <div class="info-card">
                <h3>Kayıt Tarihi</h3>
                <p id="registerDate">-</p>
            </div>
        </div>
    </main>
    
    <script>
        fetch('/api/v1/auth/me')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data.user) {
                    const user = data.data.user;
                    document.getElementById('userName').textContent = user.first_name + ' ' + user.last_name;
                    
                    const roleMap = {
                        'beneficiary': 'Öğrenci',
                        'donor': 'Bağışçı',
                        'restaurant_staff': 'Restoran',
                        'foundation_admin': 'Vakıf Yöneticisi',
                        'super_admin': 'Sistem Yöneticisi'
                    };
                    document.getElementById('accountType').textContent = roleMap[user.role] || user.role;
                    document.getElementById('registerDate').textContent = new Date().toLocaleDateString('tr-TR');
                } else {
                    window.location.href = '/giris';
                }
            })
            .catch(() => {
                window.location.href = '/giris';
            });
    </script>
</body>
</html>

