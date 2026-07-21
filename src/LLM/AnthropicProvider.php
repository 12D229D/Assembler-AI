<?php

declare(strict_types=1);

namespace AssemblerAI\LLM;

use RuntimeException;

final class AnthropicProvider extends AbstractProvider
{
    public function generate(string $message, string $systemPrompt): string
    {
        $apiKey = $this->requireConfig('api_key', 'ANTHROPIC_API_KEY');
        $model = $this->requireConfig('model', 'ANTHROPIC_MODEL');
        $baseUrl = $this->requireConfig('base_url', 'ANTHROPIC_API_BASE');
        $version = $this->requireConfig('version', 'ANTHROPIC_VERSION');

        $payload = [
            'model' => $model,
            'system' => $systemPrompt,
            'messages' => [['role' => 'user', 'content' => $message]],
            'temperature' => $this->runtime['temperature'],
            'max_tokens' => $this->runtime['max_tokens'],
        ];

        $body = $this->ensureSuccess(
            $this->http->postJson(rtrim($baseUrl, '/') . '/messages', ['x-api-key: ' . $apiKey, 'anthropic-version: ' . $version], $payload, $this->runtime['timeout']),
            'Anthropic'
        );

        $text = $body['content'][0]['text'] ?? null;
        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException('Anthropic no devolvió texto utilizable.');
        }

        return $text;
    }
}
