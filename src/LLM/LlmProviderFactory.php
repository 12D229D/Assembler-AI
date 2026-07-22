<?php

declare(strict_types=1);

namespace AssemblerAI\LLM;

use AssemblerAI\Http\HttpClient;
use InvalidArgumentException;

final class LlmProviderFactory
{
    public static function create(string $provider, array $providersConfig, array $runtime): LlmProviderInterface
    {
        $http = new HttpClient();
        $config = $providersConfig[$provider] ?? null;
        if (!is_array($config)) {
            throw new InvalidArgumentException('Proveedor LLM no soportado: ' . $provider);
        }

        return match ($provider) {
            'gemini' => new GeminiProvider($http, $config, $runtime),
            'openai' => new OpenAiProvider($http, $config, $runtime),
            'anthropic' => new AnthropicProvider($http, $config, $runtime),
            'openai_compatible' => new OpenAiCompatibleProvider($http, $config, $runtime),
            default => throw new InvalidArgumentException('Proveedor LLM no soportado: ' . $provider),
        };
    }
}
