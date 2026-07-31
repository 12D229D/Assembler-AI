# Guía y Manual Técnico: Emulación y Simulación x86 (`x86-emulator`)

**Categoría / Etiqueta**: `x86-emulator`  
**Descripción Corta**: Software diseñado para simular o emular el comportamiento de un procesador y sistema basado en la arquitectura x86 en otro hardware.  
**Estado**: Herramientas y Frameworks de Ejecución  
**Nivel de Abstracción**: Emulación por Software y Virtualización

---

## 1. Visión General

Un **emulador x86** es una capa de software que interpreta y ejecuta binarios compilados para la arquitectura x86 en plataformas de hardware diferentes (como ARM64, RISC-V, WebAssembly o arquitecturas x86 host) creando un entorno virtualizado que recrea el comportamiento exacto de los registros, memoria, bus de interrupciones y dispositivos periféricos.

---

## 2. Tipos y Arquitecturas de Emulación

### 1. Emulación por Interpretación de Opcodes
El ciclo clásico **Fetch-Decode-Execute**:
- Lee los bytes del opcode en memoria (`EIP`/`RIP`).
- Decodifica el prefijo, instrucción, modR/M y SIB bytes.
- Modifica el estado del procesador simulado (registros virtuales, FLAGS) mediante software.
- **Ejemplo**: 8086tiny, DOSBox (modo normal), Bochs.

### 2. Traducción Dinámica de Código (Dynamic Binary Translation - DBT / JIT)
Traduce bloques de instrucciones x86 (*Basic Blocks*) a instrucciones nativas del procesador host (ej. ARM64) en tiempo de ejecución y las almacena en una caché de código traducido.
- **Ejemplo**: QEMU TCG (*Tiny Code Generator*), Rosetta 2 (Apple Silicon), Box86 / Box64.

### 3. Emulación Ligera Guiada por Eventos / Hooking (CPU Frameworks)
Emula instrucciones x86 instrucción por instrucción pero permite interceptar accesos a memoria y ejecución con callbacks programables en Python/C/Go.
- **Ejemplo**: Unicorn Engine (basado en QEMU).

---

## 3. Principales Emuladores y Sus Casos de Uso

| Herramienta | Tipo | Propósito Principal |
| :--- | :--- | :--- |
| **QEMU** | Emulador / Hypervisor | Emulación completa de sistema (*Full System*) y de espacio de usuario (*User Mode*). |
| **Bochs** | Emulador preciso | Emulación extremadamente rigurosa ciclo a ciclo, ideal para depuración de kernels OS. |
| **DOSBox** | Emulador de Sistema | Emulación de x86-16/32 con tarjeta de sonido (Sound Blaster) y gráficos VGA/DOS. |
| **Unicorn Engine** | Framework de Emulación | Emulación de fragmentos de código ejecutable para ingeniería inversa y fuzzing. |
| **8086tiny** | Emulador minimalista | Emulador C superligero del Intel 8086 (< 500 líneas de código). |

---

## 4. Ejemplo Práctico: Emulación con Unicorn Engine en Python

```python
from unicorn import *
from unicorn.x86_const import *

# Código máquina x86 de 32 bits: INC eax; ADD eax, ebx
X86_CODE32 = b"\x40\x01\xd8"

# Dirección de memoria virtual para la prueba
ADDRESS = 0x1000000

try:
    # 1. Inicializar emulador en modo x86 32-bit
    mu = Uc(UC_ARCH_X86, UC_MODE_32)

    # 2. Mapear 2 MB de memoria física emulada
    mu.mem_map(ADDRESS, 2 * 1024 * 1024)

    # 3. Escribir código en la memoria emulada
    mu.mem_write(ADDRESS, X86_CODE32)

    # 4. Inicializar registros virtuales
    mu.reg_write(UC_X86_REG_EAX, 10)
    mu.reg_write(UC_X86_REG_EBX, 5)

    # 5. Emular ejecucion del codigo
    mu.emu_start(ADDRESS, ADDRESS + len(X86_CODE32))

    # 6. Leer resultado final de los registros
    eax = mu.reg_read(UC_X86_REG_EAX)
    print(f"[Emulador Unicorn] EAX final = {eax}") # Deberia imprimir 16

except UcError as e:
    print(f"Error de emulacion: {e}")
```

---

## 5. Prospectiva en Assembler-AI

En **Assembler-AI**, la etiqueta `x86-emulator` habilita la simulación en entorno seguro (*Sandboxing*), verificación de corrección de fragmentos de ensamblador en el navegador (mediante WebAssembly / v86) y pruebas automatizadas de rutinas sin riesgo de corromper el sistema anfitrión.
