<?php
/**
 * SecurityHelper
 * Funciones utilitarias de seguridad: proteccion CSRF, saneo de datos
 * y limitacion de intentos de inicio de sesion.
 *
 * Ruta: app/helpers/SecurityHelper.php
 */

class SecurityHelper
{
    /**
     * Genera (o reutiliza) un token CSRF almacenado en la sesion actual.
     */
    public static function generateCsrfToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf_token'];
    }

    /**
     * Valida el token CSRF recibido contra el almacenado en sesion.
     */
    public static function validateCsrfToken(?string $token): bool
    {
        if (empty($token) || empty($_SESSION['_csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['_csrf_token'], $token);
    }

    /**
     * Imprime un campo hidden listo para usar dentro de formularios.
     */
    public static function csrfField(): string
    {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Sanea texto para prevenir XSS al mostrarlo en HTML.
     */
    public static function sanitize(?string $value): string
    {
        return htmlspecialchars(trim($value ?? ''), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida formato de correo electronico.
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Registra un intento fallido de login para el identificador dado
     * (correo o IP) y retorna si el usuario esta bloqueado temporalmente.
     */
    public static function registerFailedAttempt(string $identifier): void
    {
        $key = 'login_attempts_' . md5($identifier);

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
        }

        $_SESSION[$key]['count']++;
        $_SESSION[$key]['last_attempt'] = time();
    }

    /**
     * Limpia el contador de intentos fallidos (login exitoso).
     */
    public static function clearFailedAttempts(string $identifier): void
    {
        $key = 'login_attempts_' . md5($identifier);
        unset($_SESSION[$key]);
    }

    /**
     * Verifica si el identificador esta actualmente bloqueado
     * por exceso de intentos fallidos.
     */
    public static function isLockedOut(string $identifier): bool
    {
        $key = 'login_attempts_' . md5($identifier);

        if (!isset($_SESSION[$key])) {
            return false;
        }

        $attempts = $_SESSION[$key];

        if ($attempts['count'] < MAX_LOGIN_ATTEMPTS) {
            return false;
        }

        $elapsed = time() - $attempts['last_attempt'];

        if ($elapsed > LOGIN_LOCKOUT_SECONDS) {
            // El tiempo de bloqueo expiro, se reinicia el contador
            unset($_SESSION[$key]);
            return false;
        }

        return true;
    }

    /**
     * Segundos restantes de bloqueo para el identificador indicado.
     */
    public static function secondsUntilUnlock(string $identifier): int
    {
        $key = 'login_attempts_' . md5($identifier);

        if (!isset($_SESSION[$key])) {
            return 0;
        }

        $elapsed = time() - $_SESSION[$key]['last_attempt'];
        $remaining = LOGIN_LOCKOUT_SECONDS - $elapsed;

        return max(0, $remaining);
    }
}
