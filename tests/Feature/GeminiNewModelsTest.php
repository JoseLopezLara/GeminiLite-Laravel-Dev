<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GeminiNewModelsTest extends TestCase
{
    /**
     * @group lightRequest
     */
    public function test_GeminiFlash(): void
    {
        $response = $this->getJson(route('get.GeminiFlashV2Exp'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_Exp1206(): void
    {
        $response = $this->getJson(route('GeminiExp1206'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_lmproExp(): void
    {
        $response = $this->getJson(route('LearnLMPProExp'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_GeminiFlashV2ThinkingExp(): void
    {
        $response = $this->getJson(route('GeminiFlashV2ThinkingExp'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_Gemini20Flash(): void
    {
        $response = $this->getJson(route('Gemini20Flash'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_GeminiV2FlashLitePreview(): void
    {
        $response = $this->getJson(route('GeminiV2FlashLitePreview'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    /**
     * @group lightRequest
     */
    public function test_Gemini20ProExp(): void
    {
        $response = $this->getJson(route('Gemini20ProExp'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
}
