<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\UploadFileToGemini;
use Log;
use Symfony\Component\HttpKernel\Log\Logger;

class GeminiTestController extends Controller
{
    // - - - - - - - - - - Properties - - - - - - - - - -

    public function testGemini(){
       return response()->json([
        'message' => 'This is a test controller for Gemini $textPrompt']);
    }

    public function testGeminiPrompts()
    {
        try {
            //TEST WITH A IMAGE PROMPT AND TEST HISTORY CONTEXT.
            $testImagePath = storage_path('app/public/test_image.jpeg');

            if (!file_exists($testImagePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Test image not found',
                ], 404);
            }

            $uploadFileToGeminiResult = UploadFileToGemini::processFileFromPath($testImagePath);
            $uri = $uploadFileToGeminiResult->getUri();
            $mimeType = $uploadFileToGeminiResult->getMimeType();

            $gemini = Gemini::newChat();
            $response = $gemini->newPrompt("Que vez en la imagen", $uri, $mimeType);
            $responseAboutInitialPrompt = $gemini->newPrompt('Dame el codigo que hay en la imagen');

            // TEST WITH MULTIPLE SHORT PROMPTS.
            // What is my hypothesis? This test will return status code 200, because the prompts are simples and the time to get result is fast.
            // $gemini = Gemini::newChat();
            // $response = $gemini->newPrompt('¿Cuanto es 1 + 1? Unicamente responde con el valor del resultado');
            // $responseAboutInitialPrompt = $gemini->newPrompt('Al resultado anterior sumale 8 ¿Caul es el nuevo resultado? Unicamente responde con el valor del resultado');
            // $responseAboutSecondPrompt = $gemini->newPrompt('Al resultado anterior restale 9 ¿Caul es el nuevo resultado? Unicamente responde con el valor del resultado');
            // $responseAboutThirdPrompt = $gemini->newPrompt('Al resultado anterior sumale 999 ¿Caul es el nuevo resultado? Unicamente responde con el valor del resultado');

            // TEST WITH MULTIPLE LARGE PROMPTS.
            // What is my hypothesis? This test will return status code 500, the reason is that, I'm doing
            // multiple prompts and the response of bere prompt doesn't ready the do the next prompt.
            // $gemini = Gemini::newChat();
            // $response = $gemini->newPrompt('Genera una historia fantasiosa donde el personaje principal es un conejo punk. El hoobie de este conejo punk es aportar en proyecto open source pero un dia descubrio un backdoor en el proyecto de react... Continua con la historia. la historia no debe de ser mayor a 120 palabras y debe ser dirigida a un publico adulto (Usa humor negro)');
            // $responseAboutInitialPrompt = $gemini->newPrompt('¿Caules son las caracteristica del personaje principal?');
            // $responseAboutSecondPrompt = $gemini->newPrompt('Añade mas historia, hazla muy grande');
            // $responseAboutThirdPrompt = $gemini->newPrompt('¿Que fue lo nuevo?');

            //TODO: EST WITH MULTIPLE LARGE PROMPTS BUD ADDING TIME BETWEEN PROMPTS TO GET RESULT.
            // $gemini = Gemini::newChat();
            // $response = $gemini->newPrompt('Genera una historia fantasiosa donde el personaje principal es un conejo punk. El hoobie de este conejo punk es aportar en proyecto open source pero un dia descubrio un backdoor en el proyecto de react... Continua con la historia. la historia no debe de ser mayor a 120 palabras y debe ser dirigida a un publico adulto (Usa humor negro)');
            // $responseAboutInitialPrompt = $gemini->newPrompt('¿Caules son las caracteristica del personaje principal?');
            // $responseAboutSecondPrompt = $gemini->newPrompt('Añade mas historia, hazla muy grande');
            // $responseAboutThirdPrompt = $gemini->newPrompt('¿Que fue lo nuevo?');

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'response' => $response,
                    'responseAboutInitialPrompt' => $responseAboutInitialPrompt
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

    public function testGeminiPromptsConfig()
    {
        try {
            // OBJETIVE OF THIS TEST:
            // Thist test has to verify that you can change the config of the Gemini in "execute time",
            // in other words, you can modify config parameters with out need to create a new instace of Gemini.
            // You can change this config parameters and keep your history chat.

            Log::info('-------------Init chat-----------------');
            $geminiChat1 = Gemini::newChat();
            Log::info('-------------First prompt-----------------');
            $response1 = $geminiChat1->newPrompt("Describe cómo sería la vida diaria en una ciudad futurista en el año 2150 en 80 palabras");
            Log::info('-------------Change config-----------------');
            Log::info('-------------BEFORE CHANGE-----------------: ' . spl_object_id($geminiChat1));


            Gemini::setGeminiModelConfig(0, 40, 0, 8192, 'text/plain', $geminiChat1);
            Log::info('-------------Second prompt-----------------');
            $response2 = $geminiChat1->newPrompt("Describe cómo sería la vida diaria en una ciudad futurista en el año 2150 en 80 palabras");

            // $geminiChat2 = Gemini::newChat();
            // Gemini::setGeminiModelConfig(2, 40, 1, 8192, 'text/plain', $geminiChat2);
            // $response2 = $geminiChat2->newPrompt("Describe cómo sería la vida diaria en una ciudad futurista en el año 2150 en 80 palabras");

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'response with: temperature 0 and topP 0' => $response1,
                    'response with: temperature 2 and topP 1' => $response2
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
