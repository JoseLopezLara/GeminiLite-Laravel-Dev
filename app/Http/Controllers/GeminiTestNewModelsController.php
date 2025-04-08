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
            $gemini->changeGeminiModel('gemini-2.0-flash-lite-preview-02-05');

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
     * Test endpoint for GEMINI_V2_FLASH_LITE model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGeminiV2FlashLite()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('gemini-2.0-flash-lite');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('What is the boiling point of water?');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_V2_FLASH_LITE successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_V2_FLASH_LITE failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test endpoint for GEMINI_FLASH_V2_0_EXP_IMAGE_GENERATION model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGeminiFlashV2ExpImageGeneration()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('gemini-2.0-flash-exp-image-generation');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('Describe how to create a watercolor painting');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_FLASH_V2_0_EXP_IMAGE_GENERATION successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_FLASH_V2_0_EXP_IMAGE_GENERATION failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test endpoint for GEMINI_2_5_PRO_PREVIEW model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGemini25ProPreview()
    {
        try {
            // Inicializar Gemini chat con el modelo experimental en lugar del preview
            // ya que el modelo preview no tiene capa gratuita según el error 429
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('gemini-2.5-pro-exp-03-25');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('Explain quantum computing in simple terms');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_2_5_PRO_PREVIEW successful (using experimental model)',
                'data' => [
                    'response' => $response,
                    'note' => 'Using experimental model instead of preview model due to quota restrictions'
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_2_5_PRO_PREVIEW failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test endpoint for GEMINI_2_5_PRO_EXP model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testGemini25ProExp()
    {
        try {
            // Initialize Gemini chat with the specified model.
            $gemini = Gemini::newChat();
            $gemini->changeGeminiModel('gemini-2.5-pro-exp-03-25');

            // Send a simple prompt to test the model.
            $response = $gemini->newPrompt('Explain quantum computing in simple terms');

            // Return a successful response with the model's answer.
            return response()->json([
                'success' => true,
                'message' => 'Test for GEMINI_2_5_PRO_EXP successful',
                'data' => [
                    'response' => $response,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors that occur during the test.
            return response()->json([
                'success' => false,
                'message' => 'Test for GEMINI_2_5_PRO_EXP failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
