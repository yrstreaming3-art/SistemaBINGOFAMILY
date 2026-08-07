<?php
/**
 * ClienteModel
 * Gestiona el acceso a datos de la tabla "clientes" (empresas/usuarios
 * finales que adquieren una licencia del sistema SaaS).
 *
 * Ruta: app/models/ClienteModel.php
 */

class ClienteModel extends Model
{
    public function total(): int
    {
        $resultado = $this->fetchOne("SELECT COUNT(*) AS total FROM clientes");
        return (int) ($resultado['total'] ?? 0);
    }

    public function totalActivos(): int
    {
        $resultado = $this->fetchOne(
            "SELECT COUNT(*) AS total FROM clientes WHERE estado = 'activo'"
        );
        return (int) ($resultado['total'] ?? 0);
    }

    public function totalPorVencer(int $dias = 7): int
    {
        $sql = "SELECT COUNT(*) AS total
                FROM clientes
                WHERE estado = 'activo'
                AND fecha_fin BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL :dias DAY)";

        $resultado = $this->fetchOne($sql, ['dias' => $dias]);
        return (int) ($resultado['total'] ?? 0);
    }

    public function listarRecientes(int $limite = 5): array
    {
        $sql = "SELECT id, nombre_empresa, subdominio, estado, fecha_fin
                FROM clientes
                ORDER BY creado_en DESC
                LIMIT :limite";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
