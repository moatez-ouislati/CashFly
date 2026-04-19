<?php
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => 'https://api-inference.huggingface.co/chat/completions',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        'model' => 'mistralai/Mistral-7B-Instruct-v0.3',
        'messages' => [
            ['role' => 'user', 'content' => 'Hello']
        ]
    ]),
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer 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',
        'Content-Type: application/json'
    ],
    CURLOPT_TIMEOUT => 60
]);
$r = curl_exec($ch);
$c = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo 'Code: ' . $c . "\nResponse: " . $r;