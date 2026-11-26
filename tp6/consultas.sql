-- =====================================================================
-- TP N° 6 – Consultas de prueba CRUD sobre carza_db
-- Ejecutar DESPUÉS de schema.sql. Se puede correr completo o por bloques.
-- =====================================================================
USE carza_db;

-- =====================================================================
-- C – CREATE: datos iniciales
-- =====================================================================

-- Tablas maestras
INSERT INTO roles (nombre, descripcion) VALUES
  ('admin',   'Administrador del sitio'),
  ('editor',  'Carga y edita trabajos'),
  ('cliente', 'Cliente registrado');

INSERT INTO categorias (nombre, descripcion) VALUES
  ('Zinguería y canaletas',  'Canaletas, cenefas y plegados a medida'),
  ('Techados y estructuras', 'Techos de chapa, cabriadas y estructuras metálicas'),
  ('Durlock',                'Tabiques, cielorrasos y revestimientos'),
  ('Herrería',               'Portones, rejas, escaleras y pérgolas');

INSERT INTO estados_consulta (nombre) VALUES
  ('Pendiente'), ('En contacto'), ('Presupuestada'), ('Cerrada'), ('Descartada');

INSERT INTO provincias (nombre) VALUES ('Córdoba'), ('Mendoza');

INSERT INTO localidades (provincia_id, nombre) VALUES
  ((SELECT id FROM provincias WHERE nombre = 'Córdoba'), 'Córdoba'),
  ((SELECT id FROM provincias WHERE nombre = 'Córdoba'), 'Villa Carlos Paz'),
  ((SELECT id FROM provincias WHERE nombre = 'Mendoza'), 'Godoy Cruz');

-- Usuarios (los hashes son de ejemplo; en el TP7 se generan con password_hash())
INSERT INTO usuarios (rol_id, nombre, apellido, email, password_hash, telefono) VALUES
  ((SELECT id FROM roles WHERE nombre = 'admin'),
   'Jairo', 'Admin', 'admin@carza.com',
   '$2y$10$EJEMPLO.hash.no.real.solo.para.pruebas.de.carga.inicial.0000', '3510000001'),
  ((SELECT id FROM roles WHERE nombre = 'cliente'),
   'María', 'Gómez', 'maria.gomez@example.com',
   '$2y$10$EJEMPLO.hash.no.real.solo.para.pruebas.de.carga.inicial.0000', '3510000002');

-- Tabla principal 1: trabajos (con FK a categorías, usuarios y localidades)
INSERT INTO trabajos (categoria_id, usuario_id, localidad_id, titulo, descripcion, imagen, publicado, fecha_obra) VALUES
  ((SELECT id FROM categorias WHERE nombre = 'Techados y estructuras'),
   (SELECT id FROM usuarios WHERE email = 'admin@carza.com'),
   (SELECT id FROM localidades WHERE nombre = 'Villa Carlos Paz'),
   'Techo de chapa prepintada', 'Cubierta de chapa prepintada en vivienda familiar.', 'galeria-1.jpg', 1, '2026-08-15'),
  ((SELECT id FROM categorias WHERE nombre = 'Herrería'),
   (SELECT id FROM usuarios WHERE email = 'admin@carza.com'),
   (SELECT id FROM localidades WHERE nombre = 'Córdoba'),
   'Portón de líneas simples', 'Portón corredizo de hierro con terminación negra.', 'galeria-4.jpg', 1, '2026-09-20'),
  ((SELECT id FROM categorias WHERE nombre = 'Durlock'),
   (SELECT id FROM usuarios WHERE email = 'admin@carza.com'),
   (SELECT id FROM localidades WHERE nombre = 'Godoy Cruz'),
   'Cielorraso con luz perimetral', 'Cielorraso de Durlock con iluminación indirecta.', 'galeria-3.jpg', 0, '2026-10-05');

-- Tabla principal 2: consultas (estado inicial y localidad por FK)
INSERT INTO consultas (usuario_id, localidad_id, estado_id, nombre, telefono, email, mensaje) VALUES
  ((SELECT id FROM usuarios WHERE email = 'maria.gomez@example.com'),
   (SELECT id FROM localidades WHERE nombre = 'Córdoba'),
   (SELECT id FROM estados_consulta WHERE nombre = 'Pendiente'),
   'María Gómez', '3510000002', 'maria.gomez@example.com',
   'Necesito cambiar las canaletas y hacer un portón.'),
  (NULL,
   (SELECT id FROM localidades WHERE nombre = 'Villa Carlos Paz'),
   (SELECT id FROM estados_consulta WHERE nombre = 'Pendiente'),
   'Carlos Pérez', '3510000003', 'carlos.perez@example.com',
   'Quiero presupuesto para un cielorraso de Durlock.'),
  (NULL, NULL,
   (SELECT id FROM estados_consulta WHERE nombre = 'Pendiente'),
   'Consulta de prueba', '3510000004', 'prueba.baja@example.com',
   'Registro creado solo para probar el DELETE.');

-- Tabla de relación N:M: rubros pedidos en cada consulta
INSERT INTO consulta_categoria (consulta_id, categoria_id) VALUES
  ((SELECT id FROM consultas WHERE email = 'maria.gomez@example.com'),
   (SELECT id FROM categorias WHERE nombre = 'Zinguería y canaletas')),
  ((SELECT id FROM consultas WHERE email = 'maria.gomez@example.com'),
   (SELECT id FROM categorias WHERE nombre = 'Herrería')),
  ((SELECT id FROM consultas WHERE email = 'carlos.perez@example.com'),
   (SELECT id FROM categorias WHERE nombre = 'Durlock')),
  ((SELECT id FROM consultas WHERE email = 'prueba.baja@example.com'),
   (SELECT id FROM categorias WHERE nombre = 'Techados y estructuras'));

-- =====================================================================
-- R – READ: consultas y uniones
-- =====================================================================

-- 1) WHERE + ORDER BY: consultas pendientes, de la más reciente a la más antigua
SELECT id, nombre, email, telefono, created_at
FROM consultas
WHERE estado_id = (SELECT id FROM estados_consulta WHERE nombre = 'Pendiente')
ORDER BY created_at DESC, id DESC;

-- 2) INNER JOIN (4 tablas): consulta con su estado, localidad y provincia
SELECT c.id,
       c.nombre   AS cliente,
       e.nombre   AS estado,
       l.nombre   AS localidad,
       p.nombre   AS provincia
FROM consultas c
INNER JOIN estados_consulta e ON e.id = c.estado_id
INNER JOIN localidades l      ON l.id = c.localidad_id
INNER JOIN provincias p       ON p.id = l.provincia_id
ORDER BY c.id;

-- 3) INNER JOIN: trabajos publicados con su categoría y el usuario que los cargó
SELECT t.titulo,
       cat.nombre AS categoria,
       CONCAT(u.nombre, ' ', u.apellido) AS cargado_por,
       t.fecha_obra
FROM trabajos t
INNER JOIN categorias cat ON cat.id = t.categoria_id
INNER JOIN usuarios u     ON u.id = t.usuario_id
WHERE t.publicado = 1
ORDER BY t.fecha_obra DESC;

-- 4) Relación N:M: rubros pedidos en cada consulta
SELECT c.nombre AS cliente,
       GROUP_CONCAT(cat.nombre ORDER BY cat.nombre SEPARATOR ', ') AS rubros
FROM consultas c
INNER JOIN consulta_categoria cc ON cc.consulta_id = c.id
INNER JOIN categorias cat        ON cat.id = cc.categoria_id
GROUP BY c.id, c.nombre
ORDER BY c.nombre;

-- =====================================================================
-- U – UPDATE (siempre con WHERE)
-- =====================================================================

-- Pasar la consulta de María a "En contacto"
UPDATE consultas
SET estado_id = (SELECT id FROM estados_consulta WHERE nombre = 'En contacto')
WHERE email = 'maria.gomez@example.com';

-- Publicar el trabajo que estaba oculto
UPDATE trabajos
SET publicado = 1
WHERE titulo = 'Cielorraso con luz perimetral';

-- Verificación
SELECT c.nombre, e.nombre AS estado, c.updated_at
FROM consultas c
INNER JOIN estados_consulta e ON e.id = c.estado_id
WHERE c.email = 'maria.gomez@example.com';

-- =====================================================================
-- D – DELETE (siempre con WHERE)
-- =====================================================================

-- Dar de baja la consulta de prueba (sus filas en consulta_categoria se borran por ON DELETE CASCADE)
DELETE FROM consultas
WHERE email = 'prueba.baja@example.com';

-- Verificación: no debe devolver filas
SELECT * FROM consultas WHERE email = 'prueba.baja@example.com';
