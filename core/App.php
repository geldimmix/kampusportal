<?php
/**
 * Ana Uygulama Sınıfı
 */

namespace Core;

class App
{
    private static ?App $instance = null;
    private array $config = [];
    private ?Database $db = null;
    
    private function __construct()
    {
        $this->loadConfig();
        $this->setTimezone();
        $this->startSession();
    }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function loadConfig(): void
    {
        $this->config['app'] = require __DIR__ . '/../config/app.php';
        $this->config['database'] = require __DIR__ . '/../config/database.php';
    }
    
    private function setTimezone(): void
    {
        date_default_timezone_set($this->config['app']['timezone']);
    }
    
    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $sessionConfig = $this->config['app']['session'];
            
            session_set_cookie_params([
                'lifetime' => $sessionConfig['lifetime'] * 60,
                'path' => '/',
                'secure' => $sessionConfig['secure'],
                'httponly' => $sessionConfig['httponly'],
                'samesite' => $sessionConfig['samesite']
            ]);
            
            session_start();
        }
    }
    
    public function config(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    public function db(): Database
    {
        if ($this->db === null) {
            $this->db = Database::getInstance();
        }
        return $this->db;
    }
    
    public function isDebug(): bool
    {
        return (bool) $this->config('app.debug', false);
    }
    
    public function baseUrl(): string
    {
        return rtrim($this->config('app.url', ''), '/');
    }
}

