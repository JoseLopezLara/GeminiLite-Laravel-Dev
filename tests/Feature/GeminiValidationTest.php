<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GeminiValidationTest extends TestCase
{
    /**
     * This test is to check all gemini models
     * @group heavyRequest
     */
    public function test_example(): void
    {
        $response = $this->getJson(route('get.validLimits'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
}
