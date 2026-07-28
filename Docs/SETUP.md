# Configuración del Entorno (SETUP.md)

## ⚙️ Entorno de Desarrollo

Para configurar tu máquina local para colaborar en **Assembler-AI**:

1. Asegúrate de contar con Node.js 18+.
2. Duplica el archivo `.env.example`:
   ```bash
   cp .env.example .env
   ```
3. Agrega tu `GEMINI_API_KEY` u otra clave si vas a probar el servidor sin enviar la clave desde la UI.
4. Ejecuta `npm run dev` para iniciar el servidor HTTP con recarga rápida mediante `tsx`.
