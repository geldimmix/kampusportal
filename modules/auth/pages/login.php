<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Kampüs Portal</title>
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
            --border: #e1e8ed;
            --error: #dc2626;
            --error-light: #fee2e2;
            --success: #059669;
            --success-light: #d1fae5;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: var(--surface-dim);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            line-height: 1.5;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 32px;
        }
        
        .logo-mark {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--teal-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 20px;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 22px;
            color: var(--ink);
            letter-spacing: -0.5px;
        }
        
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .card-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 8px;
        }
        
        .card-header p {
            color: var(--ink-light);
            font-size: 15px;
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
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--teal);
            cursor: pointer;
        }
        
        .checkbox-group label {
            font-size: 14px;
            color: var(--ink-light);
            cursor: pointer;
        }
        
        .forgot-link {
            font-size: 14px;
            color: var(--teal);
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        /* Alert */
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
            align-items: center;
            gap: 10px;
        }
        
        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        
        .alert.error {
            background: var(--error-light);
            color: var(--error);
            display: flex;
        }
        
        .alert.success {
            background: var(--success-light);
            color: var(--success);
            display: flex;
        }
        
        /* Button */
        .btn {
            width: 100%;
            padding: 16px 24px;
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
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--ink);
            color: white;
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
        
        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 28px 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        
        .divider span {
            font-size: 13px;
            color: var(--ink-faint);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Footer */
        .card-footer {
            text-align: center;
        }
        
        .card-footer p {
            font-size: 15px;
            color: var(--ink-light);
        }
        
        .card-footer a {
            color: var(--teal);
            text-decoration: none;
            font-weight: 600;
        }
        
        .card-footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 24px 16px;
                align-items: flex-start;
            }
            
            .logo {
                margin-bottom: 24px;
            }
            
            .logo-mark {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            
            .logo-text {
                font-size: 20px;
            }
            
            .card {
                padding: 28px 20px;
                border-radius: 16px;
            }
            
            .card-header {
                margin-bottom: 24px;
            }
            
            .card-header h1 {
                font-size: 24px;
            }
            
            .card-header p {
                font-size: 14px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .form-group label {
                font-size: 13px;
                margin-bottom: 6px;
            }
            
            .form-group input {
                padding: 12px 14px;
                font-size: 16px; /* Prevents zoom on iOS */
                border-radius: 8px;
            }
            
            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
                margin-bottom: 20px;
            }
            
            .checkbox-group label {
                font-size: 14px;
            }
            
            .forgot-link {
                font-size: 14px;
            }
            
            .btn {
                padding: 14px 20px;
                font-size: 15px;
                border-radius: 10px;
            }
            
            .divider {
                margin: 24px 0;
            }
            
            .card-footer p {
                font-size: 14px;
            }
        }
        
        @media (max-width: 380px) {
            body {
                padding: 16px 12px;
            }
            
            .card {
                padding: 24px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <a href="/" class="logo">
            <div class="logo-mark">K</div>
            <span class="logo-text">Kampüs Portal</span>
        </a>
        
        <div class="card">
            <div class="card-header">
                <h1>Tekrar Hoş Geldiniz</h1>
                <p>Hesabınıza giriş yapın</p>
            </div>
            
            <div id="alert" class="alert"></div>
            
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="email" placeholder="ornek@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                
                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Beni hatırla</label>
                    </div>
                    <a href="#" class="forgot-link">Şifremi unuttum</a>
                </div>
                
                <button type="submit" class="btn btn-primary" id="submitBtn">Giriş Yap</button>
            </form>
            
            <div class="divider">
                <span>veya</span>
            </div>
            
            <div class="card-footer">
                <p>Hesabınız yok mu? <a href="/kayit">Kayıt Ol</a></p>
            </div>
        </div>
    </div>
    
    <script>
    (function() {
        var form = document.getElementById('loginForm');
        var alert = document.getElementById('alert');
        var submitBtn = document.getElementById('submitBtn');
        
        function showAlert(message, type) {
            var icon = type === 'error' 
                ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'
                : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            alert.innerHTML = icon + '<span>' + message + '</span>';
            alert.className = 'alert ' + type;
        }
        
        function hideAlert() {
            alert.className = 'alert';
            alert.innerHTML = '';
        }
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            hideAlert();
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Giriş yapılıyor...';
            
            var data = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };
            
            fetch('/api/v1/auth/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(function(res) { return res.json(); })
            .then(function(result) {
                if (result.success) {
                    showAlert('Giriş başarılı! Yönlendiriliyorsunuz...', 'success');
                    setTimeout(function() {
                        window.location.href = '/panel';
                    }, 1000);
                } else {
                    showAlert(result.message || 'Giriş başarısız', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Giriş Yap';
                }
            })
            .catch(function(err) {
                showAlert('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Giriş Yap';
            });
        });
    })();
    </script>
</body>
</html>
