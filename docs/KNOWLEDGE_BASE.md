# Base de Conocimiento Interna de Assembler-AI (Knowledge Base)

Bienvenido a la **Base de Conocimiento Interna de Assembler-AI**. Este repositorio de conocimiento está diseñado con una arquitectura modular, escalable y con prospectiva para servir como referencia técnica de bajo nivel para desarrolladores, arquitectos de sistemas y motores de Inteligencia Artificial (RAG / agentes conversacionales).

---

## 📐 Arquitectura y Principios de Diseño

La Base de Conocimiento está estructurada bajo tres pilares fundamentales:

1. **Modularidad Jerárquica**: Cada etiqueta o categoría cuenta con su propia unidad de conocimiento aislada en Markdown (`docs/knowledge_base/<categoria>.md`), permitiendo actualización independiente y versionado por módulo.
2. **Interoperabilidad Máquina-Humano**: Acompañada por un archivo de manifiesto estructurado en JSON (`taxonomy_manifest.json`), lo que facilita la indexación semántica, generación de embeddings para búsqueda vectorial y consumo mediante APIs.
3. **Prospectiva y Evolución Tecnológica**: No solo cubre el legado histórico y la especificación actual de x86, sino que integra tendencias futuras como vectorización avanzada (AVX-512, AVX10, APX) y conceptos de arquitecturas extendidas de 128 bits.

---

## 🏷️ Catálogo de Categorías y Etiquetas

A continuación se presenta el catálogo oficial de los 11 dominios de conocimiento integrados:

| Categoría / Etiqueta | Descripción Corta | Nivel de Abstracción | Archivo de Referencia |
| :--- | :--- | :--- | :--- |
| **`x86`** | Arquitectura de microprocesadores de 16 y 32 bits (y su ecosistema general) iniciada por Intel con la familia 8086. | Arquitectura General | [`x86.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86.md) |
| **`x86-128`** | Referencia o término general asociado a arquitecturas futuras o conceptuales de 128 bits basadas en el legado x86. | Prospectiva / Conceptual | [`x86_128.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_128.md) |
| **`x86-16`** | Arquitectura x86 original de 16 bits utilizada en procesadores Intel 8086/80286 (modo real/protegido primitivo). | Legado / Real Mode | [`x86_16.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_16.md) |
| **`x86-32`** | Arquitectura x86 de 32 bits (IA-32), introducida con el Intel 80386 (memoria plana, paginación). | Sistemas de 32 bits | [`x86_32.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_32.md) |
| **`x86-64`** | Arquitectura de 64 bits (AMD64 / Intel 64), extensión de registros y espacio de direcciones de 64 bits. | Arquitectura Actual | [`x86_64.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_64.md) |
| **`x86-64-assembly-nasm`** | Programación en lenguaje ensamblador x86-64 de 64 bits utilizando NASM (Netwide Assembler). | Programación Práctica | [`x86_64_assembly_nasm.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_64_assembly_nasm.md) |
| **`x86-assembly`** | Lenguaje ensamblador de bajo nivel específico para la familia de procesadores con arquitectura x86. | Lenguaje / Mnemónicos | [`x86_assembly.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_assembly.md) |
| **`x86-emulator`** | Software diseñado para simular o emular el comportamiento de procesadores y sistemas x86 en otro hardware. | Herramientas / Simulación | [`x86_emulator.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_emulator.md) |
| **`x86-kernel`** | Desarrollo, configuración o estudio de núcleos (kernels) de sistemas operativos optimizados para x86. | Sistemas Operativos / Bare-Metal | [`x86_kernel.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_kernel.md) |
| **`x86-nasm`** | Uso del ensamblador NASM (Netwide Assembler) para escribir código de bajo nivel en arquitectura x86. | Assembler Tooling | [`x86_nasm.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_nasm.md) |
| **`x86-x64`** | Término general que agrupa tanto a la arquitectura tradicional de 32 bits (x86) como a la de 64 bits (x64 / x86-64). | Interoperabilidad / Migración | [`x86_x64.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/x86_x64.md) |

---

## 🔗 Fuentes de Datos e Integraciones Externas

Para consultar el marco de integración con plataformas externas como **Hugging Face (`https://huggingface.co`)** y la arquitectura de conectores extensibles:
- Archivo de Arquitectura de Fuentes: [`DATA_SOURCES.md`](file:///r:/Laboratorios/github/Assembler-AI/docs/DATA_SOURCES.md)
- Manifiesto de Fuentes: [`data_sources_manifest.json`](file:///r:/Laboratorios/github/Assembler-AI/docs/data_sources/data_sources_manifest.json)

---

## 🔄 Estructura Estándar de los Módulos de Conocimiento

Cada documento dentro de `docs/knowledge_base/` sigue una estructura estandarizada de 5 niveles para mantener consistencia:

1. **Definición y Visión General**: Resumen técnico y contexto histórico/funcional.
2. **Especificación Técnica y Arquitectura**: Registros, modos de operación, espacios de memoria y conjuntos de instrucciones.
3. **Manual de Sintaxis y Ejemplos de Código**: Snippets funcionales, directivas y patrones de implementación.
4. **Casos de Uso y Aplicación Práctica**: Cuándo y cómo emplear las técnicas descritas.
5. **Prospectiva y Escalabilidad**: Tendencias, integraciones avanzadas y evolución de la tecnología.

---

## 🤖 Integración con el Asistente AI (RAG & Prompts)

Para consultar o alimentar el motor LLM de Assembler-AI con este conocimiento:
- El archivo [`taxonomy_manifest.json`](file:///r:/Laboratorios/github/Assembler-AI/docs/knowledge_base/taxonomy_manifest.json) sirve de índice dinámico.
- Los fragmentos de código y conceptos pueden inyectarse dinámicamente como contexto según las etiquetas detectadas en las consultas de los usuarios.
