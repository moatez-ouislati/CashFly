<?php
namespace App\Service\Api;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\FormatException;

class AiKeyProvider
{
    private ?array $localEnv = null;

    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private readonly ?string $projectDir = null
    ) {
    }

    public function getOpenAiKey(): string
    {
        return $this->getConfiguredKey('OPENAI_KEY');
    }

    public function getOpenRouterKey(): string
    {
        return $this->getConfiguredKey('OPENROUTER_KEY');
    }

    public function getNvidiaKey(): string
    {
        return $this->getConfiguredKey('DEEPSEEK_NVIDIA_KEY', ['NVIDIA_API_KEY']);
    }

    public function getChatbotNvidiaKey(): string
    {
        return $this->getConfiguredKey('CHATBOT_NVIDIA_KEY', ['NVIDIA_API_KEY', 'DEEPSEEK_NVIDIA_KEY']);
    }

    public function getInvestmentNvidiaKey(): string
    {
        $key = $this->getConfiguredKey('INVESTMENT_NVIDIA_KEY', ['DEEPSEEK_NVIDIA_KEY', 'NVIDIA_API_KEY']);
        error_log('AiKeyProvider: INVESTMENT_NVIDIA_KEY result: ' . ($key ? 'FOUND (length ' . strlen($key) . ')' : 'NOT FOUND'));
        error_log('AiKeyProvider: getenv(INVESTMENT_NVIDIA_KEY)=' . (getenv('INVESTMENT_NVIDIA_KEY') ?: 'null'));
        error_log('AiKeyProvider: $_ENV[INVESTMENT_NVIDIA_KEY]=' . ($_ENV['INVESTMENT_NVIDIA_KEY'] ?? 'null'));
        error_log('AiKeyProvider: projectDir=' . ($this->projectDir ?? 'null'));
        return $key;
    }

    public function getHfToken(): string
    {
        return $this->getConfiguredKey('HF_TOKEN');
    }

    private function getConfiguredKey(string $name, array $fallbackNames = []): string
    {
        foreach ([$name, ...$fallbackNames] as $keyName) {
            foreach ($this->getEnvCandidates($keyName) as $value) {
                if ($this->isConfigured($value)) {
                    return $value;
                }
            }
        }

        return '';
    }

    /**
     * Symfony can load .env.local.php instead of .env.local. Reading .env.local
     * here keeps API keys usable when the compiled env file is stale.
     */
    private function getEnvCandidates(string $name): array
    {
        $getenvValue = getenv($name);

        return [
            $getenvValue === false ? '' : (string) $getenvValue,
            (string) ($_ENV[$name] ?? ''),
            (string) ($_SERVER[$name] ?? ''),
            (string) ($this->getLocalEnv()[$name] ?? ''),
        ];
    }

    private function getLocalEnv(): array
    {
        if ($this->localEnv !== null) {
            return $this->localEnv;
        }

        $projectDir = $this->projectDir ?? dirname(__DIR__, 3);
        $path = $projectDir . '/.env.local';
        error_log('AiKeyProvider: Looking for .env.local at: ' . $path);
        error_log('AiKeyProvider: File exists: ' . (is_file($path) ? 'YES' : 'NO'));
        
        if (!is_file($path) || !is_readable($path)) {
            error_log('AiKeyProvider: .env.local not found or not readable');
            return $this->localEnv = [];
        }

        try {
            $content = (string) file_get_contents($path);
            error_log('AiKeyProvider: .env.local content length: ' . strlen($content));
            $parsed = (new Dotenv())->parse($content, $path);
            error_log('AiKeyProvider: Parsed keys: ' . implode(', ', array_keys($parsed)));
            return $this->localEnv = $parsed;
        } catch (FormatException $e) {
            error_log('AiKeyProvider: FormatException parsing .env.local: ' . $e->getMessage());
            return $this->localEnv = [];
        }
    }

    private function isConfigured(?string $value): bool
    {
        $value = trim((string) $value);

        return $value !== '' && !str_contains(strtoupper($value), 'YOUR_') && strtolower($value) !== 'demo';
    }
}
