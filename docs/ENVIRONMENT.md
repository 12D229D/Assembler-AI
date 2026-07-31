# Variables y Entornos (ENVIRONMENT.md)

## 🌍 Entornos Ejecutivos

### Desarrollo Local (`NODE_ENV=development`)
- Ejecutado vía `npm run dev`.
- Carga variables desde `.env`.
- Puerto: 3000.

### Producción (`NODE_ENV=production`)
- Ejecutado vía `npm start`.
- Configurado detrás de un reverse proxy (ej. Nginx o Cloud Run).
