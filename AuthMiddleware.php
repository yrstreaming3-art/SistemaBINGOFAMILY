<?php
/**
 * AuthMiddleware
 * Middleware de seguridad encargado de verificar que el usuario
 * este autenticado y, opcionalmente, que posea el rol requerido
 * antes de permitir el acceso a un controlador/metodo.
 *
 * Ruta: app/middlewares/AuthMiddleware.php
 *
 * Uso dentro de un controlador:
 *   AuthMiddleware::handle();                     // solo exige sesion activa
 *   AuthMiddleware::handle(ROLE_SUPERADMIN);       // exige sesion + rol especifico
 *   AuthMiddleware::handle([ROLE_SUPERADMIN, ROLE_CLIENTE]); // exige uno de varios roles
 */

class AuthMiddleware
{
    /**
     * @param string|array|null $rolesPermitidos Rol o lista de roles autorizados
     */
    public static function handle(string|array|null $rolesPermitidos = null): void
    {
        // 1. Verifica que exista una sesion activa y valida (control de inactividad)
        if (!AuthHelper::isAuthenticated() || !AuthHelper::checkInactivity()) {
            self::denyAndRedirectToLogin();
        }

        // 2. Si se especificaron roles, valida que el usuario pertenezca a ellos
        if ($rolesPermitidos !== null) {
            $rolesPermitidos = is_array($rolesPermitidos) ? $rolesPermitidos : [$rolesPermitidos];

            if (!in_array(AuthHelper::rol(), $rolesPermitidos, true)) {
                self::denyAccessForbidden();
            }
        }
    }

    /**
     * Middleware inverso: se usa en paginas como el login para evitar
     * que un usuario ya autenticado vuelva a ver el formulario.
     */
    public static function redirectIfAuthenticated(): void
    {
        if (AuthHelper::isAuthenticated()) {
            header('Location: ' . URLROOT . '/dashboard');
            exit;
        }
    }

    protected static function denyAndRedirectToLogin(): void
    {
        header('Location: ' . URLROOT . '/auth/login');
        exit;
    }

    protected static function denyAccessForbidden(): void
    {
        http_response_code(403);
        require_once VIEWS_PATH . '/errors/403.php';
        exit;
    }
}
