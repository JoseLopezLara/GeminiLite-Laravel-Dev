<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\UploadFileToGemini;

class GeminiTestController extends Controller
{
    // - - - - - - - - - - Properties - - - - - - - - - -

    public function testGemini(){
       return response()->json([
        'message' => 'This is a test controller for Gemini $textPrompt']);
    }

    public function geminiOnlyOnePrompt()
    {
        try {
            // Use a test image path en storage/app/public/test_image.jpeg

            // Use Facades\Gemini to do a text prompt and get the response
            $gemini = Gemini::gemini();
            $response = $gemini->newPrompt('Genera una historia fantasiosa donde el personaje principal
                                es un conejo punk. El hoobie de este conejo punk es aportar
                                en proyecto open source pero un dia descubrio un backdoor en
                                el proyecto de react... Continua con la historia. la historia
                                no debe de ser mayor a 120 palabras y debe ser dirigida a un
                                publico adulto (Usa humor negro)');

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'response' => $response,
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
