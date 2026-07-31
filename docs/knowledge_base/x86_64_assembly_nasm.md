# Guía Práctica y Manual: Ensamblador x86-64 con NASM (`x86-64-assembly-nasm`)

**Categoría / Etiqueta**: `x86-64-assembly-nasm`  
**Descripción Corta**: Programación en lenguaje ensamblador utilizando el conjunto de instrucciones de 64 bits (x86-64) y el ensamblador NASM (Netwide Assembler).  
**Estado**: Guía de Programación Práctica  
**Nivel de Abstracción**: Desarrollo de Software y Compilación de Bajo Nivel

---

## 1. Visión General

El ensamblador **NASM (Netwide Assembler)** combinado con el conjunto de instrucciones **x86-64** es el estándar de facto para la programación de bajo nivel portable en sistemas Linux, macOS y Windows. Destaca por su sintaxis clara de estilo Intel, soporte para macros potentes y salida directa a formatos de objeto ejecutables como `elf64`, `win64` y `macho64`.

---

## 2. Convenciones de Llamada (Application Binary Interface - ABI)

Al escribir código en 64 bits con NASM, es vital entender las dos ABIs principales para interoperar con C/C++ y librerías del sistema:

### 1. System V AMD64 ABI (Linux, macOS, BSD)
- **Pasaje de Parámetros Enteros / Punteros**: En los registros `RDI`, `RSI`, `RDX`, `RCX`, `R8`, `R9`. (Los parámetros adicionales van a la pila).
- **Parámetros Flotantes**: Registros `XMM0` a `XMM7`.
- **Retorno de Valor**: Registro `RAX` (o `RAX:RDX` para 128 bits).
- **Alineamiento de Pila**: `RSP` debe estar alineado a 16 bytes antes de ejecutar la instrucción `CALL`.

### 2. Microsoft x64 Calling Convention (Windows)
- **Pasaje de Parámetros**: En los registros `RCX`, `RDX`, `R8`, `R9`.
- **Shadow Space (Home Space)**: El llamador debe reservar obligatoriamente 32 bytes en la pila (`sub rsp, 40` contando los 8 bytes del retorno) antes de llamar a una función API de Windows.

---

## 3. Estructura Estándar de un Programa NASM 64-bit

```nasm
default rel             ; Direccionamiento relativo a RIP por defecto (PIC)
bits 64                 ; Modo 64 bits

section .rodata
    msg db "Ensamblado con NASM 64-bit para System V ABI", 0xA, 0

section .bss
    buffer resb 64      ; Reservar 64 bytes no inicializados

section .text
    global main
    extern printf       ; Función externa de la librería C (libc)

main:
    push rbp            ; Guardar marco de pila
    mov rbp, rsp        ; Establecer puntero base
    sub rsp, 16         ; Mantener alineamiento a 16 bytes

    ; Llamar a printf(msg)
    lea rdi, [msg]      ; 1er argumento (System V ABI)
    xor rax, rax        ; 0 registros vectoriales usados (varargs)
    call printf         ; Invocar printf

    mov eax, 0          ; Código de retorno 0
    leave               ; Restaurar rbp y rsp (equivale a mov rsp, rbp / pop rbp)
    ret
```

---

## 4. Comandos de Compilación y Enlace

### En Linux (ELF64 + GCC)
```bash
# 1. Compilar código objeto con NASM
nasm -f elf64 programa.asm -o programa.o

# 2. Enlazar con GCC (incluye libc y configuración de entrada)
gcc -no-pie programa.o -o programa

# 3. Ejecutar
./programa
```

### En Windows (WIN64 + MinGW / Link.exe)
```cmd
nasm -f win64 programa.asm -o programa.obj
gcc programa.obj -o programa.exe
programa.exe
```

---

## 5. Casos de Uso en Assembler-AI

Bajo la etiqueta `x86-64-assembly-nasm`, el asistente **Assembler-AI** genera, depura y optimiza:
- Rutinas de cómputo intensivo (multiplicación de matrices, procesamiento de señales).
- Shellcodes y exploits educativos para auditorías de seguridad.
- Stubs y rutinas de enlace para integraciones C/Assembly (`extern "C"`).
