# Manual de Arquitectura y Kernel: Desarrollo para x86 (`x86-kernel`)

**Categoría / Etiqueta**: `x86-kernel`  
**Descripción Corta**: Desarrollo, configuración o estudio de núcleos (kernels) de sistemas operativos optimizados o diseñados específicamente para la arquitectura x86.  
**Estado**: Guía de Ingeniería de Sistemas Operativos  
**Nivel de Abstracción**: Bare-Metal / Ring 0 Systems Programming

---

## 1. Visión General

El desarrollo de núcleos (*kernels*) en la arquitectura x86/x86-64 exige la programación en contacto directo con el hardware (*Bare-Metal*), gestionando la secuencia de arranque, la configuración de la Unidad de Gestión de Memoria (MMU), el tratamiento de interrupciones de hardware y las tablas del sistema requeridas por la especificación de Intel y AMD.

---

## 2. Secuencia de Arranque (Boot Sequence)

```
[ Power On / Reset ]
         │
         ▼
[ Firmware BIOS / UEFI ] ──► Carga MBR (0x7C00) o EFI Application
         │
         ▼
[ Bootloader (GRUB / Limine) ] ──► Pasa control en Modo Protegido / Long Mode
         │
         ▼
[ Kernel Entry Point (Ring 0) ]
         │
         ├── Initialize GDT (Global Descriptor Table)
         ├── Initialize IDT (Interrupt Descriptor Table)
         ├── Enable Paging (CR0, CR3, CR4)
         └── Initialize Drivers & Multitasking
```

---

## 3. Estructuras de Control del Kernel x86

### 1. Tabla Global de Descriptores (GDT - Global Descriptor Table)
Define los límites, privilegios y accesos a los segmentos de código y datos del sistema. En 64 bits se simplifica pero sigue siendo obligatoria para definir el Selector de Código de Kernel (`Ring 0`) y Usuario (`Ring 3`).

### 2. Tabla de Descriptores de Interrupción (IDT - Interrupt Descriptor Table)
Mapea las interrupciones del procesador (Excepciones hardware 0-31, IRQs de dispositivos 32-47 y Syscalls por software) a sus respectivas rutinas de servicio (*Interrupt Service Routines - ISR*).

```
IDT Entry Format (x86-32):
+-----------------+---+-----------------+-----------------+
| Offset 31..16   | P | DPL | Selector    | Offset 15..0    |
+-----------------+---+-----------------+-----------------+
```

### 3. Paginación y Registros de Control
- **CR0**: Bit `PE` (Protection Enable), Bit `PG` (Paging Enable).
- **CR2**: Contiene la dirección lineal que provocó el último fallo de página (*Page Fault*).
- **CR3**: Contiene la dirección física de la raíz de la tabla de páginas (Page Directory / PML4).
- **CR4**: Habilita extensiones (PAE, PSE, SMEP, SMAP).

---

## 4. Ejemplo Práctico: Entrada de Kernel C Minimalista con ASM Stub

### Archivo Assembler de Entrada (`boot.asm`)
```nasm
bits 32
section .multiboot      ; Cabecera estándar Multiboot 1 (para GRUB)
    align 4
    dd 0x1BADB002       ; Magic number
    dd 0x00             ; Flags
    dd - (0x1BADB002)   ; Checksum

section .text
global _start
extern kernel_main

_start:
    cli                 ; Deshabilitar interrupciones
    mov esp, stack_top  ; Configurar pila de kernel
    call kernel_main    ; Llamar función principal en C
.halt:
    hlt                 ; Detener CPU
    jmp .halt

section .bss
align 16
stack_bottom:
    resb 16384          ; 16 KB de pila para el kernel
stack_top:
```

### Archivo Kernel en C (`kernel.c`)
```c
// Escribir texto directo a la memoria de video VGA (0xB8000)
void kernel_main(void) {
    volatile char* vga_buffer = (volatile char*)0xB8000;
    const char* str = "Assembler-AI Kernel Iniciar [OK]";
    
    for (int i = 0; str[i] != '\0'; i++) {
        vga_buffer[i * 2] = str[i];        // Carácter ASCII
        vga_buffer[i * 2 + 1] = 0x0F;     // Atributo: Blanco sobre negro
    }
}
```

---

## 5. Prospectiva y Escalabilidad en Assembler-AI

Bajo la etiqueta `x86-kernel`, **Assembler-AI** asiste a ingenieros en:
1. Diseño de microkernels y sistemas monolíticos modernos (Rust / C / Assembly).
2. Implementación de aislamiento de memoria (SMEP / SMAP) para prevenir ataques Kernel Exploits.
3. Desarrollo de hipervisores Tipo 1 / Tipo 2 mediante extensiones VT-x (`VMX`) y AMD-V (`SVM`).
