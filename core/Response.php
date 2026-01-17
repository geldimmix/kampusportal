<?php
/**
 * HTTP Response Helper
 */

namespace Core;

class Response
{
    /**
     * JSON yanıt döndür
     */
    public static function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Başarılı yanıt
     */
    public static function success($data = null, string $message = 'İşlem başarılı', int $status = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }
    
    /**
     * Hata yanıtı
     */
    public static function error(string $message, int $status = 400, $errors = null): void
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        self::json($response, $status);
    }
    
    /**
     * 401 Unauthorized
     */
    public static function unauthorized(string $message = 'Yetkilendirme gerekli'): void
    {
        self::error($message, 401);
    }
    
    /**
     * 403 Forbidden
     */
    public static function forbidden(string $message = 'Bu işlem için yetkiniz yok'): void
    {
        self::error($message, 403);
    }
    
    /**
     * 404 Not Found
     */
    public static function notFound(string $message = 'Kayıt bulunamadı'): void
    {
        self::error($message, 404);
    }
    
    /**
     * 422 Validation Error
     */
    public static function validationError(array $errors, string $message = 'Doğrulama hatası'): void
    {
        self::error($message, 422, $errors);
    }
    
    /**
     * 500 Server Error
     */
    public static function serverError(string $message = 'Sunucu hatası'): void
    {
        self::error($message, 500);
    }
    
    /**
     * Yönlendirme
     */
    public static function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header("Location: {$url}");
        exit;
    }
}

