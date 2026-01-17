<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - Kampüs Portal</title>
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
        
        .register-wrapper {
            width: 100%;
            max-width: 460px;
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
        
        /* Role Tabs */
        .role-tabs {
            display: flex;
            gap: 8px;
            padding: 6px;
            background: var(--surface-alt);
            border-radius: 12px;
            margin-bottom: 28px;
        }
        
        .role-tab {
            flex: 1;
            padding: 14px 20px;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            color: var(--ink-light);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .role-tab:hover {
            color: var(--ink);
        }
        
        .role-tab.active {
            background: var(--surface);
            color: var(--teal);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .role-tab svg {
            width: 18px;
            height: 18px;
        }
        
        /* Form */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
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
        
        .form-group input,
        .form-group select {
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
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--teal);
            box-shadow: 0 0 0 4px var(--teal-light);
        }
        
        .form-group input::placeholder {
            color: var(--ink-faint);
        }
        
        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%23536471' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 44px;
        }
        
        /* Checkbox */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--teal);
            cursor: pointer;
        }
        
        .checkbox-group label {
            font-size: 15px;
            color: var(--ink);
            cursor: pointer;
            user-select: none;
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
        
        /* Footer */
        .card-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
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
        
        /* Conditional Fields */
        .hidden { display: none !important; }
        
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
            
            .role-tabs {
                padding: 4px;
                border-radius: 10px;
                margin-bottom: 24px;
            }
            
            .role-tab {
                padding: 12px 14px;
                font-size: 14px;
                gap: 6px;
            }
            
            .role-tab svg {
                width: 16px;
                height: 16px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .form-group label {
                font-size: 13px;
                margin-bottom: 6px;
            }
            
            .form-group input,
            .form-group select {
                padding: 12px 14px;
                font-size: 16px; /* Prevents zoom on iOS */
                border-radius: 8px;
            }
            
            .checkbox-group {
                margin-bottom: 16px;
            }
            
            .checkbox-group label {
                font-size: 14px;
            }
            
            .btn {
                padding: 14px 20px;
                font-size: 15px;
                border-radius: 10px;
            }
            
            .card-footer {
                margin-top: 20px;
                padding-top: 20px;
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
            
            .role-tab {
                padding: 10px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <a href="/" class="logo">
            <div class="logo-mark">K</div>
            <span class="logo-text">Kampüs Portal</span>
        </a>
        
        <div class="card">
            <div class="card-header">
                <h1>Hesap Oluştur</h1>
                <p>Platforma kayıt olarak başlayın</p>
            </div>
            
            <div id="alert" class="alert"></div>
            
            <div class="role-tabs">
                <button type="button" class="role-tab active" data-role="beneficiary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    Öğrenci
                </button>
                <button type="button" class="role-tab" data-role="donor">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    Bağışçı
                </button>
            </div>
            
            <form id="registerForm">
                <input type="hidden" id="role" name="role" value="beneficiary">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">Ad</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Adınız" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Soyad</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Soyadınız" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="email" placeholder="ornek@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="tel" id="phone" name="phone" placeholder="05XX XXX XX XX">
                </div>
                
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" placeholder="En az 8 karakter" minlength="8" required>
                </div>
                
                <!-- Öğrenci Alanları -->
                <div id="studentFields">
                    <div class="form-group">
                        <label for="university">Üniversite</label>
                        <select id="university" name="university_id">
                            <option value="">Üniversite seçin</option>
                        </select>
                    </div>
                </div>
                
                <!-- Bağışçı Alanları -->
                <div id="donorFields" class="hidden">
                    <div class="checkbox-group">
                        <input type="checkbox" id="is_corporate" name="is_corporate">
                        <label for="is_corporate">Kurumsal bağışçıyım</label>
                    </div>
                    <div class="form-group hidden" id="companyField">
                        <label for="company_name">Şirket Adı</label>
                        <input type="text" id="company_name" name="company_name" placeholder="Şirket adını girin">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" id="submitBtn">Kayıt Ol</button>
            </form>
            
            <div class="card-footer">
                <p>Zaten hesabınız var mı? <a href="/giris">Giriş Yap</a></p>
            </div>
        </div>
    </div>
    
    <script>
    (function() {
        var form = document.getElementById('registerForm');
        var alert = document.getElementById('alert');
        var submitBtn = document.getElementById('submitBtn');
        var roleInput = document.getElementById('role');
        var studentFields = document.getElementById('studentFields');
        var donorFields = document.getElementById('donorFields');
        var universitySelect = document.getElementById('university');
        var roleTabs = document.querySelectorAll('.role-tab');
        var isCorporate = document.getElementById('is_corporate');
        var companyField = document.getElementById('companyField');
        
        // Üniversiteleri yükle
        function loadUniversities() {
            universitySelect.innerHTML = '<option value="">Yükleniyor...</option>';
            
            fetch('/api/v1/universities')
                .then(function(res) {
                    if (!res.ok) throw new Error('API hatası');
                    return res.json();
                })
                .then(function(data) {
                    universitySelect.innerHTML = '<option value="">Üniversite seçin</option>';
                    
                    if (data.success && data.data && data.data.length > 0) {
                        data.data.forEach(function(uni) {
                            var option = document.createElement('option');
                            option.value = uni.id;
                            option.textContent = uni.name + ' - ' + uni.city;
                            universitySelect.appendChild(option);
                        });
                    }
                })
                .catch(function(err) {
                    console.error('Üniversite yükleme hatası:', err);
                    universitySelect.innerHTML = '<option value="">Yüklenemedi - Yenileyin</option>';
                });
        }
        
        // Alert göster
        function showAlert(message, type) {
            var icon = type === 'error' 
                ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'
                : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
            alert.innerHTML = icon + '<span>' + message + '</span>';
            alert.className = 'alert ' + type;
        }
        
        // Alert gizle
        function hideAlert() {
            alert.className = 'alert';
            alert.innerHTML = '';
        }
        
        // Rol değiştir
        function switchRole(role) {
            roleInput.value = role;
            
            roleTabs.forEach(function(tab) {
                tab.classList.toggle('active', tab.dataset.role === role);
            });
            
            if (role === 'beneficiary') {
                studentFields.classList.remove('hidden');
                donorFields.classList.add('hidden');
            } else {
                studentFields.classList.add('hidden');
                donorFields.classList.remove('hidden');
            }
        }
        
        // Rol tab tıklama
        roleTabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                switchRole(this.dataset.role);
            });
        });
        
        // Kurumsal checkbox
        isCorporate.addEventListener('change', function() {
            companyField.classList.toggle('hidden', !this.checked);
        });
        
        // URL parametresinden rol al
        var params = new URLSearchParams(window.location.search);
        if (params.get('rol') === 'bagisci') {
            switchRole('donor');
        }
        
        // Form gönder
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            hideAlert();
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Kaydediliyor...';
            
            var formData = new FormData(form);
            var data = {};
            formData.forEach(function(value, key) {
                data[key] = value;
            });
            
            data.is_corporate = isCorporate.checked;
            
            fetch('/api/v1/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(function(res) { return res.json(); })
            .then(function(result) {
                if (result.success) {
                    showAlert('Kayıt başarılı! Yönlendiriliyorsunuz...', 'success');
                    setTimeout(function() {
                        window.location.href = '/panel';
                    }, 1500);
                } else {
                    var msg = result.message || 'Kayıt başarısız';
                    if (result.errors) {
                        var errors = Object.values(result.errors);
                        if (errors.length > 0) msg += ': ' + errors.join(', ');
                    }
                    showAlert(msg, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Kayıt Ol';
                }
            })
            .catch(function(err) {
                showAlert('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Kayıt Ol';
            });
        });
        
        // Sayfa yüklendiğinde üniversiteleri getir
        loadUniversities();
    })();
    </script>
</body>
</html>
