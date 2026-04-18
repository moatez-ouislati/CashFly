<?php

namespace App\Service\Api;

class TranslationService
{
    private const TRANSLATE_URL = 'https://translate.googleapis.com/translate_a/single';

    public function translate(string $text, string $targetLang = 'fr', string $sourceLang = 'auto'): ?string
    {
        if (empty(trim($text))) {
            return $text;
        }

        $url = self::TRANSLATE_URL . '?' . http_build_query([
            'client' => 'gtx',
            'sl' => $sourceLang,
            'tl' => $targetLang,
            'dt' => 't',
            'q' => $text,
        ]);

        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (isset($data[0][0][0])) {
                return $data[0][0][0];
            }

            return $text;
        } catch (\Exception $e) {
            return $text;
        }
    }

    public function translateToArabic(string $text): ?string
    {
        return $this->translate($text, 'ar', 'fr');
    }

    public function translateToFrench(string $text): ?string
    {
        return $this->translate($text, 'fr', 'ar');
    }

    public function translateToEnglish(string $text): ?string
    {
        return $this->translate($text, 'en', 'fr');
    }

    public function getSupportedLanguages(): array
    {
        return [
            'fr' => 'Français',
            'ar' => 'العربية',
            'en' => 'English',
        ];
    }
}
