<?php
/**
 * Clase Database
 * Maneja la conexion a MySQL mediante PDO usando el patron Singleton,
 * evitando abrir multiples conexiones innecesarias.
 *
 * Ruta: app/config/database.php
 */

class Database
{
    private static ?PDO $instance = null;

    /**
     * Constructor privado: no se permite instanciar directamente.
     */
    private function __construct()
    {
    }

    /**
     * Devuelve la instancia unica de conexion PDO.
     *
     * Soporta 3 formas de configuracion (en este orden de prioridad):
     *   1. DATABASE_URL / MYSQL_URL con formato mysql://usuario:clave@host:puerto/basedatos
     *      (formato usado por Railway, Render y la mayoria de hostings modernos)
     *   2. Variables MYSQLHOST / MYSQLUSER / MYSQLPASSWORD / MYSQLDATABASE / MYSQLPORT
     *      (nombres que Railway asigna automaticamente al agregar el plugin de MySQL)
     *   3. Variables DB_HOST / DB_USER / DB_PASS / DB_NAME / DB_PORT del archivo .env
     *      (para hosting tradicional o desarrollo local)
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $creds = self::resolveCredentials();

            $dsn = "mysql:host={$creds['host']};port={$creds['port']};dbname={$creds['name']};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, // Usa prepared statements reales (previene SQL Injection)
                PDO::ATTR_PERSISTENT         => false,
            ];

            try {
                self::$instance = new PDO($dsn, $creds['user'], $creds['pass'], $options);
            } catch (PDOException $e) {
                // Nunca se debe exponer el detalle de la conexion en produccion
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    die('Error de conexion a la base de datos: ' . $e->getMessage());
                }

                error_log('DB Connection Error: ' . $e->getMessage());
                die('No fue posible conectar con la base de datos. Intente mas tarde.');
            }
        }

        return self::$instance;
    }

    /**
     * Resuelve las credenciales de conexion probando, en orden, las
     * distintas convenciones de variables de entorno explicadas arriba.
     */
    private static function resolveCredentials(): array
    {
        // 1. URL de conexion completa (Railway / Render / la mayoria de PaaS modernos)
        $url = Env::get('DATABASE_URL', Env::get('MYSQL_URL', null));

        if (!empty($url)) {
            $parts = parse_url($url);

            if ($parts !== false) {
                return [
                    'host' => $parts['host'] ?? '127.0.0.1',
                    'port' => (string) ($parts['port'] ?? '3306'),
                    'name' => isset($parts['path']) ? ltrim($parts['path'], '/') : 'bingo_saas',
                    'user' => $parts['user'] ?? 'root',
                    'pass' => $parts['pass'] ?? '',
                ];
            }
        }

        // 2. Variables automaticas del plugin MySQL de Railway
        $railwayHost = Env::get('MYSQLHOST', null);

        if (!empty($railwayHost)) {
            return [
                'host' => $railwayHost,
                'port' => (string) Env::get('MYSQLPORT', '3306'),
                'name' => Env::get('MYSQLDATABASE', 'railway'),
                'user' => Env::get('MYSQLUSER', 'root'),
                'pass' => Env::get('MYSQLPASSWORD', ''),
            ];
        }

        // 3. Variables clasicas del archivo .env (hosting tradicional / local)
        return [
            'host' => Env::get('DB_HOST', '127.0.0.1'),
            'port' => (string) Env::get('DB_PORT', '3306'),
            'name' => Env::get('DB_NAME', 'bingo_saas'),
            'user' => Env::get('DB_USER', 'root'),
            'pass' => Env::get('DB_PASS', ''),
        ];
    }

    /**
     * Evita la clonacion de la instancia (patron Singleton).
     */
    private function __clone()
    {
    }
}
