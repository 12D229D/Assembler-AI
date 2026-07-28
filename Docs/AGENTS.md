# Reglas para Agentes de IA Colaboradores (AGENTS.md)

Este documento establece los lineamientos para cualquier agente de Inteligencia Artificial que interactúe, genere código o mantenga el repositorio **Assembler-AI**.

## 🤖 Principios Generales

1. **Precisión Técnica en Ensamblador**:
   - Especificar siempre la sintaxis empleada (ej. NASM, MASM, GAS AT&T).
   - Validar alineación de pila (`rsp`) en llamadas a C/POSIX de 64 bits.
   - Preservar registros callee-saved (`rbx`, `rbp`, `r12`-`r15`) en funciones.

2. **Estilo de Código**:
   - TypeScript estricto sin uso indebido de `any`.
   - Componentes ligeros en el cliente sin frameworks pesados innecesarios.
   - Manejo claro de errores HTTP (422 para datos inválidos, 502 para errores del LLM).

3. **Restricciones del Entorno**:
   - No asumir puertos distintos a 3000 en ejecuciones de entorno de desarrollo.
   - Mantener las claves de API fuera de los commits y logs.
