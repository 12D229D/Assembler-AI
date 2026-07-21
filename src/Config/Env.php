<?php

declare(strict_types=1);

namespace AssemblerAI\Config;

/**
 * Carga variables de entorno desde .env sin dependencias externas.
 */
final class Env
{
    public static function load(string $path): void
    {
        if (!is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode('=', $line, 2));
            if ($key === '' || getenv($key) !== false) {
                continue;
            }

            $value = self::normalizeValue($value);
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        return $value === false || $value === '' ? $default : $value;
    }

    public static function int(string $key, int $default): int
    {
        $value = self::get($key);
        return $value !== null && filter_var($value, FILTER_VALIDATE_INT) !== false ? (int) $value : $default;
    }

    public static function float(string $key, float $default): float
    {
        $value = self::get($key);
        return $value !== null && is_numeric($value) ? (float) $value : $default;
    }

    private static function normalizeValue(string $value): string
    {
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
