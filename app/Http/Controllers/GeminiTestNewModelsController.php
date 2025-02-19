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
            $gemini->changeGeminiModel('gemini-2.0-flash-exp');

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
            $gemini->changeGeminiModel('gemini-exp-1206');

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
            $gemini->changeGeminiModel('learnlm-1.5-pro-experimental');

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
            $gemini->changeGeminiModel('gemini-2.0-flash-thinking-exp-01-21');

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

    /**
     * Test endpoint for GEMINI_2_0_FLASH model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGemini20Flash()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('gemini-2.0-flash');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('What is the square root of 144?');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_2_0_FLASH successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_2_0_FLASH failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test endpoint for GEMINI_V2_FLASH_LITE_PREVIEW model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGeminiV2FlashLitePreview()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('gemini-v2-flash-lite-preview');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('What is the capital of Japan?');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_V2_FLASH_LITE_PREVIEW successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_V2_FLASH_LITE_PREVIEW failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test endpoint for GEMINI_2_0_PRO_EXP model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGemini20ProExp()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('gemini-2.0-pro-exp-02-05');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('What are some ways to improve code quality?');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_2_0_PRO_EXP successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_2_0_PRO_EXP failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
