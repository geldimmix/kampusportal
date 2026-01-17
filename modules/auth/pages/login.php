<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Kampüs Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --primary: #1e40af;
            --primary-light: #3b82f6;
            --text: #1f2937;
            --text-light: #6b7280;
            --bg: #ffffff;
            --bg-alt: #f9fafb;
            --border: #e5e7eb;
            --error: #dc2626;
            --success: #16a34a;
        }
        
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg-alt);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        
        .login-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 40px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
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
            font-size: 16px;
        }
        
        .logo-text {
            font-weight: 700;
            font-size: 20px;
        }
        
        h1 {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
            color: var(--text);
        }
        
        .subtitle {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 32px;
            font-size: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 6px;
        }
        
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            background: var(--primary);
            color: white;
            transition: background 0.2s;
        }
        
        .btn:hover {
            background: var(--primary-light);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--text-light);
            font-size: 13px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        
        .divider span {
            padding: 0 16px;
        }
        
        .register-link {
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
        }
        
        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }
        
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: var(--error);
        }
        
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: var(--success);
        }
        
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <a href="/" class="logo">
                <div class="logo-icon">KP</div>
                <span class="logo-text">Kampüs Portal</span>
            </a>
            
            <h1>Giriş Yap</h1>
            <p class="subtitle">Hesabınıza erişin</p>
            
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
                
                <button type="submit" class="btn" id="submitBtn">Giriş Yap</button>
            </form>
            
            <div class="divider"><span>veya</span></div>
            
            <p class="register-link">
                Hesabınız yok mu? <a href="/kayit">Kayıt Ol</a>
            </p>
        </div>
    </div>
    
    <script>
        const form = document.getElementById('loginForm');
        const alert = document.getElementById('alert');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Giriş yapılıyor...';
            alert.style.display = 'none';
            
            try {
                const response = await fetch('/api/v1/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email: document.getElementById('email').value,
                        password: document.getElementById('password').value
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = 'Giriş başarılı! Yönlendiriliyorsunuz...';
                    alert.style.display = 'block';
                    
                    setTimeout(() => {
                        const role = result.data.user.role;
                        if (['super_admin', 'foundation_admin', 'foundation_staff'].includes(role)) {
                            window.location.href = '/yonetim';
                        } else {
                            window.location.href = '/panel';
                        }
                    }, 1000);
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = result.message;
                    alert.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Giriş Yap';
                }
            } catch (error) {
                alert.className = 'alert alert-error';
                alert.textContent = 'Bir hata oluştu. Lütfen tekrar deneyin.';
                alert.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Giriş Yap';
            }
        });
    </script>
</body>
</html>
