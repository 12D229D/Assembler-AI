# Política de Seguridad (SECURITY.md)

## 🛡️ Reporte de Vulnerabilidades

Tomamos la seguridad muy en serio. Si descubres una vulnerabilidad de seguridad en **Assembler-AI**, por favor no abras un Issue público.

Envía un reporte detallado a: `security@assembler-ai.org` o directamente a la dirección del mantenedor.

## 🔐 Prácticas de Seguridad Implementadas

1. **Gestión de Claves API**:
   - Las claves enviadas desde el frontend viajan por TLS/HTTPS y no son almacenadas de forma permanente en los servidores.
   - Si se usan variables de entorno (`.env`), estas nunca se exponen al cliente.

2. **Validación de Entradas**:
   - Control estricto del tamaño máximo del mensaje enviado (`LLM_MAX_MESSAGE_LENGTH`).
   - Timeout de peticiones para evitar bloqueos del servidor (`LLM_TIMEOUT`).
