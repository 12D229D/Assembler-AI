# Contrato de la API (API_SPEC.md)

## 📄 Especificación OpenAPI / OpenAPI Spec (Simplificada)

### `POST /api/chat`

**Headers**:
- `Content-Type: application/json`

**Body**:
```json
{
  "message": "string (requerido)",
  "provider": "gemini | openai | anthropic | openai_compatible (opcional)",
  "apiKey": "string (opcional)",
  "customModel": "string (opcional)",
  "customBaseUrl": "string (opcional)"
}
```

**Respuesta Correcta (200 OK)**:
```json
{
  "reply": "string con la respuesta del LLM",
  "provider": "nombre_del_proveedor"
}
```
