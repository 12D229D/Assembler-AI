<?php

declare(strict_types=1);

namespace AssemblerAI\LLM;

final class OpenAiCompatibleProvider extends OpenAiProvider
{
    public function generate(string $message, string $systemPrompt): string
    {
        $apiKey = $this->requireConfig('api_key', 'OPENAI_COMPATIBLE_API_KEY');
        $model = $this->requireConfig('model', 'OPENAI_COMPATIBLE_MODEL');
        $baseUrl = $this->requireConfig('base_url', 'OPENAI_COMPATIBLE_API_BASE');

        return $this->chatCompletion($baseUrl, $apiKey, $model, $message, $systemPrompt, 'Proveedor compatible con OpenAI');
    }
}
