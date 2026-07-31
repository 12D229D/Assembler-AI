<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido. Utiliza POST.']);
    exit;
}

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Cuerpo de solicitud JSON inválido.']);
    exit;
}

$message = isset($input['message']) ? trim($input['message']) : '';
if (empty($message)) {
    http_response_code(422);
    echo json_encode(['error' => 'No se recibió ningún mensaje.']);
    exit;
}

$provider = strtolower(trim(isset($input['provider']) ? $input['provider'] : (getenv('LLM_PROVIDER') ?: 'gemini')));
$clientApiKey = isset($input['apiKey']) ? trim($input['apiKey']) : '';
$customModel = isset($input['customModel']) ? trim($input['customModel']) : '';
$customBaseUrl = isset($input['customBaseUrl']) ? trim($input['customBaseUrl']) : '';

$systemPrompt = getenv('LLM_SYSTEM_PROMPT') ?: 'Eres un asistente experto en programación en Lenguaje Ensamblador x86. Respondes de forma clara, técnica y precisa, usando ejemplos en NASM o MASM cuando sea útil. Ayudas a resolver errores, optimizar rutinas y explicar conceptos complejos de bajo nivel de forma sencilla.';

function makeHttpRequest($url, $method = 'POST', $headers = [], $body = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 35);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    if ($body !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_string($body) ? $body : json_encode($body));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        throw new Exception("Error cURL de conexión: " . $curlError);
    }

    return [
        'code' => $httpCode,
        'data' => json_decode($response, true),
        'raw' => $response
    ];
}

try {
    $reply = '';

    if ($provider === 'gemini') {
        $apiKey = $clientApiKey ?: getenv('GEMINI_API_KEY');
        $model = $customModel ?: (getenv('GEMINI_MODEL') ?: 'gemini-1.5-flash');
        $baseUrl = $customBaseUrl ?: (getenv('GEMINI_API_BASE') ?: 'https://generativelanguage.googleapis.com/v1beta');

        if (empty($apiKey) && empty($customBaseUrl)) {
            throw new Exception('No se proporcionó API Key para Gemini. Por favor ingrésala en el menú superior.');
        }

        $endpoint = rtrim($baseUrl, '/') . '/models/' . urlencode($model) . ':generateContent?key=' . urlencode($apiKey);

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemPrompt]]
            ],
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $message]]]
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'maxOutputTokens' => 2048
            ]
        ];

        $res = makeHttpRequest($endpoint, 'POST', ['Content-Type: application/json'], $payload);

        if ($res['code'] !== 200) {
            $errMsg = isset($res['data']['error']['message']) ? $res['data']['error']['message'] : $res['raw'];
            throw new Exception("Error {$res['code']} de Gemini API: " . $errMsg);
        }

        $reply = isset($res['data']['candidates'][0]['content']['parts'][0]['text'])
            ? $res['data']['candidates'][0]['content']['parts'][0]['text']
            : '';

        if (empty(trim($reply))) {
            throw new Exception('Gemini no devolvió texto en su respuesta.');
        }

    } elseif ($provider === 'openai' || $provider === 'openai_compatible' || $provider === 'mistral') {
        $isCompatible = ($provider === 'openai_compatible');
        $isMistral = ($provider === 'mistral');
        $apiKeyEnv = $isMistral ? 'MISTRAL_API_KEY' : ($isCompatible ? 'OPENAI_COMPATIBLE_API_KEY' : 'OPENAI_API_KEY');
        $modelEnv = $isMistral ? 'MISTRAL_MODEL' : ($isCompatible ? 'OPENAI_COMPATIBLE_MODEL' : 'OPENAI_MODEL');
        $baseUrlEnv = $isMistral ? 'MISTRAL_API_BASE' : ($isCompatible ? 'OPENAI_COMPATIBLE_API_BASE' : 'OPENAI_API_BASE');
        $defaultModel = $isMistral ? 'codestral-latest' : ($isCompatible ? '' : 'gpt-4o-mini');
        $defaultBaseUrl = $isMistral ? 'https://api.mistral.ai/v1' : ($isCompatible ? '' : 'https://api.openai.com/v1');

        $apiKey = $clientApiKey ?: getenv($apiKeyEnv);
        $model = $customModel ?: (getenv($modelEnv) ?: $defaultModel);
        $baseUrl = rtrim($customBaseUrl ?: (getenv($baseUrlEnv) ?: $defaultBaseUrl), '/');

        if (empty($apiKey) && !$isCompatible) {
            throw new Exception("{$apiKeyEnv} no está configurada.");
        }

        $endpoint = $baseUrl . '/chat/completions';
        $headers = ['Content-Type: application/json'];
        if (!empty($apiKey)) {
            $headers[] = 'Authorization: Bearer ' . $apiKey;
        }

        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $message]
            ],
            'temperature' => 0.2,
            'max_tokens' => 2048
        ];

        $res = makeHttpRequest($endpoint, 'POST', $headers, $payload);

        if ($res['code'] !== 200) {
            $errMsg = isset($res['data']['error']['message']) ? $res['data']['error']['message'] : $res['raw'];
            $providerLabel = $isMistral ? 'Mistral API' : 'API OpenAI';
            throw new Exception("Error {$res['code']} de {$providerLabel}: " . $errMsg);
        }

        $reply = isset($res['data']['choices'][0]['message']['content']) ? $res['data']['choices'][0]['message']['content'] : '';
        if (empty(trim($reply))) {
            $providerLabel = $isMistral ? 'Mistral' : 'OpenAI';
            throw new Exception("{$providerLabel} no devolvió texto utilizable.");
        }

    } elseif ($provider === 'anthropic') {
        $apiKey = $clientApiKey ?: getenv('ANTHROPIC_API_KEY');
        $model = $customModel ?: (getenv('ANTHROPIC_MODEL') ?: 'claude-3-5-haiku-latest');
        $baseUrl = rtrim($customBaseUrl ?: (getenv('ANTHROPIC_API_BASE') ?: 'https://api.anthropic.com/v1'), '/');

        if (empty($apiKey)) {
            throw new Exception('ANTHROPIC_API_KEY no está configurada.');
        }

        $endpoint = $baseUrl . '/messages';
        $headers = [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01'
        ];

        $payload = [
            'model' => $model,
            'system' => $systemPrompt,
            'messages' => [
                ['role' => 'user', 'content' => $message]
            ],
            'temperature' => 0.2,
            'max_tokens' => 2048
        ];

        $res = makeHttpRequest($endpoint, 'POST', $headers, $payload);

        if ($res['code'] !== 200) {
            $errMsg = isset($res['data']['error']['message']) ? $res['data']['error']['message'] : $res['raw'];
            throw new Exception("Error {$res['code']} de Anthropic API: " . $errMsg);
        }

        $reply = isset($res['data']['content'][0]['text']) ? $res['data']['content'][0]['text'] : '';
        if (empty(trim($reply))) {
            throw new Exception('Anthropic no devolvió texto utilizable.');
        }

    } else {
        http_response_code(400);
        echo json_encode(['error' => "Proveedor LLM no soportado: {$provider}"]);
        exit;
    }

    echo json_encode(['reply' => $reply, 'provider' => $provider]);

} catch (Exception $e) {
    http_response_code(502);
    echo json_encode(['error' => $e->getMessage(), 'details' => $e->getMessage()]);
}
