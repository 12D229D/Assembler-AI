<?php

declare(strict_types=1);

namespace AssemblerAI\LLM;

interface LlmProviderInterface
{
    /**
     * Genera una respuesta para el mensaje del usuario usando el prompt del sistema.
     */
    public function generate(string $message, string $systemPrompt): string;
}
