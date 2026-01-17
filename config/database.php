<?php
/**
 * Veritabanı Yapılandırması
 */

return [
    'default' => 'pgsql',
    
    'connections' => [
        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => getenv('DB_HOST') ?: '159.89.145.26',
            'port'     => getenv('DB_PORT') ?: '5432',
            'database' => getenv('DB_NAME') ?: 'askida_destek',
            'username' => getenv('DB_USER') ?: 'askida_admin',
            'password' => getenv('DB_PASS') ?: 'AskidaDestek2024Secure',
            'charset'  => 'utf8',
            'schema'   => 'public',
        ]
    ]
];

