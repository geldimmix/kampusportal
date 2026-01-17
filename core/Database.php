<?php
/**
 * Veritabanı Bağlantı Sınıfı (Singleton)
 */

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;
    
    private function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';
        $db = $config['connections'][$config['default']];
        
        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s',
            $db['driver'],
            $db['host'],
            $db['port'],
            $db['database']
        );
        
        try {
            $this->pdo = new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Timezone ayarla
            $this->pdo->exec("SET timezone = 'Europe/Istanbul'");
            
        } catch (PDOException $e) {
            throw new PDOException("Veritabanı bağlantı hatası: " . $e->getMessage());
        }
    }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
    
    /**
     * SELECT sorgusu çalıştır
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Tek satır getir
     */
    public function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * INSERT, UPDATE, DELETE çalıştır
     */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * INSERT ve ID döndür
     */
    public function insert(string $table, array $data): string
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders}) RETURNING id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        
        return $stmt->fetchColumn();
    }
    
    /**
     * UPDATE
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = "{$column} = :{$column}";
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $set) . " WHERE {$where}";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge($data, $whereParams));
        
        return $stmt->rowCount();
    }
    
    /**
     * DELETE
     */
    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
    
    /**
     * Transaction başlat
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Transaction onayla
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }
    
    /**
     * Transaction geri al
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }
    
    // Klonlamayı engelle
    private function __clone() {}
    
    // Unserialize'ı engelle
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}

