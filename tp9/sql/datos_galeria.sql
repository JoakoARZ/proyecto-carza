-- Datos opcionales: completa la galería con los otros 3 trabajos de ejemplo
-- (junto con los 3 que carga tp6/consultas.sql, suman 6 trabajos).
-- Ejecutar en phpMyAdmin sobre la base carza_db.
USE carza_db;

INSERT INTO trabajos (categoria_id, usuario_id, localidad_id, titulo, descripcion, imagen, publicado, fecha_obra) VALUES
  ((SELECT id FROM categorias WHERE nombre = 'Zinguería y canaletas'),
   (SELECT id FROM usuarios WHERE email = 'admin@carza.com'),
   (SELECT id FROM localidades WHERE nombre = 'Salta'),
   'Canaletas y bajadas a medida', 'Canaleta y bajada galvanizada sobre pared de ladrillo.', 'galeria-2.jpg', 1, '2026-07-10'),
  ((SELECT id FROM categorias WHERE nombre = 'Herrería'),
   (SELECT id FROM usuarios WHERE email = 'admin@carza.com'),
   (SELECT id FROM localidades WHERE nombre = 'San Lorenzo'),
   'Una pérgola para disfrutar', 'Pérgola metálica sobre deck de madera en un jardín.', 'galeria-5.jpg', 1, '2026-10-12'),
  ((SELECT id FROM categorias WHERE nombre = 'Durlock'),
   (SELECT id FROM usuarios WHERE email = 'admin@carza.com'),
   (SELECT id FROM localidades WHERE nombre = 'Salta'),
   'Terminaciones que se notan', 'Cielorraso de Durlock y ventanal con carpintería negra.', 'galeria-6.jpg', 1, '2026-10-25');
