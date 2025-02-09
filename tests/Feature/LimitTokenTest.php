<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LimitTokenTest extends TestCase
{
    /**
     * @group lightRequest
     */
    public function test_Token_Counter(): void
    {
        $response = $this->getJson(route('testTokenCounter'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_Can_make_request(): void
    {
        $response = $this->getJson(route('testCanMakeRequest'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_is_active(): void
    {
        $response = $this->getJson(route('testIsActive'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_Gemini_usage(): void
    {
        $response = $this->getJson(route('testGeminiUsage'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_Assign_role(): void
    {
        $response = $this->getJson(route('testAssignRole'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_Limits(): void
    {
        $response = $this->getJson(route('testLimits'));

        $response->assertStatus(403)->assertJson(['success' => true]);
    }
    /**
     * @group lightRequest
     */
    public function test_Log(): void
    {
        $response = $this->getJson(route('testLog'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
}
