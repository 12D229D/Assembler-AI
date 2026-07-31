# Modelo de Datos (DATA_MODEL.md)

## 🗃️ Estructuras de Datos del Cliente

### Usuario (`User`)
```typescript
interface User {
  name: string;
  email: string;
  plan: 'Gratis' | 'Pro ASM';
}
```

### Proyecto (`Project`)
```typescript
interface Project {
  id: string;
  name: string;
  desc: string;
}
```

### Chat (`Chat`)
```typescript
interface Chat {
  id: string;
  projectId: string;
  title: string;
  messages: Array<{
    sender: 'user' | 'bot';
    text: string;
    extraClass?: string;
  }>;
}
```
