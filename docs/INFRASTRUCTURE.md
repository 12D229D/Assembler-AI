# Infraestructura del Proyecto (INFRASTRUCTURE.md)

## 🏗️ Arquitectura de Infraestructura

- **Servidor de Aplicación**: Node.js contenedorizado exponiendo el puerto `3000`.
- **Proxy Inverso**: Nginx configurado para enrutar el tráfico entrante al puerto 3000.
- **Servicio de Nube**: Cloud Run / GCP Container Registry.
