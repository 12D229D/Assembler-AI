# Modelo Entidad-Relación (ERD.md)

## 📐 Diagrama Entidad-Relación Conceptual

```text
  ┌──────────────┐          ┌──────────────┐          ┌──────────────┐
  │   USUARIO    │ 1      N │   PROYECTO   │ 1      N │     CHAT     │
  │──────────────│──────────│──────────────│──────────│──────────────│
  │ id           │          │ id           │          │ id           │
  │ name         │          │ name         │          │ projectId    │
  │ email        │          │ desc         │          │ title        │
  │ plan         │          └──────────────┘          │ messages[]   │
  └──────────────┘                                    └──────────────┘
```
