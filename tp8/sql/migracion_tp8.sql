-- =====================================================================
-- TP N° 8 – Migración de la base carza_db para autenticación
-- =====================================================================
USE carza_db;

-- 1) Rol que se asigna a quienes se registran desde el sitio
--    (roles.nombre es UNIQUE, por eso INSERT IGNORE no duplica nada)
INSERT IGNORE INTO roles (nombre, descripcion)
VALUES ('usuario', 'Usuario registrado desde el sitio');

-- 2) Verificación de la estructura de usuarios
--    (password_hash = columna VARCHAR(255) que guarda el hash bcrypt; rol_id enlaza con roles)
DESCRIBE usuarios;
SELECT * FROM roles;
