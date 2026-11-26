-- =====================================================================
-- TP N° 6 – Esquema relacional de CARZA (techos, zinguería, Durlock, herrería)
-- Motor: MySQL / MariaDB (XAMPP, Laragon). Ejecutar completo en phpMyAdmin.
-- ATENCIÓN: el bloque DROP TABLE borra las tablas existentes para poder
-- volver a ejecutar el script desde cero durante el desarrollo.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS carza_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE carza_db;

-- ---------------------------------------------------------------------
-- Reinicio del esquema (orden inverso a las dependencias)
-- ---------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS consulta_categoria;
DROP TABLE IF EXISTS trabajos;
DROP TABLE IF EXISTS consultas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS localidades;
DROP TABLE IF EXISTS provincias;
DROP TABLE IF EXISTS estados_consulta;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS roles;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- TABLAS MAESTRAS / SECUNDARIAS
-- ---------------------------------------------------------------------

-- Roles de usuario (admin, editor, cliente)
CREATE TABLE roles (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(30)  NOT NULL UNIQUE,
  descripcion VARCHAR(150) NULL
) ENGINE=InnoDB;

-- Rubros de trabajo: zinguería, techados, Durlock, herrería
CREATE TABLE categorias (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(60)  NOT NULL UNIQUE,
  descripcion VARCHAR(255) NULL
) ENGINE=InnoDB;

-- Estados del ciclo de vida de una consulta de presupuesto
CREATE TABLE estados_consulta (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(40) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE provincias (
  id     INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(60) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE localidades (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  provincia_id INT         NOT NULL,
  nombre       VARCHAR(80) NOT NULL,
  UNIQUE KEY uq_localidad_provincia (provincia_id, nombre),
  CONSTRAINT fk_localidades_provincia
    FOREIGN KEY (provincia_id) REFERENCES provincias (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- USUARIOS (autenticación, rol y datos personales)
-- ---------------------------------------------------------------------
CREATE TABLE usuarios (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  rol_id        INT          NOT NULL,
  nombre        VARCHAR(60)  NOT NULL,
  apellido      VARCHAR(60)  NOT NULL,
  email         VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,          -- hash generado con password_hash() en PHP
  telefono      VARCHAR(20)  NULL,
  activo        TINYINT(1)   NOT NULL DEFAULT 1,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuarios_rol
    FOREIGN KEY (rol_id) REFERENCES roles (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- TABLAS PRINCIPALES DE NEGOCIO
-- ---------------------------------------------------------------------

-- Consultas / pedidos de presupuesto (formulario de contacto)
-- usuario_id es opcional: un visitante puede consultar sin tener cuenta.
CREATE TABLE consultas (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id   INT          NULL,
  localidad_id INT          NULL,
  estado_id    INT          NOT NULL DEFAULT 1,
  nombre       VARCHAR(80)  NOT NULL,
  telefono     VARCHAR(20)  NOT NULL,
  email        VARCHAR(120) NOT NULL,
  mensaje      TEXT         NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_consultas_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_consultas_localidad
    FOREIGN KEY (localidad_id) REFERENCES localidades (id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_consultas_estado
    FOREIGN KEY (estado_id) REFERENCES estados_consulta (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Trabajos realizados (galería del sitio)
CREATE TABLE trabajos (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  categoria_id INT          NOT NULL,
  usuario_id   INT          NOT NULL,           -- quién cargó el trabajo
  localidad_id INT          NULL,
  titulo       VARCHAR(120) NOT NULL,
  descripcion  TEXT         NULL,
  imagen       VARCHAR(255) NULL,
  publicado    TINYINT(1)   NOT NULL DEFAULT 1,
  fecha_obra   DATE         NULL,
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_trabajos_categoria
    FOREIGN KEY (categoria_id) REFERENCES categorias (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_trabajos_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_trabajos_localidad
    FOREIGN KEY (localidad_id) REFERENCES localidades (id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- TABLA DE RELACIÓN N:M  (una consulta puede pedir varios rubros)
-- ---------------------------------------------------------------------
CREATE TABLE consulta_categoria (
  consulta_id  INT NOT NULL,
  categoria_id INT NOT NULL,
  PRIMARY KEY (consulta_id, categoria_id),
  CONSTRAINT fk_cc_consulta
    FOREIGN KEY (consulta_id) REFERENCES consultas (id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_cc_categoria
    FOREIGN KEY (categoria_id) REFERENCES categorias (id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- ÍNDICES DE RENDIMIENTO
-- ---------------------------------------------------------------------
-- Buscar consultas de un cliente por correo
CREATE INDEX idx_consultas_email ON consultas (email);
-- Listar consultas por estado, de la más reciente a la más antigua
CREATE INDEX idx_consultas_estado_fecha ON consultas (estado_id, created_at);
-- Filtrar la galería por categoría y publicación
CREATE INDEX idx_trabajos_categoria_publicado ON trabajos (categoria_id, publicado);
