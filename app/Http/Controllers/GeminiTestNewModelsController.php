<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class GeminiTestNewModelsController extends Controller
{
    /**
     * Test endpoint for GEMINI_FLASH_V2_0_EXP model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGeminiFlashV2Exp()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('GEMINI_FLASH_V2_0_EXP');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('What is the capital of France?');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_FLASH_V2_0_EXP successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_FLASH_V2_0_EXP failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test endpoint for GEMINI_EXP_1206 model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGeminiExp1206()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('GEMINI_EXP_1206');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('What is the largest planet in our solar system?');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_EXP_1206 successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_EXP_1206 failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test endpoint for LEARNLM_1_5_PRO_EXP model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testLearnLMProExp()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('LEARNLM_1_5_PRO_EXP');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('What is the meaning of life?');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for LEARNLM_1_5_PRO_EXP successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for LEARNLM_1_5_PRO_EXP failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test endpoint for GEMINI_FLASH_V2_0_THINKING_EXP model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGeminiFlashV2ThinkingExp()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('GEMINI_FLASH_V2_0_THINKING_EXP');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('What is the speed of light?');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_FLASH_V2_0_THINKING_EXP successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_FLASH_V2_0_THINKING_EXP failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
