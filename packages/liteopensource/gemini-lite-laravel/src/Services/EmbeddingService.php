<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use Illuminate\Support\Facades\Http;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\EmbeddingServiceInterface;
use InvalidArgumentException;

class EmbeddingService implements EmbeddingServiceInterface
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
    protected string $model = 'models/text-embedding-004';

    public function __construct()
    {
        $this->apiKey = config('geminilite.geminilite_secret_api_key');
        
        if (empty($this->apiKey)) {
            throw new InvalidArgumentException('API key is required');
        }
    }

    public function embedText(string $text, array $options = []): array
    {
        $endpoint = "{$this->baseUrl}/{$this->model}:embedContent";
        
        $payload = [
            'model' => $this->model,
            'content' => [
                'parts' => [
                    ['text' => $text]
                ]
            ]
        ];

        // Agregar opciones si están presentes
        if (isset($options['taskType'])) {
            $payload['taskType'] = $options['taskType'];
        }
        if (isset($options['title'])) {
            $payload['title'] = $options['title'];
        }
        if (isset($options['outputDimensionality'])) {
            $payload['outputDimensionality'] = $options['outputDimensionality'];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$endpoint}?key={$this->apiKey}", $payload);

        if (!$response->successful()) {
            throw new \Exception('Error al generar embedding: ' . $response->body());
        }

        return $response->json()['embedding']['values'] ?? [];
    }

    public function embedBatch(array $texts, array $options = []): array
    {
        $endpoint = "{$this->baseUrl}/{$this->model}:batchEmbedContents";

        $requests = array_map(function($text) use ($options) {
            $request = [
                'model' => $this->model,
                'content' => [
                    'parts' => [
                        ['text' => $text]
                    ]
                ]
            ];

            if (isset($options['taskType'])) {
                $request['taskType'] = $options['taskType'];
            }
            if (isset($options['title'])) {
                $request['title'] = $options['title'];
            }
            if (isset($options['outputDimensionality'])) {
                $request['outputDimensionality'] = $options['outputDimensionality'];
            }

            return $request;
        }, $texts);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$endpoint}?key={$this->apiKey}", ['requests' => $requests]);

        if (!$response->successful()) {
            throw new \Exception('Error al generar embeddings por lotes: ' . $response->body());
        }

        return array_map(function($embedding) {
            return $embedding['values'] ?? [];
        }, $response->json()['embeddings'] ?? []);
    }
}