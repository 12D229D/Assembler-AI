<?php

declare(strict_types=1);

namespace AssemblerAI\Config;

final class Config
{
    public static function llm(): array
    {
        return [
            'provider' => strtolower(Env::get('LLM_PROVIDER', 'gemini') ?? 'gemini'),
            'system_prompt' => Env::get('LLM_SYSTEM_PROMPT', self::defaultSystemPrompt()),
            'timeout' => Env::int('LLM_TIMEOUT', 30),
            'max_message_length' => Env::int('LLM_MAX_MESSAGE_LENGTH', 8000),
            'temperature' => Env::float('LLM_TEMPERATURE', 0.2),
            'max_tokens' => Env::int('LLM_MAX_TOKENS', 2048),
        ];
    }

    public static function providers(): array
    {
        return [
            'gemini' => [
                'api_key' => Env::get('GEMINI_API_KEY'),
                'model' => Env::get('GEMINI_MODEL', 'gemini-1.5-flash-latest'),
                'base_url' => rtrim(Env::get('GEMINI_API_BASE', 'https://generativelanguage.googleapis.com/v1beta') ?? '', '/'),
            ],
            'openai' => [
                'api_key' => Env::get('OPENAI_API_KEY'),
                'model' => Env::get('OPENAI_MODEL', 'gpt-4o-mini'),
                'base_url' => rtrim(Env::get('OPENAI_API_BASE', 'https://api.openai.com/v1') ?? '', '/'),
            ],
            'anthropic' => [
                'api_key' => Env::get('ANTHROPIC_API_KEY'),
                'model' => Env::get('ANTHROPIC_MODEL', 'claude-3-5-haiku-latest'),
                'base_url' => rtrim(Env::get('ANTHROPIC_API_BASE', 'https://api.anthropic.com/v1') ?? '', '/'),
                'version' => Env::get('ANTHROPIC_VERSION', '2023-06-01'),
            ],
            'openai_compatible' => [
                'api_key' => Env::get('OPENAI_COMPATIBLE_API_KEY'),
                'model' => Env::get('OPENAI_COMPATIBLE_MODEL'),
                'base_url' => rtrim(Env::get('OPENAI_COMPATIBLE_API_BASE', '') ?? '', '/'),
            ],
        ];
    }

    private static function defaultSystemPrompt(): string
    {
        return 'Eres un asistente experto en programación en Lenguaje Ensamblador x86. Respondes de forma clara, técnica y precisa, usando ejemplos en NASM o MASM cuando sea útil. Ayudas a resolver errores, optimizar rutinas y explicar conceptos complejos de bajo nivel de forma sencilla.';
    }
}
