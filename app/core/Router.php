<?php
/**
 * Clase Router
 * Sistema de enrutamiento propio (sin dependencias externas).
 * Analiza la URL solicitada y determina que controlador, metodo
 * y parametros deben ejecutarse.
 *
 * Ruta: app/core/Router.php
 *
 * Formato de URL soportado:
 *   /public/index.php?url=controlador/metodo/parametro1/parametro2
 *   Reescrito por .htaccess a:
 *   /controlador/metodo/parametro1/parametro2
 */

class Router
{
    protected string $controllerName = 'DashboardController';
    protected string $method = 'index';
    protected array $params = [];

    /** Rutas explicitamente registradas: ['GET|POST' => ['ruta' => callable|[controller, method]]] */
    protected array $routes = [];

    public function __construct()
    {
        $this->parseUrl();
    }

    /**
     * Registra una ruta GET.
     */
    public function get(string $route, string $controller, string $method): void
    {
        $this->routes['GET'][$this->normalize($route)] = [$controller, $method];
    }

    /**
     * Registra una ruta POST.
     */
    public function post(string $route, string $controller, string $method): void
    {
        $this->routes['POST'][$this->normalize($route)] = [$controller, $method];
    }

    protected function normalize(string $route): string
    {
        return trim($route, '/');
    }

    /**
     * Analiza el parametro "url" enviado por .htaccess y separa
     * controlador / metodo / parametros.
     */
    protected function parseUrl(): void
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $this->requestUri = $this->normalize($url);
        } else {
            $this->requestUri = '';
        }
    }

    protected string $requestUri = '';

    /**
     * Ejecuta el despachador: primero busca coincidencia en rutas
     * explicitas, y si no existe, intenta resolver por convencion
     * (controlador/metodo/parametros).
     */
    public function dispatch(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        // 1. Buscar en rutas explicitas
        if (isset($this->routes[$httpMethod][$this->requestUri])) {
            [$controller, $method] = $this->routes[$httpMethod][$this->requestUri];
            $this->execute($controller, $method, []);
            return;
        }

        // 2. Resolver por convencion: /controlador/metodo/param1/param2
        $segments = $this->requestUri === '' ? [] : explode('/', $this->requestUri);

        if (!empty($segments[0])) {
            $this->controllerName = $this->toStudlyCase($segments[0]) . 'Controller';
            unset($segments[0]);
        }

        if (!empty($segments[1])) {
            $this->method = $this->toCamelCase($segments[1]);
            unset($segments[1]);
        }

        $this->params = array_values($segments);

        $this->execute($this->controllerName, $this->method, $this->params);
    }

    protected function execute(string $controllerName, string $method, array $params): void
    {
        $controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            $this->notFound();
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            $this->notFound();
            return;
        }

        $controllerInstance = new $controllerName();

        if (!method_exists($controllerInstance, $method)) {
            $this->notFound();
            return;
        }

        call_user_func_array([$controllerInstance, $method], $params);
    }

    protected function toStudlyCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    protected function toCamelCase(string $string): string
    {
        $studly = $this->toStudlyCase($string);
        return lcfirst($studly);
    }

    protected function notFound(): void
    {
        http_response_code(404);
        require_once VIEWS_PATH . '/errors/404.php';
        exit;
    }
}
