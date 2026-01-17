<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Kampüs Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --primary: #6366f1;
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
        
        .register-container { width: 100%; max-width: 480px; }
        
        .register-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 48px;
            backdrop-filter: blur(10px);
        }
        
        .logo { text-align: center; margin-bottom: 32px; }
        
        .logo a {
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        h1 { color: var(--light); font-size: 1.75rem; text-align: center; margin-bottom: 8px; }
        .subtitle { color: var(--gray); text-align: center; margin-bottom: 32px; }
        
        .role-selector {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        
        .role-btn {
            padding: 16px;
            border: 2px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            background: rgba(255,255,255,0.03);
            color: var(--light);
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        
        .role-btn:hover { border-color: var(--primary); }
        
        .role-btn.active {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }
        
        .role-btn .icon { font-size: 1.5rem; margin-bottom: 8px; }
        .role-btn .title { font-weight: 600; }
        .role-btn .desc { font-size: 0.75rem; color: var(--gray); margin-top: 4px; }
        
        .form-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .form-group { margin-bottom: 20px; }
        
        label { display: block; color: var(--light); font-weight: 500; margin-bottom: 8px; }
        
        input, select {
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
        
        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255,255,255,0.08);
        }
        
        select option { background: var(--dark); color: var(--light); }
        
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
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3); }
        .btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
        
        .login-link { text-align: center; color: var(--gray); margin-top: 24px; }
        .login-link a { color: var(--primary); text-decoration: none; font-weight: 500; }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: var(--error); }
        .alert-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: var(--success); }
        
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        #studentFields, #donorFields { display: none; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="logo">
                <a href="/">🎓 Kampüs Portal</a>
            </div>
            
            <h1>Hesap Oluştur</h1>
            <p class="subtitle">Rol seçerek başlayın</p>
            
            <div id="alert" class="alert"></div>
            
            <div class="role-selector">
                <div class="role-btn active" data-role="beneficiary">
                    <div class="icon">📚</div>
                    <div class="title">Öğrenci</div>
                    <div class="desc">Yemek desteği al</div>
                </div>
                <div class="role-btn" data-role="donor">
                    <div class="icon">🤝</div>
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
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" minlength="8" required>
                </div>
                
                <!-- Öğrenci alanları -->
                <div id="studentFields">
                    <div class="form-group">
                        <label for="university">Üniversite</label>
                        <select id="university" name="university">
                            <option value="">Üniversite seçin</option>
                        </select>
                    </div>
                </div>
                
                <!-- Bağışçı alanları -->
                <div id="donorFields">
                    <div class="form-group">
                        <label>
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
        
        // Rol seçimi
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
        
        // Kurumsal checkbox
        document.getElementById('is_corporate').addEventListener('change', function() {
            document.getElementById('companyField').style.display = this.checked ? 'block' : 'none';
        });
        
        // URL'den rol parametresi
        const urlParams = new URLSearchParams(window.location.search);
        const roleParam = urlParams.get('rol');
        if (roleParam === 'bagisci') {
            document.querySelector('[data-role="donor"]').click();
        }
        
        // Form submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Kaydediliyor...';
            alert.style.display = 'none';
            
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            
            // Tenant ID ekle (şimdilik Selçuk Üniversitesi)
            if (data.role === 'beneficiary' && data.university) {
                // İlgili tenant'ı bul
            }
            
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
                    
                    setTimeout(() => {
                        window.location.href = '/panel';
                    }, 1000);
                } else {
                    alert.className = 'alert alert-error';
                    alert.textContent = result.message;
                    if (result.errors) {
                        alert.textContent += ': ' + Object.values(result.errors).join(', ');
                    }
                    alert.style.display = 'block';
                    
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kayıt Ol';
                }
            } catch (error) {
                alert.className = 'alert alert-error';
                alert.textContent = 'Bir hata oluştu. Lütfen tekrar deneyin.';
                alert.style.display = 'block';
                
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kayıt Ol';
            }
        });
    </script>
</body>
</html>

