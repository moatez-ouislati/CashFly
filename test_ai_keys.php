<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Service\Api\AiKeyProvider;

// Load .env files if available
function loadEnvFile(string $path): void {
    if (!file_exists($path)) return;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        // Remove quotes if present
        if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
            $value = substr($value, 1, -1);
        }
        
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
    }
}

loadEnvFile(__DIR__ . '/.env');
loadEnvFile(__DIR__ . '/.env.local');

$keyProvider = new AiKeyProvider();

$keys = [
    'OPENAI_KEY' => $keyProvider->getOpenAiKey(),
    'OPENROUTER_KEY' => $keyProvider->getOpenRouterKey(),
    'CHATBOT_NVIDIA_KEY' => $keyProvider->getChatbotNvidiaKey(),
    'INVESTMENT_NVIDIA_KEY' => $keyProvider->getInvestmentNvidiaKey(),
    'HF_TOKEN' => $keyProvider->getHfToken(),
];

echo "=== AI API Keys Test ===\n\n";

foreach ($keys as $name => $value) {
    $status = empty($value) ? '❌ NOT SET' : '✅ SET';
    $masked = empty($value) ? '(empty)' : substr($value, 0, 15) . '...' . substr($value, -4);
    echo "{$name}: {$status} - {$masked}\n";
}

echo "\n=== Testing API Connectivity ===\n\n";

// Test NVIDIA API for chatbot
$chatbotKey = $keyProvider->getChatbotNvidiaKey();
if (!empty($chatbotKey)) {
    echo "Testing CHATBOT_NVIDIA_KEY...\n";
    $ch = curl_init('https://integrate.api.nvidia.com/v1/models');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $chatbotKey]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "  HTTP Status: {$httpCode} " . ($httpCode === 200 ? '✅' : '❌') . "\n";
}

// Test NVIDIA API for investment
$investmentKey = $keyProvider->getInvestmentNvidiaKey();
if (!empty($investmentKey)) {
    echo "Testing INVESTMENT_NVIDIA_KEY...\n";
    $ch = curl_init('https://integrate.api.nvidia.com/v1/models');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $investmentKey]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "  HTTP Status: {$httpCode} " . ($httpCode === 200 ? '✅' : '❌') . "\n";
}

// Test OpenRouter
$openRouterKey = $keyProvider->getOpenRouterKey();
if (!empty($openRouterKey)) {
    echo "Testing OPENROUTER_KEY...\n";
    $ch = curl_init('https://openrouter.ai/api/v1/auth/key');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $openRouterKey]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "  HTTP Status: {$httpCode} " . ($httpCode === 200 ? '✅' : '❌') . "\n";
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        echo "  Rate Limit: " . ($data['data']['rate_limit']['requests'] ?? 'N/A') . "\n";
    }
}

// Test OpenAI
$openAiKey = $keyProvider->getOpenAiKey();
if (!empty($openAiKey)) {
    echo "Testing OPENAI_KEY...\n";
    $ch = curl_init('https://api.openai.com/v1/models');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $openAiKey]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "  HTTP Status: {$httpCode} " . ($httpCode === 200 ? '✅' : '❌') . "\n";
}

echo "\n=== Test Complete ===\n";
