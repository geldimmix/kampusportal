<?php
/**
 * Admin Yetki Middleware
 */

namespace Shared\Middleware;

use Core\Auth;
use Core\Response;

class AdminMiddleware
{
    private array $allowedRoles = [
        'super_admin',
        'university_admin',
        'foundation_admin',
        'foundation_staff'
    ];
    
    public function handle(): void
    {
        if (!Auth::hasRole($this->allowedRoles)) {
            Response::forbidden('Bu sayfaya erişim yetkiniz yok');
        }
    }
}

