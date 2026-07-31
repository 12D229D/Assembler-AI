# Estrategia de Registros (LOGGING.md)

## 📝 Estándar de Logs del Servidor

Los registros emitidos por Express en `server.ts` siguen la estructura:

```text
[Assembler-AI] Dev server running on http://0.0.0.0:3000
[Assembler-AI Error]: Error message description
```

Nunca se incluyen claves de API ni credenciales sensibles dentro de los registros de la consola.
