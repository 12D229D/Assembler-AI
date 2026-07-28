# Escalabilidad del Sistema (SCALABILITY.md)

## 📈 Arquitectura Escalable

- **Stateless Backend**: El servidor backend de Express no guarda estado de sesión en memoria local, lo que permite escalar horizontalmente añadiendo múltiples réplicas detrás de un balanceador de carga.
- **Autoescalado en Cloud Run**: Habilidad de autoescalar de 0 a N instancias según el tráfico de peticiones.
