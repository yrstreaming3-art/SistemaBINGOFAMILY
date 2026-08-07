<?php
/**
 * Clase Controller
 * Controlador base del cual heredan todos los controladores
 * del sistema. Provee metodos utilitarios comunes.
 *
 * Ruta: app/core/Controller.php
 */

abstract class Controller
{
    /**
     * Carga un modelo por nombre. Ejemplo: $this->model('UsuarioModel')
     */
    protected function model(string $modelName)
    {
        $modelFile = APP_PATH . '/models/' . $modelName . '.php';

        if (!file_exists($modelFile)) {
            throw new RuntimeException("El modelo {$modelName} no existe.");
        }

        require_once $modelFile;

        return new $modelName();
    }

    /**
     * Renderiza una vista, envolviendola opcionalmente en el layout
     * principal del panel (header, sidebar, footer).
     *
     * @param string $view   Ruta relativa de la vista dentro de app/views (sin .php)
     * @param array  $data   Datos a extraer como variables dentro de la vista
     * @param bool   $layout Si es true, envuelve la vista con el layout del panel
     */
    protected function view(string $view, array $data = [], bool $layout = true): void
    {
        $viewFile = VIEWS_PATH . '/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            die("La vista '{$view}' no fue encontrada.");
        }

        // Extrae las variables del arreglo $data al scope local
        extract($data, EXTR_SKIP);

        if ($layout) {
            require_once VIEWS_PATH . '/layouts/header.php';
            require_once VIEWS_PATH . '/layouts/sidebar.php';
            echo '<main class="main-content">';
            require_once VIEWS_PATH . '/layouts/topbar.php';
            echo '<div class="content-wrapper">';
            require_once $viewFile;
            echo '</div>'; // .content-wrapper
            require_once VIEWS_PATH . '/layouts/footer.php';
            echo '</main>'; // .main-content
            require_once VIEWS_PATH . '/layouts/scripts.php';
        } else {
            require_once $viewFile;
        }
    }

    /**
     * Devuelve una respuesta en formato JSON y detiene la ejecucion.
     */
    protected function json($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Redirige a una ruta interna del sistema.
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . URLROOT . '/' . ltrim($path, '/'));
        exit;
    }

    /**
     * Obtiene datos enviados por POST de forma saneada.
     */
    protected function input(string $key, $default = null)
    {
        if (!isset($_POST[$key])) {
            return $default;
        }

        $value = trim($_POST[$key]);
        return $value === '' ? $default : $value;
    }

    /**
     * Verifica que la peticion sea POST, de lo contrario responde 405.
     */
    protected function onlyPost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            die('Metodo no permitido.');
        }
    }
}
