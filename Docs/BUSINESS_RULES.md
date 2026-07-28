# Reglas de Negocio (BUSINESS_RULES.md)

## 📜 Reglas de Negocio

1. **Límite de Longitud de Mensaje**: Ningún mensaje enviado al servidor puede exceder el límite establecido por `LLM_MAX_MESSAGE_LENGTH` (predeterminado: 8000 caracteres).
2. **Prioridad de Claves de API**:
   - Si el usuario proporciona una API Key personalizada en la UI, esta tiene precedencia sobre las claves predeterminadas del servidor.
3. **Manejo de Errores de Proveedor**: Si la llamada a un proveedor falla, la aplicación debe informar la causa exacta (502 Bad Gateway) sin romper la sesión del chat.
