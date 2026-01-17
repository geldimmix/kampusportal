<?php
/**
 * Uygulama Yapılandırması
 */

return [
    'name' => 'Kampüs Portal',
    'version' => '1.0.0',
    'env' => getenv('APP_ENV') ?: 'production',
    'debug' => getenv('APP_DEBUG') ?: false,
    'url' => getenv('APP_URL') ?: 'https://kampusportal.com.tr',
    'timezone' => 'Europe/Istanbul',
    'locale' => 'tr_TR',
    
    // Oturum ayarları
    'session' => [
        'lifetime' => 120, // dakika
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ],
    
    // JWT ayarları
    'jwt' => [
        'secret' => getenv('JWT_SECRET') ?: 'your-256-bit-secret-key-change-in-production',
        'ttl' => 60, // dakika
        'refresh_ttl' => 20160, // 2 hafta
        'algorithm' => 'HS256'
    ],
    
    // Kupon ayarları
    'voucher' => [
        'ttl_minutes' => 5, // QR/OTP geçerlilik süresi
        'otp_length' => 6
    ],
    
    // Rezervasyon ayarları
    'reservation' => [
        'duration_minutes' => 30,
        'extension_minutes' => 10,
        'max_extensions' => 1
    ]
];

