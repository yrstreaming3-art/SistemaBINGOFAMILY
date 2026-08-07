<?php
/**
 * Front Controller
 * Punto de entrada unico de toda la aplicacion.
 * Todas las peticiones HTTP son redirigidas aqui por .htaccess
 *
 * Ruta: public/index.php
 */

// ==========================================
// 1. Configuracion base y variables de entorno
// ==========================================
require_once dirname(__DIR__) . '/app/config/config.php';

// ==========================================
// 2. Sesion segura
// ==========================================
require_once dirname(__DIR__) . '/app/config/session.php';

// ==========================================
// 3. Conexion a base de datos
// ==========================================
require_once dirname(__DIR__) . '/app/config/database.php';

// ==========================================
// 4. Autoload manual de clases core y helpers
// ==========================================
$coreClasses = [
    APP_PATH . '/core/Controller.php',
    APP_PATH . '/core/Model.php',
    APP_PATH . '/core/Router.php',
    APP_PATH . '/helpers/SecurityHelper.php',
    APP_PATH . '/helpers/AuthHelper.php',
    APP_PATH . '/middlewares/AuthMiddleware.php',
];

foreach ($coreClasses as $classFile) {
    require_once $classFile;
}

// ==========================================
// 5. Cabeceras de seguridad HTTP
// ==========================================
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ==========================================
// 6. Enrutamiento y despacho de la peticion
// ==========================================
$router = new Router();

// Rutas explicitas principales (ademas del enrutamiento por convencion)
$router->get('', 'Dashboard', 'index');
$router->get('auth/login', 'Auth', 'login');
$router->post('auth/authenticate', 'Auth', 'authenticate');
$router->get('auth/logout', 'Auth', 'logout');
$router->get('dashboard', 'Dashboard', 'index');

$router->dispatch();
