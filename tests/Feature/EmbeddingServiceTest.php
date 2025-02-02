<?php

namespace Tests\Feature;

use Tests\TestCase;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Embedding;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\EmbeddingService;

class EmbeddingServiceTest extends TestCase
{
    public function test_can_generate_single_embedding()
    {
        $text = "Hello world";
        $embedding = Embedding::embedText($text);

        $this->assertIsArray($embedding);
        $this->assertNotEmpty($embedding);
        $this->assertIsFloat($embedding[0]);
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

    public function test_empty_text_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $service = new EmbeddingService('test-api-key');
        $service->embedText('');
    }

    public function test_empty_batch_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $service = new EmbeddingService('test-api-key');
        $service->embedBatch([]);
    }
}