<?php
$models = [
    'Xenova/gpt2',
    'Xenova/phi-1',
    'Xenova/phi-1.5',
    'Xenova/distilbert-base-uncased-finetuned-sst-2-english',
    'bert-base-uncased',
    'distilbert-base-uncased',
    'google/bert_uncased_L-12_H-768_A-12',
];

foreach ($models as $model) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api-inference.huggingface.co/models/' . $model,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => '{"inputs": "Hello"}',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer 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',
            'Content-Type: application/json'
        ],
        CURLOPT_TIMEOUT => 30
    ]);
    $r = curl_exec($ch);
    $c = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "$model: $c\n";
    if ($c === 200) break;
}