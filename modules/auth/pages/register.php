<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Kampüs Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --primary: #1e40af;
            --primary-light: #3b82f6;
            --secondary: #059669;
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
        
        .register-container { width: 100%; max-width: 440px; }
        
        .register-card {
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
        
        .logo-text { font-weight: 700; font-size: 20px; }
        
        h1 { font-size: 24px; font-weight: 700; text-align: center; margin-bottom: 8px; }
        .subtitle { text-align: center; color: var(--text-light); margin-bottom: 24px; font-size: 15px; }
        
        .role-selector {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        
        .role-btn {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg);
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        
        .role-btn:hover { border-color: var(--primary); }
        
        .role-btn.active {
            border-color: var(--primary);
            background: #eff6ff;
        }
        
        .role-btn .icon { font-size: 24px; margin-bottom: 8px; }
        .role-btn .title { font-weight: 600; font-size: 15px; color: var(--text); }
        .role-btn .desc { font-size: 12px; color: var(--text-light); margin-top: 4px; }
        
        .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .form-group { margin-bottom: 16px; }
        
        label { display: block; font-size: 14px; font-weight: 500; color: var(--text); margin-bottom: 6px; }
        
        input, select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--bg);
        }
        
        input:focus, select:focus {
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
        
        .btn:hover { background: var(--primary-light); }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; }
        
        .login-link {
            text-align: center;
            color: var(--text-light);
            font-size: 14px;
            margin-top: 24px;
        }
        
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }
        
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: var(--error); }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: var(--success); }
        
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text);
            cursor: pointer;
        }
        
        .checkbox-label input { width: auto; }
        
        #studentFields, #donorFields { display: none; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <a href="/" class="logo">
                <div class="logo-icon">KP</div>
                <span class="logo-text">Kampüs Portal</span>
            </a>
            
            <h1>Hesap Oluştur</h1>
            <p class="subtitle">Rol seçerek başlayın</p>
            
            <div id="alert" class="alert"></div>
            
            <div class="role-selector">
                <div class="role-btn active" data-role="beneficiary">
                    <div class="icon">🎓</div>
                    <div class="title">Öğrenci</div>
                    <div class="desc">Yemek desteği al</div>
                </div>
                <div class="role-btn" data-role="donor">
                    <div class="icon">❤️</div>
                    <div class="title">Bağışçı</div>
                    <div class="desc">Destek ol</div>
                </div>
            </div>
            
            <form id="registerForm">
                <input type="hidden" id="role" name="role" value="beneficiary">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">Ad</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Soyad</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="tel" id="phone" name="phone" placeholder="05XX XXX XX XX">
                </div>
                
                <div class="form-group">
                    <label for="password">Şifre (min 8 karakter)</label>
                    <input type="password" id="password" name="password" minlength="8" required>
                </div>
                
                <div id="studentFields">
                    <div class="form-group">
                        <label for="university">Üniversite</label>
                        <select id="university" name="university">
                            <option value="">Üniversite seçin</option>
                        </select>
                    </div>
                </div>
                
                <div id="donorFields">
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="is_corporate" name="is_corporate">
                            Kurumsal bağışçıyım
                        </label>
                    </div>
                    <div class="form-group" id="companyField" style="display:none;">
                        <label for="company_name">Şirket Adı</label>
                        <input type="text" id="company_name" name="company_name">
                    </div>
                </div>
                
                <button type="submit" class="btn" id="submitBtn">Kayıt Ol</button>
            </form>
            
            <p class="login-link">
                Zaten hesabınız var mı? <a href="/giris">Giriş Yap</a>
            </p>
        </div>
    </div>
    
    <script>
        const roleButtons = document.querySelectorAll('.role-btn');
        const roleInput = document.getElementById('role');
        const studentFields = document.getElementById('studentFields');
        const donorFields = document.getElementById('donorFields');
        const form = document.getElementById('registerForm');
        const alert = document.getElementById('alert');
        const submitBtn = document.getElementById('submitBtn');
        
        // Üniversiteleri yükle
        fetch('/api/v1/universities')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('university');
                    data.data.forEach(uni => {
                        const option = document.createElement('option');
                        option.value = uni.id;
                        option.textContent = uni.name + ' (' + uni.city + ')';
                        select.appendChild(option);
                    });
                }
            });
        
        roleButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                roleButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                const role = btn.dataset.role;
                roleInput.value = role;
                
                studentFields.style.display = role === 'beneficiary' ? 'block' : 'none';
                donorFields.style.display = role === 'donor' ? 'block' : 'none';
            });
        });
        
        document.getElementById('is_corporate').addEventListener('change', function() {
            document.getElementById('companyField').style.display = this.checked ? 'block' : 'none';
        });
        
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('rol') === 'bagisci') {
            document.querySelector('[data-role="donor"]').click();
        }
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Kaydediliyor...';
            alert.style.display = 'none';
            
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch('/api/v1/auth/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert.className = 'alert alert-success';
                    alert.textContent = 'Kayıt başarılı! Yönlendiriliyorsunuz...';
                    alert.style.display = 'block';
                    setTimeout(() => { window.location.href = '/panel'; }, 1000);
                } else {
                    alert.className = 'alert alert-error';
                    let msg = result.message;
                    if (result.errors) msg += ': ' + Object.values(result.errors).join(', ');
                    alert.textContent = msg;
                    alert.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kayıt Ol';
                }
            } catch (error) {
                alert.className = 'alert alert-error';
                alert.textContent = 'Bir hata oluştu.';
                alert.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kayıt Ol';
            }
        });
    </script>
</body>
</html>
