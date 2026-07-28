# Especificación Funcional (SPECIFICATION.md)

## 📐 Especificación del Sistema

### Flujo de Datos Principal
1. El usuario ingresa un prompt en la caja de texto.
2. La interfaz obtiene la clave y modelo seleccionados de la configuración.
3. Se envía una petición `POST` al endpoint `/api/chat` del backend.
4. El backend invoca al SDK o API REST del proveedor seleccionado.
5. La respuesta formateada se renderiza en el chat del usuario.
