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
            Log::info('-------------Change config-----------------');
            $geminiChat1->setGeminiModelConfig(1, 40, 0.95, 8192, 'text/plain');
            Log::info('-------------First prompt-----------------');
            $response1 = $geminiChat1->newPrompt("Describe cómo sería la vida diaria en una ciudad futurista en el año 2150 en 80 palabras");

            Log::info('-------------Init chat-----------------');
            $geminiChat2 = Gemini::newChat();
            Log::info('-------------Change config-----------------');
            $geminiChat2->setGeminiModelConfig(0, 40, 0, 8192, 'text/plain');
            Log::info('-------------First prompt-----------------');
            $response2 = $geminiChat2->newPrompt("Describe cómo sería la vida diaria en una ciudad futurista en el año 2150 en 80 palabras");

            Log::info('-------------Init chat-----------------');
            $geminiChat3 = Gemini::newChat();
            Log::info('-------------Change config-----------------');
            $geminiChat3->setGeminiModelConfig(2, 64, 1, 8192, 'text/plain');
            Log::info('-------------First prompt-----------------');
            $response3 = $geminiChat3->newPrompt("Describe cómo sería la vida diaria en una ciudad futurista en el año 2150 en 80 palabras");

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Test successful',
            //     'data' => [
            //         'response with: temperature 1 and topP 0.95' => $response1
            //     ]
            // ], 200);

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'response with: temperature 1 and topP 0.95' => $response1,
                    'response with: temperature 0 and topP 0' => $response2,
                    'response with: temperature 2 and topP 1' => $response3
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

    public function testGeminiJSONMode()
    {
        try {
            // OBJETIVE OF THIS TEST:
            // Thist test has to verify that you can change the config of the Gemini in "execute time",
            // in other words, you can modify config parameters with out need to create a new instace of Gemini.
            // You can change this config parameters and keep your history chat.
            $responseSchema = [
                "responseSchema" => [
                    "type" => "object",
                    "description" => "Return some of the most popular cookie recipes",
                    "properties" => [
                        "recipes" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "recipe_name" => [
                                        "type" => "string",
                                        "description" => "name of recipe using upper case"
                                    ],
                                    "ingredients_number" => [
                                        "type" => "number"
                                    ]
                                ],
                                "required" => [
                                    "recipe_name",
                                    "ingredients_number"
                                ]
                            ]
                        ],
                        "status_response" => [
                            "type" => "array",
                            "items" => [
                                "type" => "object",
                                "properties" => [
                                    "sucess" => [
                                        "type" => "string",
                                        "description" => "Short message in uppercase about request"
                                    ],
                                    "code" => [
                                        "type" => "string",
                                        "description" => "Status code",
                                        "enum" => [
                                            "200",
                                            "400"
                                        ]
                                    ]
                                ],
                                "required" => [
                                    "sucess",
                                    "code"
                                ]
                            ]
                        ]
                    ],
                    "required" => [
                        "recipes",
                        "status_response"
                    ]
                ]
            ];

            Log::info('-------------Init chat-----------------');
            $geminiChat1 = Gemini::newChat();
            Log::info('-------------Change config has to gemini response in "application/json" instead "text/plain"-----------------');
            $geminiChat1->setGeminiModelConfig(
                temperature: 1,
                topK: 40,
                topP: 0.95,
                maxOutputTokens: 8192,
                responseMimeType: 'application/json',
                responseSchema: $responseSchema);
            Log::info('-------------First prompt-----------------');
            $response1 = $geminiChat1->newPrompt(
                textPrompt: "Generate a list of cookie recipes. Make the outputs in JSON format.",
            );

            $response1 = $geminiChat1->newPrompt(
                textPrompt: "Generate a list of cookie recipes. Make the outputs in JSON format.",
            );

            Log::info('-------------DEBUG-----------------');
            Log::info($response1);
            Log::info('-------------DEBUG-----------------');

            Log::info('-------------DEBUG-----------------');
            $response1_decode_to_object = json_decode($response1);
            Log::info($response1_decode_to_object->recipes);
            Log::info('-------------DEBUG-----------------');

            Log::info('-------------DEBUG-----------------');
            $response1_decode_array = json_decode($response1, true);
            Log::info($response1_decode_array['recipes'][0]['recipe_name']);
            Log::info('-------------DEBUG-----------------');

            Log::info('-------------DEBUG-----------------');
            $response1_decode_object = json_decode($response1);
            Log::info($response1_decode_object->recipes[0]->recipe_name);
            Log::info('-------------DEBUG-----------------');



            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'recipes' => $response1_decode_to_object->recipes,
                    'get_first_recipe_name_form_array_decode' => $response1_decode_array['recipes'][0]['recipe_name'],
                    'get_first_recipe_name_form_object_decode' => $response1_decode_object->recipes[0]->recipe_name
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
