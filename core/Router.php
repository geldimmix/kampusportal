<?php
/**
 * Basit Router Sınıfı
 */

namespace Core;

class Router
{
    private array $routes = [];
    private string $prefix = '';
    private array $middleware = [];
    
    /**
     * Route grubu oluştur
     */
    public function group(array $options, callable $callback): void
    {
        $previousPrefix = $this->prefix;
        $previousMiddleware = $this->middleware;
        
        if (isset($options['prefix'])) {
            $this->prefix .= '/' . trim($options['prefix'], '/');
        }
        
        if (isset($options['middleware'])) {
            $this->middleware = array_merge($this->middleware, (array) $options['middleware']);
        }
        
        $callback($this);
        
        $this->prefix = $previousPrefix;
        $this->middleware = $previousMiddleware;
    }
    
    /**
     * GET route
     */
    public function get(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }
    
    /**
     * POST route
     */
    public function post(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }
    
    /**
     * PUT route
     */
    public function put(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }
    
    /**
     * DELETE route
     */
    public function delete(string $path, $handler, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }
    
    /**
     * Route ekle
     */
    private function addRoute(string $method, string $path, $handler, array $middleware): void
    {
        $fullPath = $this->prefix . '/' . trim($path, '/');
        $fullPath = '/' . trim($fullPath, '/');
        
        // {id} gibi parametreleri regex'e çevir
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $fullPath);
        $pattern = '#^' . $pattern . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'pattern' => $pattern,
            'handler' => $handler,
            'middleware' => array_merge($this->middleware, $middleware)
        ];
    }
    
    /**
     * Request'i işle
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Trailing slash'ı kaldır
        $uri = rtrim($uri, '/') ?: '/';
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Parametreleri filtrele (sadece named groups)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Middleware'leri çalıştır
                foreach ($route['middleware'] as $middleware) {
                    $this->runMiddleware($middleware);
                }
                
                // Handler'ı çalıştır
                $this->runHandler($route['handler'], $params);
                return;
            }
        }
        
        // 404 Not Found
        Response::notFound('Sayfa bulunamadı');
    }
    
    /**
     * Middleware çalıştır
     */
    private function runMiddleware(string $middleware): void
    {
        $class = "Shared\\Middleware\\{$middleware}";
        
        if (class_exists($class)) {
            $instance = new $class();
            $instance->handle();
        }
    }
    
    /**
     * Handler çalıştır
     */
    private function runHandler($handler, array $params): void
    {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }
        
        if (is_string($handler)) {
            [$class, $method] = explode('@', $handler);
            
            if (class_exists($class)) {
                $instance = new $class();
                call_user_func_array([$instance, $method], $params);
            }
        }
    }
}

