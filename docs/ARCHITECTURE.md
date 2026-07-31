# Arquitectura del Sistema (ARCHITECTURE.md)

## 🏗️ Visión General

Assembler-AI utiliza un modelo híbrido cliente-servidor diseñado para ser extremadamente liviano y directo:

```text
[ Cliente Web (HTML5/CSS3/Vanilla JS) ] 
                 │
                 ▼  POST /api/chat o /api_handler.php
[ Servidor Backend (Node.js + Express / TypeScript) ]
                 │
        ┌────────┴────────┬────────────────┬────────────────┐
        ▼                 ▼                ▼                ▼
   [ Gemini SDK ]   [ OpenAI API ]   [ Anthropic API ] [ Groq / Local ]
```

## 🧩 Componentes

1. **Frontend (`index.html`)**:
   - Aplicación Single Page Application (SPA) en JavaScript vanilla.
   - Control de estado local (`localStorage`) para persistir chats, proyectos y configuraciones.
   - Modal de selección de proveedor LLM y clave de API.

2. **Backend (`server.ts`)**:
   - Servidor Express en Node.js que expone `/api/chat` y compatibilidad con `/api_handler.php`.
   - Adapta las peticiones al formato nativo de cada proveedor (Google GenAI SDK, OpenAI Chat Completions REST, Anthropic Messages REST).
   - Sanitización básica y límites de longitud de mensajes.
