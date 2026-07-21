# Assembler-AI

Assembler-AI es una aplicación web ligera para conversar con un asistente experto en lenguaje ensamblador x86. La versión actual separa frontend, configuración, transporte HTTP y proveedores LLM para facilitar escalabilidad y cambios de modelo sin tocar código.

## Capacidades principales

- Backend PHP sin dependencias externas ni gestor de paquetes obligatorio.
- Configuración por `.env` con proveedores `gemini`, `openai`, `anthropic` y `openai_compatible`.
- Validación de método HTTP, JSON y longitud máxima de mensaje.
- Manejo seguro de claves API fuera del repositorio.
- Interfaz responsive con mejoras de accesibilidad y protección básica contra HTML no confiable.

## Configuración rápida

1. Copia el archivo de ejemplo:

   ```bash
   cp .env.example .env
   ```

2. Edita `.env` y selecciona un proveedor:

   ```dotenv
   LLM_PROVIDER=gemini
   GEMINI_API_KEY=tu_clave
   GEMINI_MODEL=gemini-1.5-flash-latest
   ```

3. Levanta el servidor local:

   ```bash
   php -S localhost:8000
   ```

4. Abre `http://localhost:8000`.

## Proveedores soportados

| Proveedor | Variables mínimas |
| --- | --- |
| Gemini | `LLM_PROVIDER=gemini`, `GEMINI_API_KEY`, `GEMINI_MODEL` |
| OpenAI | `LLM_PROVIDER=openai`, `OPENAI_API_KEY`, `OPENAI_MODEL` |
| Anthropic | `LLM_PROVIDER=anthropic`, `ANTHROPIC_API_KEY`, `ANTHROPIC_MODEL` |
| Compatible OpenAI | `LLM_PROVIDER=openai_compatible`, `OPENAI_COMPATIBLE_API_KEY`, `OPENAI_COMPATIBLE_MODEL`, `OPENAI_COMPATIBLE_API_BASE` |

## Arquitectura

```text
index.html                 Frontend responsive del chat
api_handler.php            Endpoint JSON de entrada
bootstrap.php              Autoload y carga de .env
src/Config                 Configuración y loader de entorno
src/Http                   Respuestas JSON y cliente HTTP
src/LLM                    Contratos, factory y proveedores LLM
```

## Seguridad operativa

- Nunca publiques `.env` ni claves API.
- Ajusta `LLM_MAX_MESSAGE_LENGTH`, `LLM_TIMEOUT` y `LLM_MAX_TOKENS` según coste y capacidad del proveedor.
- En producción, configura rate limiting desde el servidor web o un gateway para controlar abuso y costes.

## Estrategia MVP y escalamiento

1. **MVP:** chat especializado en ensamblador con un proveedor LLM activo.
2. **Escalamiento técnico:** añadir historial conversacional persistente, autenticación y observabilidad.
3. **Escalamiento de negocio:** planes por uso, plantillas para depuración ASM, snippets exportables y laboratorios interactivos.
