-- ============================================================
-- BINGO SAAS - Script de base de datos inicial
-- Motor: MySQL 8+ / MariaDB 10.4+
-- Ruta: database/bingo_saas.sql
--
-- Este script crea la base de datos y las tablas necesarias
-- para la primera etapa del sistema (autenticacion y estructura
-- base multi-cliente). Los modulos de bingo (cartones, sorteos,
-- planes, pagos) se agregaran en etapas posteriores.
-- ============================================================

CREATE DATABASE IF NOT EXISTS `bingo_saas`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `bingo_saas`;

-- ============================================================
-- TABLA: clientes
-- Representa a cada empresa/organizacion que adquiere una
-- licencia del sistema SaaS.
-- ============================================================
CREATE TABLE IF NOT EXISTS `clientes` (
    `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nombre_empresa`   VARCHAR(150)        NOT NULL,
    `subdominio`       VARCHAR(80)         NOT NULL UNIQUE,
    `correo_contacto`  VARCHAR(150)        NOT NULL,
    `telefono`         VARCHAR(30)             NULL,
    `plan`             VARCHAR(50)         NOT NULL DEFAULT 'basico',
    `estado`           ENUM('activo','inactivo','suspendido') NOT NULL DEFAULT 'activo',
    `fecha_inicio`     DATE                    NULL,
    `fecha_fin`        DATE                    NULL,
    `creado_en`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en`   DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX `idx_clientes_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA: usuarios
-- Usuarios del sistema. El campo "rol" define si es
-- Super Administrador (gestiona todo el SaaS) o Cliente
-- (usuario final vinculado a un registro en "clientes").
-- ============================================================
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id`               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cliente_id`       INT UNSIGNED            NULL,
    `nombre`           VARCHAR(120)        NOT NULL,
    `email`            VARCHAR(150)        NOT NULL UNIQUE,
    `password`         VARCHAR(255)        NOT NULL,
    `rol`              ENUM('super_admin','cliente') NOT NULL DEFAULT 'cliente',
    `estado`           ENUM('activo','inactivo')     NOT NULL DEFAULT 'activo',
    `ultimo_acceso`    DATETIME                NULL,
    `creado_en`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en`   DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT `fk_usuarios_cliente`
        FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    INDEX `idx_usuarios_rol` (`rol`),
    INDEX `idx_usuarios_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLA: logs_actividad
-- Bitacora general de acciones relevantes del sistema
-- (inicios de sesion, cambios administrativos, etc.)
-- ============================================================
CREATE TABLE IF NOT EXISTS `logs_actividad` (
    `id`               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `usuario_id`       INT UNSIGNED            NULL,
    `accion`           VARCHAR(100)        NOT NULL,
    `descripcion`      VARCHAR(255)            NULL,
    `ip`               VARCHAR(45)             NULL,
    `creado_en`        DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT `fk_logs_usuario`
        FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    INDEX `idx_logs_accion` (`accion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DATOS INICIALES
-- Usuario Super Administrador por defecto.
--
--   Correo:     admin@bingosaas.com
--   Contrasena: Admin123!
--
-- IMPORTANTE: Esta contrasena debe cambiarse inmediatamente
-- despues del primer inicio de sesion en un entorno real.
-- El hash fue generado con bcrypt (compatible con la funcion
-- password_hash() / password_verify() de PHP).
-- ============================================================
INSERT INTO `usuarios` (`cliente_id`, `nombre`, `email`, `password`, `rol`, `estado`)
VALUES (
    NULL,
    'Super Administrador',
    'admin@bingosaas.com',
    '$2b$12$6rbLHpQo6AedBpuE52Rny.X5XavaQSvUFNqPwamH2roSq3guUihbO',
    'super_admin',
    'activo'
);
