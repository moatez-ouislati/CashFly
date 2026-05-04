<?php

$tests = [
    'Propriétaire (MiniMax)' => [
        'key' => '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',
        'model' => 'minimaxai/minimax-m2.7'
    ],
    'Investisseur (DeepSeek)' => [
        'key' => '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',
        'model' => 'deepseek-ai/deepseek-v3.2'
    ],
    'Fallback (DeepSeek OLD)' => [
        'key' => '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',
        'model' => 'deepseek-ai/deepseek-v3.2'
    ],
    'GLM (Previous)' => [
        'key' => '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',
        'model' => 'zai-org/glm-4.7'
    ]
];

$url = 'https://integrate.api.nvidia.com/v1/chat/completions';

foreach ($tests as $name => $config) {
    echo "Testing $name with model {$config['model']}...\n";
    
    $data = [
        'model' => $config['model'],
        'messages' => [['role' => 'user', 'content' => 'Hello']],
        'max_tokens' => 5
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $config['key'],
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "HTTP Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 100) . "...\n\n";
}
