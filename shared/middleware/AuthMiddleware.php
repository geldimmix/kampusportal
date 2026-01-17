<?php
/**
 * Kimlik Doğrulama Middleware
 */

namespace Shared\Middleware;

use Core\Auth;
use Core\Response;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!Auth::check()) {
            // API isteği mi?
            if ($this->isApiRequest()) {
                Response::unauthorized();
            }
            
            // Web isteği - giriş sayfasına yönlendir
            Response::redirect('/giris');
        }
    }
    
    private function isApiRequest(): bool
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        return str_starts_with($uri, '/api/');
    }
}

