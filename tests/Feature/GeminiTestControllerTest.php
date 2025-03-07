<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GeminiTestControllerTest extends TestCase
{
    /**
     * @group lightRequest
     */
    public function test_GemniPrompts(): void
    {
        $response = $this->getJson(route('GeminiPrompts'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_PromptsConfig(): void
    {
        $response = $this->getJson(route('GeminiPromptsConfig'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_ChangeBetweenn_models(): void
    {
        $response = $this->getJson(route('GeminiBetweenModel'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_JSON_Mode(): void
    {
        $response = $this->getJson(route('GeminiJSONMode'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_Gemini(): void
    {
        $response = $this->getJson(route('testGemini'));

        $response->assertStatus(200);
    }
    /**
     * @group lightRequest
     */
    public function test_Prompt_Nutrition_Summary(): void
    {
        $response = $this->getJson(route('GeminiPromptNutritionSumary'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_current_model(): void
    {
        $response = $this->getJson(route('CurrentModel'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_chat_history(): void
    {
        $response = $this->getJson(route('GeminiHistory'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
}
