# Problemas Conocidos (KNOWN_ISSUES.md)

## 🐛 Problemas Conocidos

1. **Límite de Almacenamiento Local**: Si el historial de chats supera los 5MB, `localStorage` podría arrojar una excepción `QuotaExceededError`.
   - *Solución temporal*: Eliminar proyectos o chats antiguos en la interfaz.
