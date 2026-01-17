<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Kampüs Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --ink: #0f1419;
            --ink-light: #536471;
            --ink-faint: #8899a6;
            --surface: #ffffff;
            --surface-dim: #f7f9fa;
            --surface-alt: #eff3f4;
            --teal: #0d9488;
            --teal-dark: #0f766e;
            --teal-light: #ccfbf1;
            --amber: #f59e0b;
            --amber-light: #fef3c7;
            --border: #e1e8ed;
            --error: #dc2626;
            --success: #059669;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--surface-dim);
            min-height: 100vh;
            line-height: 1.5;
        }
        
        /* Navbar */
        .navbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        
        .logo-mark {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 18px;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 20px;
            color: var(--ink);
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--surface-alt);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--ink-light);
        }
        
        .user-details {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 15px;
            color: var(--ink);
        }
        
        .user-role {
            font-size: 13px;
            color: var(--ink-faint);
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
        }
        
        .btn-ghost {
            background: transparent;
            color: var(--ink-light);
        }
        
        .btn-ghost:hover {
            background: var(--surface-alt);
            color: var(--ink);
        }
        
        /* Main Content */
        .main {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 24px;
        }
        
        /* Status Card */
        .status-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
        }
        
        .status-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .status-icon {
            width: 64px;
            height: 64px;
            background: var(--amber-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        
        .status-icon.verified {
            background: var(--teal-light);
        }
        
        .status-text h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 4px;
        }
        
        .status-text p {
            font-size: 15px;
            color: var(--ink-light);
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--amber-light);
            color: #92400e;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-badge svg {
            width: 16px;
            height: 16px;
        }
        
        .status-badge.verified {
            background: var(--teal-light);
            color: var(--teal-dark);
        }
        
        /* Verification Card */
        .verification-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
        }
        
        .verification-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .verification-header-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .verification-header-icon svg {
            width: 24px;
            height: 24px;
        }
        
        .verification-header-text h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 2px;
        }
        
        .verification-header-text p {
            font-size: 14px;
            color: var(--ink-light);
        }
        
        .verification-body {
            padding: 32px;
        }
        
        /* Alert Box */
        .alert-box {
            display: flex;
            gap: 16px;
            padding: 20px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            margin-bottom: 28px;
        }
        
        .alert-box-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            color: #2563eb;
        }
        
        .alert-box-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 6px;
        }
        
        .alert-box-content p {
            font-size: 14px;
            color: #3b82f6;
            line-height: 1.6;
        }
        
        /* Security Notice */
        .security-notice {
            display: flex;
            gap: 12px;
            padding: 16px 20px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            margin-bottom: 28px;
        }
        
        .security-notice svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            color: var(--success);
        }
        
        .security-notice p {
            font-size: 14px;
            color: #166534;
            line-height: 1.6;
        }
        
        .security-notice strong {
            font-weight: 600;
        }
        
        /* Form */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 8px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            color: var(--ink);
            background: var(--surface);
            transition: all 0.2s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 4px var(--teal-light);
        }
        
        .form-group input::placeholder {
            color: var(--ink-faint);
        }
        
        .form-hint {
            font-size: 13px;
            color: var(--ink-faint);
            margin-top: 6px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
        .btn-primary {
            width: 100%;
            padding: 16px 24px;
            background: var(--ink);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 8px;
        }
        
        .btn-primary:hover {
            background: #2a3842;
            transform: translateY(-1px);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-primary svg {
            width: 20px;
            height: 20px;
        }
        
        /* Steps */
        .verification-steps {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
        }
        
        .step {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            background: var(--surface-alt);
            border-radius: 10px;
        }
        
        .step-number {
            width: 28px;
            height: 28px;
            background: var(--surface);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: var(--ink-light);
        }
        
        .step.active .step-number {
            background: var(--teal);
            color: white;
        }
        
        .step-text {
            font-size: 14px;
            font-weight: 500;
            color: var(--ink-light);
        }
        
        .step.active .step-text {
            color: var(--ink);
        }
        
        /* Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 24px;
        }
        
        .info-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }
        
        .info-card h4 {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink-faint);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .info-card p {
            font-size: 16px;
            font-weight: 600;
            color: var(--ink);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                padding: 0 16px;
                height: 64px;
            }
            
            .logo-mark {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }
            
            .logo-text {
                font-size: 18px;
            }
            
            .user-details {
                display: none;
            }
            
            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
            
            .btn-ghost {
                padding: 8px 14px;
                font-size: 13px;
            }
            
            .main {
                padding: 20px 16px;
            }
            
            .status-card {
                padding: 24px 20px;
                border-radius: 16px;
            }
            
            .status-header {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            
            .status-icon {
                width: 56px;
                height: 56px;
                font-size: 24px;
            }
            
            .status-text h2 {
                font-size: 20px;
            }
            
            .status-text p {
                font-size: 14px;
            }
            
            .verification-card {
                border-radius: 16px;
            }
            
            .verification-header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
            
            .verification-header-icon {
                width: 44px;
                height: 44px;
            }
            
            .verification-header-text h3 {
                font-size: 17px;
            }
            
            .verification-body {
                padding: 20px;
            }
            
            .verification-steps {
                flex-direction: column;
                gap: 8px;
            }
            
            .step {
                padding: 12px 14px;
            }
            
            .alert-box {
                flex-direction: column;
                text-align: center;
                padding: 16px;
            }
            
            .alert-box-content h4 {
                font-size: 14px;
            }
            
            .alert-box-content p {
                font-size: 13px;
            }
            
            .security-notice {
                flex-direction: column;
                text-align: center;
                padding: 16px;
            }
            
            .security-notice p {
                font-size: 13px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-group input {
                font-size: 16px; /* Prevents zoom on iOS */
                padding: 12px 14px;
            }
            
            .btn-primary {
                padding: 14px 20px;
                font-size: 15px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .info-card {
                padding: 16px;
            }
        }
        
        @media (max-width: 480px) {
            .navbar {
                height: 60px;
            }
            
            .user-menu {
                gap: 12px;
            }
            
            .main {
                padding: 16px 12px;
            }
            
            .status-card {
                padding: 20px 16px;
                margin-bottom: 16px;
            }
            
            .status-text h2 {
                font-size: 18px;
            }
            
            .verification-header {
                padding: 16px;
            }
            
            .verification-body {
                padding: 16px;
            }
            
            .verification-steps {
                margin-bottom: 20px;
            }
            
            .alert-box,
            .security-notice {
                margin-bottom: 20px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .form-group label {
                font-size: 13px;
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
            <div class="user-info">
                <div class="user-details">
                    <div class="user-name" id="userName">Yükleniyor...</div>
                    <div class="user-role" id="userRole">-</div>
                </div>
                <div class="user-avatar" id="userAvatar">-</div>
            </div>
            <a href="/cikis" class="btn btn-ghost">Çıkış Yap</a>
        </div>
    </nav>
    
    <main class="main">
        <!-- Status Card -->
        <div class="status-card">
            <div class="status-header">
                <div class="status-icon">⏳</div>
                <div class="status-text">
                    <h2>Hoş Geldiniz!</h2>
                    <p>Hesabınız oluşturuldu. Sistemden yararlanmak için öğrenci doğrulaması yapmanız gerekmektedir.</p>
                </div>
            </div>
            <div class="status-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>
                Doğrulama Bekleniyor
            </div>
        </div>
        
        <!-- Verification Card -->
        <div class="verification-card">
            <div class="verification-header">
                <div class="verification-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <div class="verification-header-text">
                    <h3>Öğrenci Doğrulama</h3>
                    <p>Üniversite bilgilerinizi doğrulayın</p>
                </div>
            </div>
            
            <div class="verification-body">
                <!-- Steps -->
                <div class="verification-steps">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-text">Bilgi Girişi</div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-text">Doğrulama</div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-text">Onay</div>
                    </div>
                </div>
                
                <!-- Alert -->
                <div class="alert-box">
                    <svg class="alert-box-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    <div class="alert-box-content">
                        <h4>Neden Doğrulama Gerekli?</h4>
                        <p>Sistemin kötüye kullanımını önlemek ve yardımların gerçek ihtiyaç sahiplerine ulaşmasını sağlamak için üniversite öğrenci bilgi sistemleri üzerinden kimlik doğrulaması yapıyoruz.</p>
                    </div>
                </div>
                
                <!-- Security Notice -->
                <div class="security-notice">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <p><strong>Şifreniz kesinlikle saklanmayacaktır.</strong> Üniversite bilgi sistemi üzerinden tek seferlik doğrulama yapılacak, şifreniz sunucularımızda tutulmayacaktır. Sadece öğrenci olduğunuz bilgisi doğrulandıktan sonra hesabınız aktif edilecektir.</p>
                </div>
                
                <!-- Form -->
                <form id="verificationForm">
                    <div class="form-group">
                        <label for="edu_email">Üniversite E-posta Adresi</label>
                        <input type="email" id="edu_email" name="edu_email" placeholder="ogrenci@selcuk.edu.tr" required>
                        <p class="form-hint">Üniversiteniz tarafından verilen .edu.tr uzantılı e-posta adresinizi girin</p>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="student_number">Öğrenci Numarası</label>
                            <input type="text" id="student_number" name="student_number" placeholder="20201234567" required>
                        </div>
                        <div class="form-group">
                            <label for="uni_password">Öğrenci Bilgi Sistemi Şifresi</label>
                            <input type="password" id="uni_password" name="uni_password" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-primary" id="verifyBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        Doğrulamayı Başlat
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Info Cards -->
        <div class="info-grid">
            <div class="info-card">
                <h4>Hesap Türü</h4>
                <p id="accountType">Öğrenci</p>
            </div>
            <div class="info-card">
                <h4>Üniversite</h4>
                <p id="universityName">-</p>
            </div>
            <div class="info-card">
                <h4>Kayıt Tarihi</h4>
                <p id="registerDate">-</p>
            </div>
        </div>
    </main>
    
    <script>
    (function() {
        var verificationForm = document.getElementById('verificationForm');
        var verifyBtn = document.getElementById('verifyBtn');
        
        // Kullanıcı bilgilerini yükle
        fetch('/api/v1/auth/me')
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success && data.data.user) {
                    var user = data.data.user;
                    var fullName = user.first_name + ' ' + user.last_name;
                    var initials = user.first_name.charAt(0) + user.last_name.charAt(0);
                    
                    document.getElementById('userName').textContent = fullName;
                    document.getElementById('userAvatar').textContent = initials;
                    
                    var roleMap = {
                        'beneficiary': 'Öğrenci',
                        'donor': 'Bağışçı',
                        'restaurant_staff': 'Restoran',
                        'foundation_admin': 'Vakıf Yöneticisi',
                        'super_admin': 'Sistem Yöneticisi'
                    };
                    document.getElementById('userRole').textContent = roleMap[user.role] || user.role;
                    document.getElementById('accountType').textContent = roleMap[user.role] || user.role;
                    
                    // E-posta domain'inden üniversite tahmin et
                    var emailDomain = user.email.split('@')[1];
                    if (emailDomain && emailDomain.includes('edu.tr')) {
                        document.getElementById('edu_email').value = user.email;
                    }
                    
                    document.getElementById('registerDate').textContent = new Date().toLocaleDateString('tr-TR');
                    document.getElementById('universityName').textContent = 'Selçuk Üniversitesi'; // Varsayılan
                } else {
                    window.location.href = '/giris';
                }
            })
            .catch(function() {
                window.location.href = '/giris';
            });
        
        // Form submit
        verificationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<span style="width:18px;height:18px;border:2px solid rgba(255,255,255,0.3);border-top-color:white;border-radius:50%;animation:spin 0.6s linear infinite;display:inline-block;"></span> Doğrulanıyor...';
            
            // Simüle edilmiş doğrulama (API entegre edilecek)
            setTimeout(function() {
                alert('Doğrulama sistemi yakında aktif olacaktır. Şu anda test aşamasındayız.');
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Doğrulamayı Başlat';
            }, 2000);
        });
    })();
    </script>
    
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</body>
</html>
