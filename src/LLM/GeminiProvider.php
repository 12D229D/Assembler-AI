<?php

declare(strict_types=1);

namespace AssemblerAI\LLM;

use RuntimeException;

final class GeminiProvider extends AbstractProvider
{
    public function generate(string $message, string $systemPrompt): string
    {
        $apiKey = $this->requireConfig('api_key', 'GEMINI_API_KEY');
        $model = $this->requireConfig('model', 'GEMINI_MODEL');
        $baseUrl = $this->requireConfig('base_url', 'GEMINI_API_BASE');
        $url = sprintf('%s/models/%s:generateContent?key=%s', $baseUrl, rawurlencode($model), rawurlencode($apiKey));

        $payload = [
            'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
            'contents' => [['role' => 'user', 'parts' => [['text' => $message]]]],
            'generationConfig' => [
                'temperature' => $this->runtime['temperature'],
                'maxOutputTokens' => $this->runtime['max_tokens'],
            ],
        ];

        $body = $this->ensureSuccess($this->http->postJson($url, [], $payload, $this->runtime['timeout']), 'Gemini');
        $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException('Gemini no devolvió texto utilizable.');
        }

        return $text;
    }
}
