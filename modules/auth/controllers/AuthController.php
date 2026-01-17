<?php
/**
 * Kimlik Doğrulama Controller
 */

namespace Modules\Auth\Controllers;

use Core\Auth;
use Core\Database;
use Core\Response;

class AuthController
{
    private Database $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * POST /api/v1/auth/login
     */
    public function login(): void
    {
        $data = $this->getJsonInput();
        
        // Validasyon
        $errors = [];
        if (empty($data['email'])) {
            $errors['email'] = 'E-posta adresi gerekli';
        }
        if (empty($data['password'])) {
            $errors['password'] = 'Şifre gerekli';
        }
        
        if (!empty($errors)) {
            Response::validationError($errors);
        }
        
        // Giriş denemesi
        $user = Auth::attempt($data['email'], $data['password']);
        
        if ($user === null) {
            Response::error('E-posta veya şifre hatalı', 401);
        }
        
        // Session'a kaydet
        Auth::login($user);
        
        Response::success([
            'user' => $user
        ], 'Giriş başarılı');
    }
    
    /**
     * POST /api/v1/auth/register
     */
    public function register(): void
    {
        $data = $this->getJsonInput();
        
        // Validasyon
        $errors = $this->validateRegistration($data);
        
        if (!empty($errors)) {
            Response::validationError($errors);
        }
        
        // E-posta kontrolü
        $existing = $this->db->queryOne(
            "SELECT id FROM users WHERE email = :email",
            ['email' => $data['email']]
        );
        
        if ($existing) {
            Response::error('Bu e-posta adresi zaten kayıtlı', 400);
        }
        
        // Üniversiteden tenant bul (öğrenci için)
        $tenantId = null;
        if (($data['role'] ?? 'beneficiary') === 'beneficiary' && !empty($data['university_id'])) {
            $tenant = $this->db->queryOne(
                "SELECT id FROM tenants WHERE university_id = :uid AND is_active = true",
                ['uid' => $data['university_id']]
            );
            if ($tenant) {
                $tenantId = $tenant['id'];
            }
        }
        
        // Kullanıcı oluştur
        try {
            $this->db->beginTransaction();
            
            $userId = $this->db->insert('users', [
                'email' => $data['email'],
                'password_hash' => Auth::hashPassword($data['password']),
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone' => $data['phone'] ?? null,
                'role' => $data['role'] ?? 'beneficiary',
                'tenant_id' => $tenantId,
                'is_active' => true
            ]);
            
            // Rol'e göre ek tablo kaydı
            if (($data['role'] ?? 'beneficiary') === 'beneficiary') {
                $beneficiaryId = $this->db->insert('beneficiaries', [
                    'user_id' => $userId,
                    'tenant_id' => $tenantId,
                    'type' => 'university',
                    'verification_status' => 'pending'
                ]);
                
                // Üniversite detayları
                if (!empty($data['university_id'])) {
                    $this->db->insert('beneficiary_university', [
                        'beneficiary_id' => $beneficiaryId,
                        'university_id' => $data['university_id'],
                        'student_number' => $data['student_number'] ?? null,
                        'faculty' => $data['faculty'] ?? null,
                        'department' => $data['department'] ?? null
                    ]);
                }
            } elseif (($data['role'] ?? '') === 'donor') {
                $this->db->insert('donors', [
                    'user_id' => $userId,
                    'is_corporate' => ($data['is_corporate'] ?? false) === true || ($data['is_corporate'] ?? '') === 'true',
                    'company_name' => $data['company_name'] ?? null
                ]);
            }
            
            $this->db->commit();
            
            // Kullanıcıyı getir
            $user = $this->db->queryOne(
                "SELECT id, email, first_name, last_name, role, tenant_id FROM users WHERE id = :id",
                ['id' => $userId]
            );
            
            // Otomatik giriş
            Auth::login($user);
            
            Response::success([
                'user' => $user
            ], 'Kayıt başarılı', 201);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log('Register error: ' . $e->getMessage());
            Response::serverError('Kayıt sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }
    
    /**
     * POST /api/v1/auth/logout
     */
    public function logout(): void
    {
        Auth::logout();
        Response::success(null, 'Çıkış yapıldı');
    }
    
    /**
     * GET /api/v1/auth/me
     */
    public function me(): void
    {
        if (!Auth::check()) {
            Response::unauthorized();
        }
        
        $user = Auth::user();
        
        if ($user === null) {
            Response::unauthorized();
        }
        
        // Ek bilgiler
        $additionalInfo = [];
        
        if ($user['role'] === 'beneficiary') {
            $additionalInfo['beneficiary'] = $this->db->queryOne(
                "SELECT * FROM beneficiaries WHERE user_id = :id",
                ['id' => $user['id']]
            );
        } elseif ($user['role'] === 'donor') {
            $additionalInfo['donor'] = $this->db->queryOne(
                "SELECT * FROM donors WHERE user_id = :id",
                ['id' => $user['id']]
            );
        }
        
        Response::success([
            'user' => $user,
            ...$additionalInfo
        ]);
    }
    
    /**
     * JSON input al
     */
    private function getJsonInput(): array
    {
        $json = file_get_contents('php://input');
        return json_decode($json, true) ?? [];
    }
    
    /**
     * Kayıt validasyonu
     */
    private function validateRegistration(array $data): array
    {
        $errors = [];
        
        if (empty($data['email'])) {
            $errors['email'] = 'E-posta adresi gerekli';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Geçerli bir e-posta adresi girin';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Şifre gerekli';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Şifre en az 8 karakter olmalı';
        }
        
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'Ad gerekli';
        }
        
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Soyad gerekli';
        }
        
        return $errors;
    }
}

