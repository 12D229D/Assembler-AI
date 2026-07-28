# Referencia Técnica de la API (API_REFERENCE.md)

## 📖 Referencia de Endpoints

### Endpoint `/api/chat` (y alias `/api_handler.php`)

Procesa una consulta sobre lenguaje ensamblador y consulta al LLM configurado.

#### Parámetros
- `message` (*string*): El mensaje o código `.asm` enviado por el usuario.
- `provider` (*string*): ID del proveedor (`gemini`, `openai`, `anthropic`, `openai_compatible`).
- `apiKey` (*string*): Clave de la API (si se anula la del servidor).
- `customModel` (*string*): Nombre del modelo a solicitar.
- `customBaseUrl` (*string*): URL personalizada para APIs compatibles con OpenAI.
