# Casos de Uso (USE_CASES.md)

## 🎯 Caso de Uso 1: Explicación de Instrucción de Ensamblador
- **Actor**: Estudiante / Desarrollador.
- **Precondición**: El usuario está en la pantalla principal.
- **Flujo**:
  1. El usuario pregunta: *"¿Cómo funciona CMP y JE?"*.
  2. El sistema envía la petición al backend.
  3. El backend llama a Gemini/OpenAI con el prompt especializado.
  4. La interfaz renderiza el desglose de flags (`ZF`) y salto condicional.
