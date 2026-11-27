-- =====================================================================
-- TP N° 8 – Migración de la base carza_db para autenticación
-- Ejecutar UNA vez en phpMyAdmin (pestaña SQL), con la base carza_db ya creada.
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

-- =====================================================================
-- 3) CONVERTIR TU CUENTA EN ADMINISTRADOR
--    Primero registrate desde registro.php y después ejecutá esto
--    reemplazando el correo por el que usaste:
-- =====================================================================
-- UPDATE usuarios
-- SET rol_id = (SELECT id FROM roles WHERE nombre = 'admin')
-- WHERE email = 'tu_correo@ejemplo.com';

-- 4) Ver cómo quedaron los usuarios (la contraseña se ve como hash, nunca en texto)
-- SELECT u.id, u.nombre, u.email, r.nombre AS rol, u.password_hash, u.created_at
-- FROM usuarios u INNER JOIN roles r ON r.id = u.rol_id;
