<?php

declare(strict_types=1);

namespace AssemblerAI\LLM;

use RuntimeException;

class OpenAiProvider extends AbstractProvider
{
    public function generate(string $message, string $systemPrompt): string
    {
        $apiKey = $this->requireConfig('api_key', 'OPENAI_API_KEY');
        $model = $this->requireConfig('model', 'OPENAI_MODEL');
        $baseUrl = $this->requireConfig('base_url', 'OPENAI_API_BASE');

        return $this->chatCompletion($baseUrl, $apiKey, $model, $message, $systemPrompt, 'OpenAI');
    }

    public function chatCompletion(string $baseUrl, string $apiKey, string $model, string $message, string $systemPrompt, string $providerName): string
    {
        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $message],
            ],
            'temperature' => $this->runtime['temperature'],
            'max_tokens' => $this->runtime['max_tokens'],
        ];

        $body = $this->ensureSuccess(
            $this->http->postJson(rtrim($baseUrl, '/') . '/chat/completions', ['Authorization: Bearer ' . $apiKey], $payload, $this->runtime['timeout']),
            $providerName
        );

        $text = $body['choices'][0]['message']['content'] ?? null;
        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException("{$providerName} no devolvió texto utilizable.");
        }

        return $text;
    }
}
