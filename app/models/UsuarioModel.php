<?php
/**
 * UsuarioModel
 * Gestiona el acceso a datos de la tabla "usuarios".
 *
 * Ruta: app/models/UsuarioModel.php
 */

class UsuarioModel extends Model
{
    /**
     * Busca un usuario activo por su correo electronico.
     */
    public function buscarPorEmail(string $email): ?array
    {
        $sql = "SELECT id, cliente_id, nombre, email, password, rol, estado
                FROM usuarios
                WHERE email = :email
                LIMIT 1";

        return $this->fetchOne($sql, ['email' => $email]);
    }

    /**
     * Busca un usuario por su ID.
     */
    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT id, cliente_id, nombre, email, rol, estado, creado_en
                FROM usuarios
                WHERE id = :id
                LIMIT 1";

        return $this->fetchOne($sql, ['id' => $id]);
    }

    /**
     * Crea un nuevo usuario con contrasena ya hasheada.
     */
    public function crear(array $datos): string
    {
        $sql = "INSERT INTO usuarios (cliente_id, nombre, email, password, rol, estado, creado_en)
                VALUES (:cliente_id, :nombre, :email, :password, :rol, :estado, NOW())";

        return $this->insert($sql, [
            'cliente_id' => $datos['cliente_id'] ?? null,
            'nombre'     => $datos['nombre'],
            'email'      => $datos['email'],
            'password'   => $datos['password'],
            'rol'        => $datos['rol'],
            'estado'     => $datos['estado'] ?? 'activo',
        ]);
    }

    /**
     * Actualiza la fecha del ultimo acceso exitoso del usuario.
     */
    public function actualizarUltimoAcceso(int $id): void
    {
        $this->execute(
            "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = :id",
            ['id' => $id]
        );
    }

    /**
     * Cuenta cuantos usuarios con rol super_admin existen (uso en instalacion).
     */
    public function contarSuperAdmins(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE rol = :rol";
        $resultado = $this->fetchOne($sql, ['rol' => ROLE_SUPERADMIN]);
        return (int) ($resultado['total'] ?? 0);
    }
}
