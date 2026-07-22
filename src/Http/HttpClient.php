<?php

declare(strict_types=1);

namespace AssemblerAI\Http;

use RuntimeException;

final class HttpClient
{
    public function postJson(string $url, array $headers, array $payload, int $timeout): array
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('URL del proveedor inválida.');
        }

        $ch = curl_init($url);
        if ($ch === false) {
            throw new RuntimeException('No se pudo inicializar cURL.');
        }

        $encodedPayload = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($encodedPayload === false) {
            throw new RuntimeException('No se pudo serializar la petición JSON.');
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array_merge(['Content-Type: application/json'], $headers),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $encodedPayload,
            CURLOPT_TIMEOUT => max(1, $timeout),
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $body = curl_exec($ch);
        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            throw new RuntimeException('Error de red al contactar el proveedor: ' . $error);
        }

        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('El proveedor devolvió una respuesta no JSON.');
        }

        return ['status' => $statusCode, 'body' => $decoded];
    }
}
