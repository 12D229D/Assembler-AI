<?php

declare(strict_types=1);

namespace AssemblerAI\LLM;

use AssemblerAI\Http\HttpClient;
use RuntimeException;

abstract class AbstractProvider implements LlmProviderInterface
{
    public function __construct(protected HttpClient $http, protected array $config, protected array $runtime)
    {
    }

    protected function requireConfig(string $key, string $label): string
    {
        $value = $this->config[$key] ?? null;
        if (!is_string($value) || trim($value) === '') {
            throw new RuntimeException("Falta configurar {$label}.");
        }

        return trim($value);
    }

    protected function ensureSuccess(array $response, string $provider): array
    {
        if (($response['status'] ?? 0) >= 200 && ($response['status'] ?? 0) < 300) {
            return $response['body'];
        }

        $message = $response['body']['error']['message'] ?? $response['body']['error'] ?? 'Error desconocido del proveedor.';
        if (is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: 'Error desconocido del proveedor.';
        }

        throw new RuntimeException("{$provider} respondió con error HTTP {$response['status']}: {$message}");
    }
}
