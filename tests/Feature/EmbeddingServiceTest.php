<?php

namespace Tests\Feature;

use Tests\TestCase;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Embedding;
use Illuminate\Support\Facades\Config;
use Illuminate\Testing\Fluent\AssertableJson;

class EmbeddingServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Asegurarse de que existe una API key de prueba
        Config::set('geminilite.geminilite_secret_api_key', env('GEMINILITE_SECRET_API_KEY'));
    }

    public function test_can_generate_single_embedding()
    {
        $text = "Hello world";
        $embedding = Embedding::embedText($text);

        $this->assertIsArray($embedding);
        $this->assertNotEmpty($embedding);
        $this->assertIsFloat($embedding[0]); // Verificar que los valores son números flotantes
    }

    public function test_can_generate_batch_embeddings()
    {
        $texts = [
            "Hello world",
            "How are you?",
            "Testing embeddings"
        ];

        $embeddings = Embedding::embedBatch($texts);

        $this->assertIsArray($embeddings);
        $this->assertCount(3, $embeddings);
        
        foreach ($embeddings as $embedding) {
            $this->assertIsArray($embedding);
            $this->assertNotEmpty($embedding);
            $this->assertIsFloat($embedding[0]);
        }
    }

    public function test_embeddings_integration_via_http()
    {
        $response = $this->postJson('/api/embedding/single', [
            'text' => 'Hello world'
        ]);

        $response->assertStatus(200)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('success')
                         ->has('embedding')
                         ->where('success', true)
                );
    }

    public function test_batch_embeddings_integration_via_http()
    {
        $response = $this->postJson('/api/embedding/batch', [
            'texts' => [
                'Hello world',
                'How are you?'
            ]
        ]);

        $response->assertStatus(200)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('success')
                         ->has('embeddings')
                         ->where('success', true)
                );
    }

    public function test_similarity_calculation_via_http()
    {
        $response = $this->postJson('/api/embedding/similarity', [
            'text1' => 'Hello world',
            'text2' => 'Hello there'
        ]);

        $response->assertStatus(200)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('success')
                         ->has('similarity')
                         ->has('embedding1')
                         ->has('embedding2')
                         ->where('success', true)
                );

        $data = $response->json();
        $this->assertIsFloat($data['similarity']);
        $this->assertGreaterThanOrEqual(-1, $data['similarity']);
        $this->assertLessThanOrEqual(1, $data['similarity']);
    }

    public function test_invalid_request_returns_validation_error()
    {
        $response = $this->postJson('/api/embedding/single', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['text']);
    }

    public function test_batch_request_with_invalid_data_returns_validation_error()
    {
        $response = $this->postJson('/api/embedding/batch', [
            'texts' => [''] // texto vacío
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['texts.0']);
    }
}