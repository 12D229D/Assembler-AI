# Operación Diaria (OPERATIONS.md)

## 🔄 Procedimientos Diarios

- **Monitoreo de Errores**: Revisar logs de la consola del servidor Node (`[Assembler-AI Error]`).
- **Verificación de Cuotas API**: Asegurarse de que las claves predeterminadas del servidor tengan cuota disponible en las consolas correspondientes (Google AI Studio, OpenAI, Anthropic).
- **Ajuste de Parámetros**: Si las respuestas se cortan, aumentar `LLM_MAX_TOKENS` en `.env`.
