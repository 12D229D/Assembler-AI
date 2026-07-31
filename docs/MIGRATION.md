# Guía de Migración (MIGRATION.md)

## 🔄 Migración desde Backend PHP a Node.js Express

Si vienes de versiones anteriores basadas en PHP (`api_handler.php` nativo en PHP):

- **Endpoints**: El backend de Node.js ahora soporta tanto `/api/chat` como `/api_handler.php` para compatibilidad retroactiva.
- **Servidor**: No es necesario utilizar `php -S localhost:8000`. Ejecuta `npm run dev` en el puerto `3000`.
