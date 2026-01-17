<?php
/**
 * Kampüs Portal - Ana Giriş Noktası
 * 
 * Tüm istekler bu dosya üzerinden yönlendirilir.
 */

// Autoloader
require_once __DIR__ . '/autoload.php';

// Uygulama başlat
$app = Core\App::getInstance();

// Router
$router = new Core\Router();

// =====================================================
// WEB ROUTES
// =====================================================

// Ana sayfa
$router->get('/', function() {
    require __DIR__ . '/shared/views/home.php';
});

// Auth sayfaları
$router->get('/giris', function() {
    require __DIR__ . '/modules/auth/pages/login.php';
});

$router->get('/kayit', function() {
    require __DIR__ . '/modules/auth/pages/register.php';
});

$router->get('/cikis', function() {
    Core\Auth::logout();
    Core\Response::redirect('/giris');
});

// =====================================================
// API ROUTES
// =====================================================

$router->group(['prefix' => 'api/v1'], function($router) {
    
    // Auth
    $router->post('/auth/login', 'Modules\\Auth\\Controllers\\AuthController@login');
    $router->post('/auth/register', 'Modules\\Auth\\Controllers\\AuthController@register');
    $router->post('/auth/logout', 'Modules\\Auth\\Controllers\\AuthController@logout');
    $router->get('/auth/me', 'Modules\\Auth\\Controllers\\AuthController@me');
    
    // Universities (public)
    $router->get('/universities', 'Modules\\Tenant\\Controllers\\UniversityController@index');
    $router->get('/universities/{id}', 'Modules\\Tenant\\Controllers\\UniversityController@show');
    
    // Restaurants (public)
    $router->get('/restaurants', 'Modules\\Restaurant\\Controllers\\RestaurantController@index');
    $router->get('/restaurants/{id}', 'Modules\\Restaurant\\Controllers\\RestaurantController@show');
    $router->get('/restaurants/{id}/menu', 'Modules\\Restaurant\\Controllers\\RestaurantController@menu');
    
    // Protected routes
    $router->group(['middleware' => ['AuthMiddleware']], function($router) {
        
        // Profile
        $router->get('/profile', 'Modules\\Auth\\Controllers\\ProfileController@show');
        $router->put('/profile', 'Modules\\Auth\\Controllers\\ProfileController@update');
        
        // Beneficiary
        $router->get('/beneficiary/quota', 'Modules\\Beneficiary\\Controllers\\BeneficiaryController@quota');
        $router->post('/beneficiary/verify', 'Modules\\Beneficiary\\Controllers\\BeneficiaryController@verify');
        
        // Reservations
        $router->get('/reservations', 'Modules\\Reservation\\Controllers\\ReservationController@index');
        $router->post('/reservations', 'Modules\\Reservation\\Controllers\\ReservationController@store');
        $router->get('/reservations/{id}', 'Modules\\Reservation\\Controllers\\ReservationController@show');
        $router->post('/reservations/{id}/extend', 'Modules\\Reservation\\Controllers\\ReservationController@extend');
        $router->delete('/reservations/{id}', 'Modules\\Reservation\\Controllers\\ReservationController@cancel');
        
        // Vouchers
        $router->post('/vouchers/generate', 'Modules\\Voucher\\Controllers\\VoucherController@generate');
        $router->post('/vouchers/redeem', 'Modules\\Voucher\\Controllers\\VoucherController@redeem');
        
        // Donations
        $router->get('/donations', 'Modules\\Donation\\Controllers\\DonationController@index');
        $router->post('/donations', 'Modules\\Donation\\Controllers\\DonationController@store');
        $router->get('/donations/{id}', 'Modules\\Donation\\Controllers\\DonationController@show');
        
        // Ratings
        $router->post('/ratings', 'Modules\\Restaurant\\Controllers\\RatingController@store');
    });
    
    // Admin routes
    $router->group(['prefix' => 'admin', 'middleware' => ['AuthMiddleware', 'AdminMiddleware']], function($router) {
        
        // Dashboard
        $router->get('/dashboard', 'Modules\\Admin\\Controllers\\DashboardController@index');
        
        // Users
        $router->get('/users', 'Modules\\Admin\\Controllers\\UserController@index');
        $router->post('/users', 'Modules\\Admin\\Controllers\\UserController@store');
        $router->get('/users/{id}', 'Modules\\Admin\\Controllers\\UserController@show');
        $router->put('/users/{id}', 'Modules\\Admin\\Controllers\\UserController@update');
        $router->delete('/users/{id}', 'Modules\\Admin\\Controllers\\UserController@destroy');
        
        // Settlements
        $router->get('/settlements', 'Modules\\Settlement\\Controllers\\SettlementController@index');
        $router->post('/settlements/calculate', 'Modules\\Settlement\\Controllers\\SettlementController@calculate');
        $router->post('/settlements/{id}/approve', 'Modules\\Settlement\\Controllers\\SettlementController@approve');
        
        // Reports
        $router->get('/reports/donations', 'Modules\\Admin\\Controllers\\ReportController@donations');
        $router->get('/reports/usage', 'Modules\\Admin\\Controllers\\ReportController@usage');
    });
});

// Dispatch
$router->dispatch();

