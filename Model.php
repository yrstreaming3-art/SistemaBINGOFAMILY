<?php
/**
 * Clase Model
 * Modelo base del cual heredan todos los modelos del sistema.
 * Provee la conexion PDO y metodos utilitarios de consulta segura.
 *
 * Ruta: app/core/Model.php
 */

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Ejecuta una consulta preparada con parametros y retorna el statement.
     * Siempre utiliza sentencias preparadas para prevenir SQL Injection.
     */
    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Retorna un unico registro o null.
     */
    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $result = $this->query($sql, $params)->fetch();
        return $result === false ? null : $result;
    }

    /**
     * Retorna todos los registros encontrados.
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Ejecuta un INSERT y retorna el ultimo ID generado.
     */
    protected function insert(string $sql, array $params = []): string
    {
        $this->query($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Ejecuta un UPDATE/DELETE y retorna el numero de filas afectadas.
     */
    protected function execute(string $sql, array $params = []): int
    {
        return $this->query($sql, $params)->rowCount();
    }
}
