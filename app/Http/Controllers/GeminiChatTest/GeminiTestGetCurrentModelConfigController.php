<?php

namespace App\Http\Controllers\GeminiChatTest;

use App\Http\Controllers\Controller;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use Log;
use Illuminate\Http\Request;

class GeminiTestGetCurrentModelConfigController extends Controller
{
    public function testGetCurrentModelConfig()
    {
        try {
            Log::info('-------------Init chat-----------------');
            $geminiChat = Gemini::newChat();
            Log::info('-------------Change config-----------------');
            $initialModelConfig = $geminiChat->getGeminiModelConfig();
            $geminiChat->setGeminiModelConfig(2, 64, 1, 8192, 'text/plain');
            Log::info('-------------Afer change config-----------------');
            $finalModelConfig = $geminiChat->getGeminiModelConfig();

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'Before change model config' => $initialModelConfig,
                    'Afeter change model config' => $finalModelConfig
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
