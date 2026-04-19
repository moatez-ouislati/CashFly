<?php
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://openrouter.ai/api/v1/chat/completions',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        'model' => 'mistralai/Mistral-7B-Instruct-v0.3',
        'messages' => [
            ['role' => 'user', 'content' => 'Hello']
        ],
        'max_tokens' => 50,
    ]),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer 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',
        'Content-Type: application/json',
        'HTTP-Referer: https://cashfly.tn',
        'X-Title: CashFly',
    ],
    CURLOPT_TIMEOUT => 60
]);
$r = curl_exec($ch);
$c = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo 'Code: ' . $c . "\nResponse: " . $r;