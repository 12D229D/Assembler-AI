# Resolución de Problemas (TROUBLESHOOTING.md)

## 🔍 Solución de Problemas Frecuentes

### Problema: "No se proporcionó API Key para Gemini"
- **Causa**: No se definió `GEMINI_API_KEY` en `.env` ni se ingresó una clave en el menú "Multi-LLM" de la UI.
- **Solución**: Abre el panel superior derecho "Multi-LLM ▾", ingresa tu API Key personal y presiona "Guardar".

### Problema: Error `Vite: not found` o fallos de compilación
- **Causa**: Falta instalar dependencias npm.
- **Solución**: Ejecuta `npm install` en la raíz del proyecto.
