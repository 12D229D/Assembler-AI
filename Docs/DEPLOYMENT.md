# Despliegue en Producción (DEPLOYMENT.md)

## 🚀 Opciones de Despliegue

### Cloud Run / Contenedores Docker
El sistema está configurado para ejecutarse nativamente en servicios de contenedores como Cloud Run o Docker:

1. Puerto expuesto: `3000`.
2. Comando de inicio: `npm start` (`node server.ts`).

### Servidor Node.js Estándar
1. Ejecuta `npm run build` para validar tipos.
2. Inicia con `NODE_ENV=production npm start`.
