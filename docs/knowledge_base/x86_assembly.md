# Manual Fundamentos: Lenguaje Ensamblador x86 (`x86-assembly`)

**Categoría / Etiqueta**: `x86-assembly`  
**Descripción Corta**: Lenguaje ensamblador de bajo nivel específico para la familia de procesadores con arquitectura x86.  
**Estado**: Core Reference  
**Nivel de Abstracción**: Lenguaje / Mnemónicos / Opcodes

---

## 1. Visión General

El **lenguaje ensamblador x86** es la representación simbólica legible por humanos del código de máquina binario ejecutado por la familia de procesadores x86. Cada instrucción de ensamblador se traduce generalmente de 1 a 1 a opcodes de bytes binarios interpretados por el decodificador de instrucciones del CPU.

---

## 2. Comparativa de Sintaxis: Intel vs AT&T

Existen dos sintaxis principales para escribir ensamblador x86:

| Característica | Sintaxis Intel (NASM, MASM) | Sintaxis AT&T (GAS / GCC por defecto) |
| :--- | :--- | :--- |
| **Orden de Operandos** | `Instrucción Destino, Fuente` | `Instrucción Fuente, Destino` |
| **Ejemplo de Asignación** | `mov eax, ebx` | `movl %ebx, %eax` |
| **Prefijo de Registros** | Sin prefijo (`eax`) | Prefijo `%` (`%eax`) |
| **Prefijo de Constantes** | Sin prefijo (`42`, `0x2A`) | Prefijo `$` (`$42`, `$0x2A`) |
| **Desreferencia de Memoria** | `[ebx + 4]` | `4(%ebx)` |

---

## 3. Mnemónicos Fundamentales y Categorías

### 1. Transferencia de Datos
- `mov`: Copiar datos entre registros, memoria y valores inmediatos.
- `push` / `pop`: Meter y sacar datos de la pila de memoria (Stack).
- `lea` (Load Effective Address): Calcular la dirección de memoria de un operando sin acceder a su contenido.

### 2. Operaciones Aritmético-Lógicas (ALU)
- `add`, `sub`, `mul`, `imul`, `div`, `idiv`, `inc`, `dec`.
- `and`, `or`, `xor`, `not`, `shl`, `shr`, `sar`, `rol`, `ror`.

### 3. Control de Flujo y Saltos (Jumps)
- Saltos incondicionales: `jmp`, `call`, `ret`.
- Saltos condicionales (basados en banderas de RFLAGS/EFLAGS):
  - `je` / `jz`: Saltar si es igual / cero (`ZF = 1`).
  - `jne` / `jnz`: Saltar si no es igual / no es cero (`ZF = 0`).
  - `jg` / `jge` / `jl` / `jle`: Saltos con signo (Greater/Less).
  - `ja` / `jae` / `jb` / `jbe`: Saltos sin signo (Above/Below).

---

## 4. Registro de Banderas (FLAGS / EFLAGS / RFLAGS)

- **ZF (Zero Flag)**: Se activa (1) si el resultado de la operación es 0.
- **CF (Carry Flag)**: Se activa si ocurrió un acarreo o préstamo en operaciones sin signo.
- **SF (Sign Flag)**: Refleja el bit más significativo del resultado (indica negativo en complemento a dos).
- **OF (Overflow Flag)**: Se activa si ocurrió un desbordamiento aritmético con signo.

---

## 5. Ejemplo de Código Demostrativo (Algoritmo de Factorial)

```nasm
section .text
    global factorial

; int factorial(int n)
; Entrada: EDI = n (System V ABI)
; Salida:  EAX = n!
factorial:
    mov eax, 1          ; Caso base: result = 1
    cmp edi, 1
    jle .done           ; Si n <= 1, retornar 1

.loop:
    imul eax, edi       ; result *= n
    dec edi             ; n--
    cmp edi, 1
    jg .loop            ; Mientras n > 1

.done:
    ret
```

---

## 6. Prospectiva en Assembler-AI

La etiqueta `x86-assembly` actúa como la base de conocimiento transversal para la optimización de código, traducción automática entre sintaxis Intel y AT&T, y explicación didáctica de instrucciones para el asistente AI.
