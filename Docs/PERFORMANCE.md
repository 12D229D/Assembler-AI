# Rendimiento y Optimización (PERFORMANCE.md)

## ⚡ Estrategias de Rendimiento

1. **Carga Ultrarrápida del Frontend**: SPA sin dependencias pesadas de frameworks UI en runtime.
2. **Reutilización de Conexiones**: Fetch nativo para minimizar la sobrecarga de handshake SSL/TLS al llamar a las APIs de los LLM.
3. **Respuesta Asíncrona**: No bloqueo del Event Loop de Node.js mediante uso estricto de `async/await`.
