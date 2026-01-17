<?php
/**
 * Kimlik Doğrulama Sınıfı
 */

namespace Core;

class Auth
{
    private static ?array $user = null;
    
    /**
     * Kullanıcı girişi
     */
    public static function login(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['tenant_id'] = $user['tenant_id'];
        $_SESSION['login_time'] = time();
        
        self::$user = $user;
        
        // Son giriş zamanını güncelle
        $db = Database::getInstance();
        $db->execute(
            "UPDATE users SET last_login_at = NOW(), last_login_ip = :ip WHERE id = :id",
            ['ip' => $_SERVER['REMOTE_ADDR'] ?? null, 'id' => $user['id']]
        );
    }
    
    /**
     * Kullanıcı çıkışı
     */
    public static function logout(): void
    {
        self::$user = null;
        session_destroy();
        
        // Yeni session başlat
        session_start();
        session_regenerate_id(true);
    }
    
    /**
     * Giriş yapılmış mı?
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Giriş yapan kullanıcı ID'si
     */
    public static function id(): ?string
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Giriş yapan kullanıcı rolü
     */
    public static function role(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Tenant ID
     */
    public static function tenantId(): ?string
    {
        return $_SESSION['tenant_id'] ?? null;
    }
    
    /**
     * Giriş yapan kullanıcı bilgileri
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        
        if (self::$user === null) {
            $db = Database::getInstance();
            self::$user = $db->queryOne(
                "SELECT * FROM users WHERE id = :id AND is_active = true",
                ['id' => self::id()]
            );
        }
        
        return self::$user;
    }
    
    /**
     * Belirli bir role sahip mi?
     */
    public static function hasRole(string|array $roles): bool
    {
        $userRole = self::role();
        
        if ($userRole === null) {
            return false;
        }
        
        // Super admin her şeye yetkili
        if ($userRole === 'super_admin') {
            return true;
        }
        
        if (is_string($roles)) {
            return $userRole === $roles;
        }
        
        return in_array($userRole, $roles);
    }
    
    /**
     * Belirli bir yetkiye sahip mi?
     */
    public static function hasPermission(string $permission): bool
    {
        $role = self::role();
        
        if ($role === null) {
            return false;
        }
        
        // Super admin her şeye yetkili
        if ($role === 'super_admin') {
            return true;
        }
        
        $db = Database::getInstance();
        $result = $db->queryOne(
            "SELECT 1 FROM role_permissions WHERE role = :role AND (permission = :perm OR permission = '*')",
            ['role' => $role, 'perm' => $permission]
        );
        
        return $result !== null;
    }
    
    /**
     * Şifre hash'le
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
    
    /**
     * Şifre doğrula
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Giriş doğrulaması yap
     */
    public static function attempt(string $email, string $password): ?array
    {
        $db = Database::getInstance();
        
        $user = $db->queryOne(
            "SELECT * FROM users WHERE email = :email AND is_active = true",
            ['email' => $email]
        );
        
        if ($user === null) {
            return null;
        }
        
        // Hesap kilitli mi?
        if ($user['locked_until'] !== null && strtotime($user['locked_until']) > time()) {
            return null;
        }
        
        if (!self::verifyPassword($password, $user['password_hash'])) {
            // Başarısız giriş sayısını artır
            $db->execute(
                "UPDATE users SET failed_login_attempts = failed_login_attempts + 1 WHERE id = :id",
                ['id' => $user['id']]
            );
            
            // 5 başarısız denemeden sonra 15 dakika kilitle
            if ($user['failed_login_attempts'] >= 4) {
                $db->execute(
                    "UPDATE users SET locked_until = NOW() + INTERVAL '15 minutes' WHERE id = :id",
                    ['id' => $user['id']]
                );
            }
            
            return null;
        }
        
        // Başarılı giriş - sayacı sıfırla
        $db->execute(
            "UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = :id",
            ['id' => $user['id']]
        );
        
        // Hassas bilgileri kaldır
        unset($user['password_hash'], $user['two_factor_secret']);
        
        return $user;
    }
}

