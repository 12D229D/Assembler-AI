<?php
// Fichero: api_handler.php

// 1. Configuración de seguridad y respuesta
header('Content-Type: application/json'); // Indicamos que la respuesta será en formato JSON

// ¡IMPORTANTE! Pega tu clave de API de Gemini aquí.
$apiKey = 'AIzaSyAknlhqXhES0FQ0OfkSiqfAWo3OjV88Scc';

// 2. Recibir la pregunta del usuario desde el frontend
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['message'])) {
    echo json_encode(['error' => 'No se recibió ningún mensaje.']);
    exit;
}
$userMessage = $input['message'];

// 3. Tu prompt personalizado para el bot
$systemPrompt = "Eres un asistente experto en programación en Lenguaje Ensamblador x86. Respondes de forma clara, técnica y precisa, usando ejemplos en NASM o MASM cuando sea útil. Ayudas a resolver errores, optimizar rutinas, y explicar conceptos complejos de bajo nivel de forma sencilla.";

// 4. Preparar la petición para la API de Gemini
$apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $apiKey;

$data = [
    // El "system_instruction" establece el comportamiento del modelo
    'system_instruction' => [
        'parts' => [
            ['text' => $systemPrompt]
        ]
    ],
    // "contents" es el historial de la conversación. Aquí solo enviamos el mensaje actual.
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => $userMessage]
            ]
        ]
    ]
];

// 5. Realizar la llamada a la API usando cURL (estándar en hosting)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Es importante para la seguridad

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 6. Procesar la respuesta de Gemini y enviarla al frontend
if ($httpcode == 200) {
    $responseData = json_decode($response, true);
    // Navegamos por la estructura de la respuesta para obtener el texto
    $botResponseText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No he podido procesar tu solicitud.';
    echo json_encode(['reply' => $botResponseText]);
} else {
    // Si hay un error, lo enviamos para depuración
    echo json_encode(['error' => 'Error al contactar la API de Gemini.', 'details' => json_decode($response)]);
}
?>