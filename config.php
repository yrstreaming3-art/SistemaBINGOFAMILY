<?php
/**
 * Configuracion general del sistema
 * Ruta: app/config/config.php
 *
 * Este archivo centraliza las constantes de la aplicacion.
 * Los valores sensibles se cargan desde el archivo .env
 */

require_once __DIR__ . '/../core/Env.php';

// Carga las variables de entorno desde la raiz del proyecto
Env::load(dirname(__DIR__, 2) . '/.env');

// ==============================
// INFORMACION DE LA APLICACION
// ==============================
define('APP_NAME', Env::get('APP_NAME', 'Bingo SaaS'));
define('APP_ENV', Env::get('APP_ENV', 'production'));
define('APP_DEBUG', filter_var(Env::get('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN));
define('APP_VERSION', '1.0.0');

// URL base del sistema (sin barra final)
// Si el hosting es Railway, se detecta automaticamente el dominio publico
// asignado (RAILWAY_PUBLIC_DOMAIN). Si no existe, se usa APP_URL del .env.
$railwayDomain = Env::get('RAILWAY_PUBLIC_DOMAIN', null);

if (!empty($railwayDomain)) {
    define('URLROOT', 'https://' . $railwayDomain);
} else {
    define('URLROOT', rtrim(Env::get('APP_URL', 'http://localhost/bingo-saas/public'), '/'));
}

// Zona horaria
date_default_timezone_set(Env::get('APP_TIMEZONE', 'America/Bogota'));

// ==============================
// RUTAS DEL SISTEMA DE ARCHIVOS
// ==============================
define('BASE_PATH', dirname(__DIR__, 2));
define('APP_PATH', BASE_PATH . '/app');
define('VIEWS_PATH', APP_PATH . '/views');
define('STORAGE_PATH', BASE_PATH . '/storage');

// ==============================
// ROLES DEL SISTEMA
// ==============================
define('ROLE_SUPERADMIN', 'super_admin');
define('ROLE_CLIENTE', 'cliente');

// ==============================
// SEGURIDAD DE SESIONES
// ==============================
define('SESSION_NAME', Env::get('SESSION_NAME', 'bingo_saas_session'));
define('SESSION_LIFETIME', (int) Env::get('SESSION_LIFETIME', 7200));
define('HASH_COST', (int) Env::get('HASH_COST', 12));

// Maximo de intentos de login antes de bloqueo temporal
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_SECONDS', 300); // 5 minutos

// ==============================
// REPORTE DE ERRORES
// ==============================
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_PATH . '/logs/php_errors.log');
}
