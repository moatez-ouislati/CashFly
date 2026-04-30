<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OcrService
{
    private const API_URL = 'https://api.ocr.space/parse/image';
    private const API_KEY = 'helloworld';

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function extractTextFromFile(string $filePath): array
    {
        $result = [
            'success' => false,
            'text' => '',
            'error' => null,
        ];

        if (!file_exists($filePath)) {
            $result['error'] = 'Fichier non trouvé';
            return $result;
        }

        $fileContent = file_get_contents($filePath);
        $base64Image = base64_encode($fileContent);

        $mimeType = $this->getMimeType($filePath);

        try {
            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'apikey' => self::API_KEY,
                ],
                'body' => [
                    'base64Image' => 'data:' . $mimeType . ';base64,' . $base64Image,
                    'language' => 'fre',
                    'isOverlayRequired' => 'false',
                    'detectOrientation' => 'true',
                    'scale' => 'true',
                    'OCREngine' => '2',
                ],
            ]);

            $data = $response->toArray();

            if (isset($data['ParsedResults']) && count($data['ParsedResults']) > 0) {
                $text = '';
                foreach ($data['ParsedResults'] as $parsedResult) {
                    if (isset($parsedResult['ParsedText'])) {
                        $text .= $parsedResult['ParsedText'] . "\n\n";
                    }
                }
                
                $result['success'] = true;
                $result['text'] = trim($text);
            } elseif (isset($data['ErrorMessage'])) {
                $result['error'] = $data['ErrorMessage'][0] ?? 'Erreur OCR';
            } else {
                $result['error'] = 'Aucun texte extrait';
            }

        } catch (TransportExceptionInterface $e) {
            $result['error'] = 'Erreur de connexion: ' . $e->getMessage();
        }

        return $result;
    }

    public function extractTextFromBase64(string $base64Data, string $mimeType = 'image/png'): array
    {
        $result = [
            'success' => false,
            'text' => '',
            'error' => null,
        ];

        try {
            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'apikey' => self::API_KEY,
                ],
                'body' => [
                    'base64Image' => 'data:' . $mimeType . ';base64,' . $base64Data,
                    'language' => 'fre',
                    'isOverlayRequired' => 'false',
                    'detectOrientation' => 'true',
                    'scale' => 'true',
                    'OCREngine' => '2',
                ],
            ]);

            $data = $response->toArray();

            if (isset($data['ParsedResults']) && count($data['ParsedResults']) > 0) {
                $text = '';
                foreach ($data['ParsedResults'] as $parsedResult) {
                    if (isset($parsedResult['ParsedText'])) {
                        $text .= $parsedResult['ParsedText'] . "\n\n";
                    }
                }
                
                $result['success'] = true;
                $result['text'] = trim($text);
            } elseif (isset($data['ErrorMessage'])) {
                $result['error'] = $data['ErrorMessage'][0] ?? 'Erreur OCR';
            } else {
                $result['error'] = 'Aucun texte extrait';
            }

        } catch (TransportExceptionInterface $e) {
            $result['error'] = 'Erreur de connexion: ' . $e->getMessage();
        }

        return $result;
    }

    private function getMimeType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        return match($extension) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'tiff', 'tif' => 'image/tiff',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }

    public function isImageFile(string $filePath): bool
    {
        $mimeType = $this->getMimeType($filePath);
        return in_array($mimeType, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/tiff',
            'image/webp',
        ]);
    }

    public function isPdfFile(string $filePath): bool
    {
        return $this->getMimeType($filePath) === 'application/pdf';
    }
}
