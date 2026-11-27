# Versión MVC nativa (PHP puro)

1. Copiá `.env.example` como `.env` y ajustá `DB_PORT` (3306 por defecto en XAMPP).
2. Asegurate de tener la base `carza_db` (TP6) y la migración `tp8/sql/migracion_tp8.sql` (rol `usuario`).
3. Copiá tus imágenes en `public/img/`.
4. Abrí `http://localhost/NOMBRE-REPO/tp10/mvc-nativo/public/`.

Rutas: `/` · `/trabajos` · `/login` · `/registro` · `/cuenta` (protegida).

Requiere `mod_rewrite` de Apache (viene activo en XAMPP).
