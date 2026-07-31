# Monitorización Técnica (MONITORING.md)

## 📈 Monitoreo Técnico

Para entornos de producción (Cloud Run / Kubernetes):

1. **Health Checks**: Petición GET a la raíz `/` para verificar que el servidor responda HTTP 200.
2. **Uso de Memoria**: Supervisar consumo de RAM en el proceso de Node.js.
