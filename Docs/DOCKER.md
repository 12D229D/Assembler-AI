# Contenedores Docker (DOCKER.md)

## 🐳 Configuración Docker

Ejemplo de `Dockerfile` sugerido para producción:

```dockerfile
FROM node:18-alpine
WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production
COPY . .
EXPOSE 3000
CMD ["npm", "start"]
```
