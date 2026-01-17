<?php
/**
 * Üniversite Controller
 */

namespace Modules\Tenant\Controllers;

use Core\Database;
use Core\Response;

class UniversityController
{
    private Database $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * GET /api/v1/universities
     * Tüm üniversiteleri listele
     */
    public function index(): void
    {
        $city = $_GET['city'] ?? null;
        $type = $_GET['type'] ?? null;
        $active = $_GET['active'] ?? null;
        $search = $_GET['search'] ?? null;
        
        $sql = "SELECT id, name, city, type, founded_year, is_active FROM universities WHERE 1=1";
        $params = [];
        
        if ($city) {
            $sql .= " AND city = :city";
            $params['city'] = $city;
        }
        
        if ($type) {
            $sql .= " AND type = :type";
            $params['type'] = $type;
        }
        
        if ($active !== null) {
            $sql .= " AND is_active = :active";
            $params['active'] = $active === 'true';
        }
        
        if ($search) {
            $sql .= " AND (name ILIKE :search OR city ILIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY name ASC";
        
        $universities = $this->db->query($sql, $params);
        
        Response::success($universities);
    }
    
    /**
     * GET /api/v1/universities/{id}
     * Tek üniversite detayı
     */
    public function show(string $id): void
    {
        $university = $this->db->queryOne(
            "SELECT * FROM universities WHERE id = :id",
            ['id' => $id]
        );
        
        if (!$university) {
            Response::notFound('Üniversite bulunamadı');
        }
        
        // İlgili tenant varsa ekle
        $tenant = $this->db->queryOne(
            "SELECT id, name, daily_quota_per_beneficiary, max_meal_price 
             FROM tenants WHERE university_id = :id AND is_active = true",
            ['id' => $id]
        );
        
        $university['tenant'] = $tenant;
        
        Response::success($university);
    }
    
    /**
     * Şehirleri listele
     */
    public function cities(): void
    {
        $cities = $this->db->query(
            "SELECT DISTINCT city FROM universities ORDER BY city ASC"
        );
        
        Response::success(array_column($cities, 'city'));
    }
}

