<?php
/**
 * AuthController
 * Controla el flujo de autenticacion: formulario de login,
 * validacion de credenciales y cierre de sesion.
 *
 * Ruta: app/controllers/AuthController.php
 */

class AuthController extends Controller
{
    protected UsuarioModel $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = $this->model('UsuarioModel');
    }

    /**
     * Muestra el formulario de inicio de sesion.
     * GET /auth/login
     */
    public function login(): void
    {
        AuthMiddleware::redirectIfAuthenticated();

        $data = [
            'titulo'     => 'Iniciar Sesion',
            'csrfToken'  => SecurityHelper::generateCsrfToken(),
            'error'      => $_SESSION['_flash_error'] ?? null,
        ];

        unset($_SESSION['_flash_error']);

        $this->view('auth/login', $data, layout: false);
    }

    /**
     * Procesa el envio del formulario de login.
     * POST /auth/authenticate
     */
    public function authenticate(): void
    {
        $this->onlyPost();

        $csrfToken = $this->input('csrf_token');

        if (!SecurityHelper::validateCsrfToken($csrfToken)) {
            $this->failLogin('Token de seguridad invalido. Intente nuevamente.');
        }

        $email    = strtolower((string) $this->input('email', ''));
        $password = (string) $this->input('password', '');

        if (!SecurityHelper::isValidEmail($email) || $password === '') {
            $this->failLogin('Debe ingresar un correo valido y su contrasena.');
        }

        // Control de fuerza bruta por correo
        if (SecurityHelper::isLockedOut($email)) {
            $segundos = SecurityHelper::secondsUntilUnlock($email);
            $minutos  = ceil($segundos / 60);
            $this->failLogin("Demasiados intentos fallidos. Intente nuevamente en {$minutos} minuto(s).");
        }

        $usuario = $this->usuarioModel->buscarPorEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            SecurityHelper::registerFailedAttempt($email);
            $this->failLogin('Credenciales incorrectas. Verifique su correo y contrasena.');
        }

        if ($usuario['estado'] !== 'activo') {
            $this->failLogin('Su cuenta se encuentra inactiva. Contacte al administrador.');
        }

        // Login exitoso
        SecurityHelper::clearFailedAttempts($email);
        AuthHelper::login($usuario);
        $this->usuarioModel->actualizarUltimoAcceso((int) $usuario['id']);

        $this->redirect('dashboard');
    }

    /**
     * Cierra la sesion activa.
     * GET /auth/logout
     */
    public function logout(): void
    {
        AuthHelper::logout();
        $this->redirect('auth/login');
    }

    /**
     * Guarda un mensaje de error en sesion (flash message) y
     * redirige de vuelta al formulario de login.
     */
    protected function failLogin(string $mensaje): void
    {
        $_SESSION['_flash_error'] = $mensaje;
        $this->redirect('auth/login');
    }
}
