# Manual Avanzado: El Ensamblador NASM (`x86-nasm`)

**Categoría / Etiqueta**: `x86-nasm`  
**Descripción Corta**: Uso del ensamblador NASM (Netwide Assembler) para escribir código de bajo nivel para procesadores con arquitectura x86.  
**Estado**: Herramienta de Compilación Principal  
**Nivel de Abstracción**: Sintaxis, Preprocesador y Formatos de Objeto

---

## 1. Visión General

**NASM (Netwide Assembler)** es un ensamblador de código abierto, altamente optimizado y portátil para las arquitecturas x86 y x86-64. Se caracteriza por adoptar la sintaxis Intel de manera limpia y sin ambigüedades, libre de la complejidad histórica de MASM, y por ofrecer un potente motor de preprocesador integrado.

---

## 2. Directivas Fundamentales de NASM

- `BITS 16` / `BITS 32` / `BITS 64`: Especifica explícitamente el modo de procesador objetivo.
- `DEFAULT REL` / `DEFAULT ABS`: Establece si los direccionamientos a símbolos son por defecto relativos al registro de instrucción (`RIP`) o absolutos.
- `SECTION .text` / `.data` / `.rodata` / `.bss`: Organiza el código y los datos en segmentos estándar.
- `GLOBAL symbol` / `EXTERN symbol`: Exporta o importa símbolos para el enlazador (*Linker*).
- `ALIGN n`: Alinea la siguiente instrucción o variable a una frontera de $n$ bytes.

---

## 3. Preprocesador y Macros Avanzados

### 1. Definición de Constantes y Macros Monolínea (`%define`)
```nasm
%define SYS_WRITE 1
%define STDOUT    1

mov rax, SYS_WRITE
mov rdi, STDOUT
```

### 2. Macros Multilínea (`%macro` / `%endmacro`)
```nasm
; Macro para guardar todos los registros de proposito general en x86-64
%macro PUSH_ALL 0
    push rax
    push rbx
    push rcx
    push rdx
    push rsi
    push rdi
    push rbp
    push r8
    push r9
    push r10
    push r11
%endmacro

%macro POP_ALL 0
    pop r11
    pop r10
    pop r9
    pop r8
    pop rbp
    pop rdi
    pop rsi
    pop rdx
    pop rcx
    pop rbx
    pop rax
%endmacro
```

### 3. Estructuras de Datos (`struc` / `endstruc`)
```nasm
struc Persona
    .id:    resd 1      ; int 32-bit (4 bytes)
    .edad:  resw 1      ; short 16-bit (2 bytes)
    .nombre:resb 32     ; char array (32 bytes)
endstruc

section .bss
    usuario resb Persona_size    ; Reservar espacio para la estructura
```

---

## 4. Principales Formatos de Salida de NASM (`-f`)

| Formato | Bandera CLI | Descripción |
| :--- | :--- | :--- |
| **Binario Puro** | `-f bin` | Archivo ejecutable plano sin cabeceras. Ideal para Bootloaders MBR y Firmware. |
| **ELF 32-bit** | `-f elf32` | Formato estándar de ejecutables y librerías en Linux 32-bit. |
| **ELF 64-bit** | `-f elf64` | Formato estándar de ejecutables y librerías en Linux 64-bit. |
| **Win32** | `-f win32` | Código objeto de 32 bits para Windows (COFF). |
| **Win64** | `-f win64` | Código objeto de 64 bits para Windows (x64 COFF). |
| **Mach-O** | `-f macho64` | Código objeto de 64 bits para Apple macOS. |

---

## 5. Prospectiva y Escalabilidad

La etiqueta `x86-nasm` en **Assembler-AI** se consolida como la referencia estándar para la validación sintáctica de scripts, refactorización de código macrocomplejo y generación de plantillas de compilación multiplataforma.
