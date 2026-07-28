# Modelo de Amenazas (THREAT_MODEL.md)

## 🛡️ Análisis de Amenazas (STRIDE)

- **Spoofing**: Prevenido al no requerir sesiones del lado del servidor no autenticadas con permisos críticos.
- **Tampering**: Prevenido validando que los tipos de datos enviados en JSON al backend coincidan con los esquemas esperados.
- **Information Disclosure**: Las claves de API del servidor se mantienen ocultas en variables de entorno inaccesibles desde el navegador.
