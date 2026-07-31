# Integración y Despliegue Continuo (CI_CD.md)

## 🔄 Pipeline de Integración Continua

### GitHub Actions / Cloud Build Workflow
1. **Lint & Build Check**: Ejecutar `npm run build` en Node.js 18 y 20.
2. **Deploy Automático**: Despliegue automático a Cloud Run tras pasar todas las comprobaciones en la rama `main`.
