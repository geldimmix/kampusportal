<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Kampüs Portal</title>
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
            --error: #ef4444;
            --success: #22c55e;
        }
        
        body {
            font-family: 'Space Grotesk', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
        }
        
        .login-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 48px;
            backdrop-filter: blur(10px);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo a {
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        h1 {
            color: var(--light);
            font-size: 1.75rem;
            text-align: center;
            margin-bottom: 8px;
        }
        
        .subtitle {
            color: var(--gray);
            text-align: center;
            margin-bottom: 32px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: var(--light);
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        input {
            width: 100%;
            padding: 14px 16px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            color: var(--light);
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255,255,255,0.08);
        }
        
        input::placeholder {
            color: var(--gray);
        }
        
        .btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--gray);
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }
        
        .divider span {
            padding: 0 16px;
            font-size: 0.875rem;
        }
        
        .register-link {
            text-align: center;
            color: var(--gray);
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
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--error);
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: var(--success);
        }
        
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
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
            <div class="logo">
                <a href="/">🎓 Kampüs Portal</a>
            </div>
            
            <h1>Hoş Geldiniz</h1>
            <p class="subtitle">Hesabınıza giriş yapın</p>
            
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
                
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    Giriş Yap
                </button>
            </form>
            
            <div class="divider">
                <span>veya</span>
            </div>
            
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
            
            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Giriş yapılıyor...';
            alert.style.display = 'none';
            
            const data = {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };
            
            try {
                const response = await fetch('/api/v1/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = 'Giriş başarılı! Yönlendiriliyorsunuz...';
                    alert.style.display = 'block';
                    
                    // Rol'e göre yönlendir
                    setTimeout(() => {
                        const role = result.data.user.role;
                        if (['super_admin', 'foundation_admin', 'foundation_staff'].includes(role)) {
                            window.location.href = '/yonetim';
                        } else if (['restaurant_owner', 'restaurant_staff', 'cafeteria_manager'].includes(role)) {
                            window.location.href = '/restoran';
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

