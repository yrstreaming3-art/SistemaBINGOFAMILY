<?php
/**
 * Clase Env
 * Carga variables de entorno desde el archivo .env ubicado en la raiz
 * del proyecto, sin depender de librerias externas (Composer).
 *
 * Ruta: app/core/Env.php
 */

class Env
{
    protected static bool $loaded = false;

    /**
     * Carga el archivo .env indicado y define las variables
     * mediante putenv/$_ENV/$_SERVER.
     */
    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }

        if (!file_exists($path)) {
            // Si no existe .env, se intenta usar .env.example como referencia
            // pero jamas se debe usar en produccion sin configurar.
            self::$loaded = true;
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Ignorar comentarios y lineas vacias
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$name, $value] = array_map('trim', explode('=', $line, 2));

            // Elimina comillas envolventes si existen
            $value = trim($value, "\"'");

            if ($name !== '') {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }

        self::$loaded = true;
    }

    /**
     * Obtiene una variable de entorno con valor por defecto opcional.
     */
    public static function get(string $key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        // Normaliza valores booleanos comunes
        return match (strtolower($value)) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            'null', '(null)'   => null,
            default            => $value,
        };
    }
}
