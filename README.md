# Assembler-AI ⚡

**Assembler-AI** es un asistente web impulsado por inteligencia artificial especializado en **Lenguaje Ensamblador x86/x86_64**, optimización de bajo nivel, depuración de rutinas y explicación de arquitecturas de procesamiento.

## 🌟 Características Principales

- **Asistente Experto en x86**: Respuestas precisas en sintaxis NASM, MASM, GAS y FASM.
- **Soporte Multi-LLM**: Integración nativa con Google Gemini, OpenAI, Anthropic Claude, Mistral AI, Hugging Face Hub y proveedores compatibles con OpenAI (Groq, Ollama, etc.).
- **Base de Conocimiento Interna (`docs/knowledge_base/`)**: Cobertura técnica estructurada en 11 etiquetas clave de x86 (16-bit, 32-bit, 64-bit, 128-bit conceptual, NASM, Kernels, Emulación).
- **Fuentes de Datos Extensibles (`docs/DATA_SOURCES.md`)**: Explotación integral de **Hugging Face** (Modelos, Datasets, Inference Endpoints) y marco extensible para integrar fuentes web adicionales (GitHub, Intel SDM, Godbolt, StackOverflow, ArXiv).
- **Gestión de Proyectos y Chats**: Organización por proyectos temáticos y almacenamiento local con `localStorage`.
- **Modo Servidor o Client Key**: Posibilidad de usar claves preconfiguradas en el servidor o ingresar tu propia API Key directamente en el navegador.

## 🚀 Inicio Rápido

```bash
# 1. Instalar dependencias
npm install

# 2. Configurar variables de entorno (opcional)
cp .env.example .env

# 3. Iniciar el servidor
npm run dev
```

Navega a `http://localhost:3000` para comenzar a interactuar con el asistente.

## 📚 Base de Conocimiento y Fuentes de Datos (`docs/`)

El proyecto cuenta con una documentación estructurada y en minúsculas dentro del directorio [`docs/`](file:///r:/Laboratorios/github/Assembler-AI/docs/):
- 📖 [Base de Conocimiento Interna (`docs/KNOWLEDGE_BASE.md`)](file:///r:/Laboratorios/github/Assembler-AI/docs/KNOWLEDGE_BASE.md)
- 🌐 [Fuentes de Datos de Assembler-AI (`docs/DATA_SOURCES.md`)](file:///r:/Laboratorios/github/Assembler-AI/docs/DATA_SOURCES.md)
- ⚙️ [Manifiesto de Taxonomía (`docs/knowledge_base/taxonomy_manifest.json`)](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/taxonomy_manifest.json)
- 🔌 [Manifiesto de Fuentes de Datos (`docs/data_sources/data_sources_manifest.json`)](file:///r:/Laboratorios/github/Assembler-AI/docs/data_sources/data_sources_manifest.json)
