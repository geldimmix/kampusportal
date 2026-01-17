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
            --primary-hover: #1e3a8a;
            --primary-light: #dbeafe;
            --success: #059669;
            --success-light: #d1fae5;
            --text: #111827;
            --text-secondary: #6b7280;
            --bg: #ffffff;
            --bg-page: #f3f4f6;
            --border: #d1d5db;
            --error: #dc2626;
            --error-light: #fee2e2;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-page);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            line-height: 1.5;
        }
        
        .container {
            width: 100%;
            max-width: 480px;
        }
        
        .card {
            background: var(--bg);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 32px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text);
            margin-bottom: 20px;
        }
        
        .logo-box {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }
        
        .logo-name {
            font-weight: 600;
            font-size: 18px;
        }
        
        h1 {
            font-size: 22px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 4px;
        }
        
        .subtitle {
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        /* Role Seçici */
        .role-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            background: var(--bg-page);
            padding: 4px;
            border-radius: 6px;
        }
        
        .role-tab {
            flex: 1;
            padding: 10px 16px;
            border: none;
            background: transparent;
            border-radius: 4px;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all 0.15s;
        }
        
        .role-tab:hover {
            color: var(--text);
        }
        
        .role-tab.active {
            background: var(--bg);
            color: var(--primary);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        /* Form */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 6px;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: var(--bg);
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }
        
        .form-group input::placeholder {
            color: var(--text-secondary);
        }
        
        /* Checkbox */
        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }
        
        .checkbox-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }
        
        .checkbox-row label {
            font-size: 14px;
            color: var(--text);
            cursor: pointer;
        }
        
        /* Alert */
        .alert {
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 16px;
            display: none;
        }
        
        .alert.error {
            background: var(--error-light);
            color: var(--error);
            display: block;
        }
        
        .alert.success {
            background: var(--success-light);
            color: var(--success);
            display: block;
        }
        
        /* Buton */
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            cursor: pointer;
            transition: background 0.15s;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .btn-primary .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Footer */
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-secondary);
        }
        
        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .footer-text a:hover {
            text-decoration: underline;
        }
        
        /* Gizli alanlar */
        .hidden { display: none !important; }
        
        /* Loading state for select */
        select.loading {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24'%3E%3Cstyle%3E.spinner{transform-origin:center;animation:rotate 1s linear infinite}@keyframes rotate{100%{transform:rotate(360deg)}}%3C/style%3E%3Ccircle class='spinner' cx='12' cy='12' r='8' fill='none' stroke='%236b7280' stroke-width='2' stroke-dasharray='32' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <a href="/" class="logo">
                    <div class="logo-box">KP</div>
                    <span class="logo-name">Kampüs Portal</span>
                </a>
                <h1>Hesap Oluştur</h1>
                <p class="subtitle">Sisteme kayıt olarak başlayın</p>
            </div>
            
            <div id="alert" class="alert"></div>
            
            <div class="role-tabs">
                <button type="button" class="role-tab active" data-role="beneficiary">Öğrenci</button>
                <button type="button" class="role-tab" data-role="donor">Bağışçı</button>
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
                    <input type="password" id="password" name="password" minlength="8" placeholder="En az 8 karakter" required>
                </div>
                
                <!-- Öğrenci Alanları -->
                <div id="studentFields">
                    <div class="form-group">
                        <label for="university">Üniversite</label>
                        <select id="university" name="university_id" class="loading">
                            <option value="">Yükleniyor...</option>
                        </select>
                    </div>
                </div>
                
                <!-- Bağışçı Alanları -->
                <div id="donorFields" class="hidden">
                    <div class="checkbox-row">
                        <input type="checkbox" id="is_corporate" name="is_corporate">
                        <label for="is_corporate">Kurumsal bağışçıyım</label>
                    </div>
                    <div class="form-group hidden" id="companyField">
                        <label for="company_name">Şirket Adı</label>
                        <input type="text" id="company_name" name="company_name">
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" id="submitBtn">Kayıt Ol</button>
            </form>
            
            <p class="footer-text">
                Zaten hesabınız var mı? <a href="/giris">Giriş Yap</a>
            </p>
        </div>
    </div>
    
    <script>
    (function() {
        const form = document.getElementById('registerForm');
        const alert = document.getElementById('alert');
        const submitBtn = document.getElementById('submitBtn');
        const roleInput = document.getElementById('role');
        const studentFields = document.getElementById('studentFields');
        const donorFields = document.getElementById('donorFields');
        const universitySelect = document.getElementById('university');
        const roleTabs = document.querySelectorAll('.role-tab');
        const isCorporate = document.getElementById('is_corporate');
        const companyField = document.getElementById('companyField');
        
        // Üniversiteleri yükle
        function loadUniversities() {
            fetch('/api/v1/universities')
                .then(function(res) {
                    if (!res.ok) throw new Error('API hatası');
                    return res.json();
                })
                .then(function(data) {
                    universitySelect.innerHTML = '<option value="">Üniversite seçin</option>';
                    universitySelect.classList.remove('loading');
                    
                    if (data.success && data.data && data.data.length > 0) {
                        data.data.forEach(function(uni) {
                            var option = document.createElement('option');
                            option.value = uni.id;
                            option.textContent = uni.name + ' - ' + uni.city;
                            universitySelect.appendChild(option);
                        });
                    } else {
                        showAlert('Üniversite listesi yüklenemedi.', 'error');
                    }
                })
                .catch(function(err) {
                    console.error('Üniversite yükleme hatası:', err);
                    universitySelect.innerHTML = '<option value="">Yüklenemedi</option>';
                    universitySelect.classList.remove('loading');
                    showAlert('Üniversite listesi yüklenirken hata oluştu.', 'error');
                });
        }
        
        // Alert göster
        function showAlert(message, type) {
            alert.textContent = message;
            alert.className = 'alert ' + type;
        }
        
        // Alert gizle
        function hideAlert() {
            alert.className = 'alert';
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
            submitBtn.innerHTML = '<span class="spinner"></span>Kaydediliyor...';
            
            var formData = new FormData(form);
            var data = {};
            formData.forEach(function(value, key) {
                data[key] = value;
            });
            
            // Checkbox için düzeltme
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
                    submitBtn.textContent = 'Kayıt Ol';
                }
            })
            .catch(function(err) {
                showAlert('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kayıt Ol';
            });
        });
        
        // Sayfa yüklendiğinde üniversiteleri getir
        loadUniversities();
    })();
    </script>
</body>
</html>
