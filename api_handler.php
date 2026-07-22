<?php

declare(strict_types=1);

use AssemblerAI\Config\Config;
use AssemblerAI\Http\JsonResponse;
use AssemblerAI\LLM\LlmProviderFactory;

require __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    JsonResponse::send(['error' => 'Método no permitido. Usa POST.'], 405);
    exit;
}

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput ?: '', true);
if (!is_array($input)) {
    JsonResponse::send(['error' => 'La petición debe ser JSON válido.'], 400);
    exit;
}

$message = trim((string) ($input['message'] ?? ''));
$runtime = Config::llm();
if ($message === '') {
    JsonResponse::send(['error' => 'No se recibió ningún mensaje.'], 422);
    exit;
}

$messageLength = function_exists('mb_strlen') ? mb_strlen($message) : strlen($message);
if ($messageLength > $runtime['max_message_length']) {
    JsonResponse::send(['error' => 'El mensaje supera el límite configurado.'], 422);
    exit;
}

try {
    $provider = LlmProviderFactory::create($runtime['provider'], Config::providers(), $runtime);
    $reply = $provider->generate($message, $runtime['system_prompt']);
    JsonResponse::send(['reply' => $reply, 'provider' => $runtime['provider']]);
} catch (Throwable $exception) {
    error_log('[Assembler-AI] ' . $exception->getMessage());
    JsonResponse::send(['error' => 'No se pudo generar la respuesta. Revisa la configuración del proveedor LLM.'], 502);
}
