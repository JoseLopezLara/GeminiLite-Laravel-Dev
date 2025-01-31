<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GeminiNewModelsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_GeminiFlash(): void
    {
        $response = $this->getJson(route('get.GeminiFlashV2Exp'));

        $response->assertStatus(200);
    }
    public function test_Exp1206(): void
    {
        $response = $this->getJson(route('GeminiExp1206'));

        $response->assertStatus(200);
    }
    public function test_lmproExp(): void
    {
        $response = $this->getJson(route('LearnLMPProExp'));

        $response->assertStatus(200);
    }
    public function test_GeminiFlashV2ThinkingExp(): void
    {
        $response = $this->getJson(route('GeminiFlashV2ThinkingExp'));

        $response->assertStatus(200);
    }
}
