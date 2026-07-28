# Runbook Operativo (RUNBOOK.md)

## 📜 Procedimientos Operativos de Emergencia

### Incidente 1: La API de Gemini / OpenAI responde con 502 Bad Gateway
1. Verificar si la API Key configurada venció o superó el límite de cuota.
2. En la UI, cambiar temporalmente el proveedor a `groq` u `openai_compatible`.
3. Reiniciar el servidor de desarrollo en caso de bloqueo.

### Incidente 2: Mensajes extremadamente largos congelan el cliente
1. Verificar `LLM_MAX_MESSAGE_LENGTH` en `.env`. Por defecto es `8000`.
2. Limpiar la memoria del navegador para la app si el `localStorage` se satura.
