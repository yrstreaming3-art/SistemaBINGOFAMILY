<?php
/**
 * AuthHelper
 * Centraliza el manejo de la sesion del usuario autenticado:
 * inicio de sesion, cierre de sesion y consultas de estado.
 *
 * Ruta: app/helpers/AuthHelper.php
 */

class AuthHelper
{
    /**
     * Inicia la sesion de un usuario autenticado, regenerando el ID
     * de sesion para prevenir ataques de fijacion de sesion.
     */
    public static function login(array $usuario): void
    {
        session_regenerate_id(true);

        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email']  = $usuario['email'];
        $_SESSION['usuario_rol']    = $usuario['rol'];
        $_SESSION['cliente_id']     = $usuario['cliente_id'] ?? null;
        $_SESSION['_last_regeneration'] = time();
        $_SESSION['_last_activity'] = time();
    }

    /**
     * Cierra la sesion actual del usuario por completo.
     */
    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    public static function isAuthenticated(): bool
    {
        return !empty($_SESSION['usuario_id']);
    }

    public static function id()
    {
        return $_SESSION['usuario_id'] ?? null;
    }

    public static function nombre(): string
    {
        return $_SESSION['usuario_nombre'] ?? '';
    }

    public static function rol(): ?string
    {
        return $_SESSION['usuario_rol'] ?? null;
    }

    public static function esSuperAdmin(): bool
    {
        return self::rol() === ROLE_SUPERADMIN;
    }

    public static function esCliente(): bool
    {
        return self::rol() === ROLE_CLIENTE;
    }

    public static function clienteId()
    {
        return $_SESSION['cliente_id'] ?? null;
    }

    /**
     * Actualiza la marca de tiempo de ultima actividad y verifica
     * inactividad prolongada (cierre de sesion automatico).
     */
    public static function checkInactivity(int $maxInactiveSeconds = SESSION_LIFETIME): bool
    {
        if (!self::isAuthenticated()) {
            return false;
        }

        $lastActivity = $_SESSION['_last_activity'] ?? time();

        if (time() - $lastActivity > $maxInactiveSeconds) {
            self::logout();
            return false;
        }

        $_SESSION['_last_activity'] = time();
        return true;
    }
}
