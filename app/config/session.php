<?php
/**
 * Configuracion segura de sesiones
 * Ruta: app/config/session.php
 *
 * Debe incluirse ANTES de cualquier salida HTML y despues de config.php
 */

if (session_status() === PHP_SESSION_NONE) {

    // Nombre de sesion personalizado (evita usar el nombre por defecto PHPSESSID)
    session_name(SESSION_NAME);

    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    );

    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,   // Solo se envia por HTTPS si esta disponible
        'httponly' => true,       // No accesible via JavaScript
        'samesite' => 'Lax',      // Mitiga ataques CSRF
    ]);

    ini_set('session.use_strict_mode', '1');
    ini_set('session.gc_maxlifetime', (string) SESSION_LIFETIME);

    session_start();

    // Regeneracion periodica del ID de sesion para mitigar session fixation
    if (!isset($_SESSION['_last_regeneration'])) {
        $_SESSION['_last_regeneration'] = time();
    } elseif (time() - $_SESSION['_last_regeneration'] > 900) { // cada 15 minutos
        session_regenerate_id(true);
        $_SESSION['_last_regeneration'] = time();
    }
}
