# Despliegue en Kubernetes (KUBERNETES.md)

## ☸️ Manifiestos de Kubernetes

Configuración básica de Deployment y Service para Kubernetes:

```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: assembler-ai
spec:
  replicas: 2
  selector:
    matchLabels:
      app: assembler-ai
  template:
    metadata:
      labels:
        app: assembler-ai
    spec:
      containers:
      - name: assembler-ai
        image: gcr.io/tu-proyecto/assembler-ai:latest
        ports:
        - containerPort: 3000
```
