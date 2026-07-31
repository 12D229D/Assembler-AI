# Diseño de Base de Datos (DATABASE.md)

## 🗄️ Arquitectura de Almacenamiento

Actualmente, **Assembler-AI** opera en modalidad client-side sin base de datos relacional obligatoria en el servidor. 

- **Persistencia Primaria**: `localStorage` del navegador.
- **Evolución Futura**: Integración con Google Firestore para sincronización multi-dispositivo y almacenamiento seguro de historial.
