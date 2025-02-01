<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UploadFileToGeminiTest extends TestCase
{
    public function test_Process_file_from_path(): void
    {
        $response = $this->getJson(route('ProcessFileFromPath'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    public function test_process_file_from_upload(): void
    {
        $response = $this->getJson(route('ProcesssFileFromUpload'));

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
    public function test_process_file(): void
    {
        $response = $this->getJson(route('test'));

        $response->assertStatus(200);
    }
}
