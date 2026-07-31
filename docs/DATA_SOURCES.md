# Arquitectura de Fuentes de Datos de Assembler-AI (`DATA_SOURCES.md`)

Este documento establece la arquitectura, conectores y catálogo de **Fuentes de Datos de Assembler-AI**. Define cómo el sistema integra información externa, modelos de IA y conjuntos de datos para enriquecer la Base de Conocimiento, alimentar pipelines RAG (Retrieval-Augmented Generation) y habilitar capacidades avanzadas de razonamiento en ensamblador x86/x64.

---

## 📐 Principios de Diseño

1. **Prioridad y Potencial Máximo a Hugging Face (`https://huggingface.co`)**: Aprovechamiento integral del ecosistema Hugging Face (Modelos, Datasets, Inference Endpoints, Spaces y Embeddings) como la fuente principal de inteligencia y datos para Assembler-AI.
2. **Arquitectura Modulo-Extensible**: Capacidad de conectar nuevos sitios web y APIs (GitHub, Intel Docs, Godbolt, StackOverflow, ArXiv) mediante conectores estandarizados sin alterar la lógica central del sistema.
3. **Indexación en Minúsculas y Escalabilidad**: Todas las rutas de almacenamiento interno y categorías utilizan nomenclaturas en minúsculas (`docs/data_sources/`, `docs/knowledge_base/`) estructuradas en archivos de manifiesto machine-readable.

---

## 🚀 Integración Exhaustiva: Hugging Face (`https://huggingface.co`)

Hugging Face representa la fuente de datos y modelos primordial para Assembler-AI. La integración se desglosa en cuatro pilares operativos:

### 1. Hugging Face Models Hub (Modelos Especializados en Código)
Permite a Assembler-AI consultar o consultar mediante Inference APIs modelos LLM especializados en ingeniería inversa, ensamblador y bajo nivel:
- **`deepseek-ai/DeepSeek-Coder-V2-Instruct`**: Razonamiento complejo en código de máquina, optimización de registros y síntesis de algoritmos.
- **`bigcode/starcoder2-15b`**: Autocompletado y análisis estático de programas x86/NASM.
- **`codellama/CodeLlama-34b-Instruct-hf`**: Explicación didáctica de opcodes y traducción entre C y ensamblador.
- **`Qwen/Qwen2.5-Coder-32B-Instruct`**: Alto rendimiento en traducción de sintaxis Intel vs AT&T y depuración de rutinas.
- **Fine-tunes de Comunidad en Assembly**: Modelos especializados en desensamblado (Hex-Rays/Ghidra outputs a C pseudo-code).

### 2. Hugging Face Datasets Hub (Corpora de Entrenamiento y RAG)
Acceso e ingesta de conjuntos de datos clave para fine-tuning y búsqueda semántica:
- **Corpora de Código Ensamblador x86/x64**: Colecciones de proyectos NASM, MASM, GAS y FASM de código abierto.
- **Pares C <-> x86 Assembly**: Datasets de funciones C pareadas con sus binarios compilados bajo diferentes niveles de optimización (`-O0` a `-O3`).
- **Instrucciones y Especificaciones x86**: Tablas enriquecidas con opcodes, ciclos de reloj, latencias de instrucciones y banderas afectadas.
- **Benchmarks de Descompilación y Seguridad**: Datasets para entrenamiento de detección de vulnerabilidades en binarios x86.

### 3. Hugging Face Serverless Inference API & Dedicated Endpoints
- **API Serverless**: Permite ejecutar inferencias livianas de modelos alojados en Hugging Face directamente desde `Assembler-AI` mediante `HF_TOKEN`.
- **Inference Endpoints**: Capacidad de desplegar pods dedicados (GPU A10G/A100) para modelos finamente sintonizados en ensamblador de la empresa.

### 4. Hugging Face Spaces & Vector Embeddings
- **Modelos de Embeddings de Código**: Uso de modelos como `BAAI/bge-large-en-v1.5` o `thenlper/gte-large` para generar vectores semánticos de rutinas x86 e indexarlas en la Base de Conocimiento.
- **Spaces como Microservicios**: Conexión con Spaces de Hugging Face para análisis AST (*Abstract Syntax Tree*), desensamblado dinámico y simulación en sandbox.

---

## 🔌 Marco Extensible de Fuentes Web (Future Extensibility Framework)

Para garantizar que Assembler-AI incorpore nuevos sitios web y fuentes en etapas futuras del proyecto, se establece la interfaz estándar `IDataSourceConnector`:

```typescript
export interface IDataSourceConnector {
  id: string;                    // ID único (ej: "huggingface", "github", "intel_docs")
  name: string;                  // Nombre legible de la fuente
  baseUrl: string;               // URL base del sitio web
  type: 'ai_hub' | 'code_repo' | 'official_doc' | 'live_compiler' | 'qna_community';
  status: 'active' | 'planned' | 'deprecated';
  priority: number;              // 1 = Máxima prioridad
  capabilities: {
    fetchModels?: boolean;
    fetchDatasets?: boolean;
    searchCode?: boolean;
    fetchDocumentation?: boolean;
  };
  authRequired: boolean;
}
```

### Roadmap de Futuras Fuentes Web Extensibles

| Sitio Web / Fuente | Categoría | Propósito en Assembler-AI | Estado |
| :--- | :--- | :--- | :--- |
| **`https://huggingface.co`** | AI Hub & Datasets | LLMs de código, datasets RAG, Inference API y Embeddings. | **Activo (Primario)** |
| **`https://github.com`** | Repositorio de Código | Indexación de proyectos NASM/x86 de código abierto y ejemplos reales. | *Planificado* |
| **`https://www.intel.com`** | Documentación Oficial | Intel® 64 and IA-32 Architectures Software Developer Manuals. | *Planificado* |
| **`https://godbolt.org`** | Live Compiler Explorer | Interoperabilidad para compilación en vivo y desensamblado instantáneo. | *Planificado* |
| **`https://stackoverflow.com`** | Q&A Community | Ingesta de solución a errores frecuentes de sintaxis y linking x86. | *Planificado* |
| **`https://arxiv.org`** | Artículos Académicos | Papeles de investigación sobre optimización de compiladores y análisis de binarios. | *Planificado* |

---

## 🗃️ Registro de Fuentes

El registro activo de fuentes de datos se encuentra codificado en formato JSON en:
[`docs/data_sources/data_sources_manifest.json`](file:///r:/Laboratorios/github/Assembler-AI/docs/data_sources/data_sources_manifest.json)

Este manifiesto permite a los agentes de Assembler-AI descubrir, priorizar y consultar dinámicamente las fuentes disponibles.
