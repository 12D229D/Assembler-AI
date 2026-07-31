# Requisitos del Sistema (REQUIREMENTS.md)

## 🎯 Requisitos Funcionales
- El sistema debe procesar peticiones HTTP POST con mensajes del usuario y retornar una respuesta en texto enriquecido.
- El sistema debe permitir cambiar el proveedor de LLM en tiempo de ejecución.
- El cliente debe persistir el historial de chats en `localStorage`.

## 🔒 Requisitos No Funcionales
- Tiempo de respuesta de la interfaz < 100ms (excluyendo latencia de la API del LLM).
- Soporte para diseño responsivo en pantallas móviles desde 320px de ancho.
- Cumplimiento con las políticas de privacidad sin filtrado no deseado de API Keys.
