<?php

namespace App\Http\Controllers\GeminiChatTest;

use App\Http\Controllers\Controller;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use Log;
use Illuminate\Http\Request;

class GeminiTestBetweenModelController extends Controller
{
    public function testGeminiBetweenModel()
    {
        try {
            // OBJETIVE OF THIS TEST:
            Log::info('-------------Init chat-----------------');
            $geminiChat1 = Gemini::newChat();
            Log::info('-------------Change config-----------------');
            $geminiChat1->changeGeminiModel("gemini-1.5-flash-8b");
            Log::info('-------------First prompt-----------------');
            $response1 = $geminiChat1->newPrompt("Tres amigos —Ana, Bruno y Carlos— están sentados en una fila. Ana está a la izquierda de Bruno y a la derecha de Carlos. Si Carlos no está en el extremo derecho, ¿quién está en el medio y quién está en cada extremo?");

            Log::info('-------------Init chat-----------------');
            $geminiChat2 = Gemini::newChat();
            Log::info('-------------Change config-----------------');
            $geminiChat2->changeGeminiModel("gemini-1.5-pro-002");
            Log::info('-------------First prompt-----------------');
            $response2 = $geminiChat2->newPrompt("Tres amigos —Ana, Bruno y Carlos— están sentados en una fila. Ana está a la izquierda de Bruno y a la derecha de Carlos. Si Carlos no está en el extremo derecho, ¿quién está en el medio y quién está en cada extremo?");

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'response with: 8b' => $response1,
                    'response with: pro002' => $response2
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
