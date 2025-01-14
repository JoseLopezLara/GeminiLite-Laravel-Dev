<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\UploadFileToGemini;
use Illuminate\Support\Facades\Log;
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

    public function testGeminiPromptNutritionSumary()
    {
        try {
            // OBJETIVE OF THIS TEST:

            $responseSchema = [
                "responseSchema" => [
                    "type" => "object",
                    "description" => "This JSON object represents the structured nutritional information response provided by Gemini after analyzing an image of a food dish, appetizer, beverage, or general food item.",
                    "properties" => [
                        "status" => [
                            "type" => "string",
                            "description" => "Indicates 'success' if the provided image corresponds to a food item or beverage with sufficient quality for nutritional analysis. Otherwise, returns 'error' if the image lacks quality or does not correspond to a recognizable food item or beverage.",
                            "enum" => [
                                "success",
                                "error"
                            ]
                        ],
                        "status_code" => [
                            "type" => "string",
                            "description" => "Indicates the response status code: '200' if the image represents a food item or beverage with sufficient quality for analysis; '422' if the image represents a food item or beverage but lacks sufficient quality; and '400' if the image does not appear to correspond to any food item or beverage.",
                            "enum" => [
                                "200",
                                "422",
                                "400"
                            ]
                        ],
                        "message" => [
                            "type" => "string",
                            "description" => "Provides a message corresponding to the response: 'The food dish was successfully registered' if the image represents a food item or beverage with adequate quality; 'The image quality is too low to generate an accurate nutritional report' if quality is insufficient; and 'The provided image does not appear to correspond to a food dish' if no food item or beverage is identified.",
                            "enum" => [
                                "The food dish was successfully registered",
                                "The image quality is too low to generate an accurate nutritional report",
                                "The provided image does not appear to correspond to a food dish"
                            ]
                        ],
                        "data" => [
                            "type" => "object",
                            "description" => "Contains nutritional information data. This object is required but should remain empty if the status is not 200.",
                            "properties" => [
                                "food_name" => [
                                    "type" => "string",
                                    "description" => "If the status is 200, this field provides the name of the analyzed food item or beverage."
                                ],
                                "ingredients" => [
                                    "type" => "array",
                                    "description" => "An array of ingredient objects, each containing the name and weight of individual ingredients present in the analyzed food item or beverage. This array is only populated if the status code is 200.",
                                    "items" => [
                                        "type" => "object",
                                        "description" => "An object representing a single ingredient with its name and estimated weight in grams, only provided if the status code is 200.",
                                        "properties" => [
                                            "ingredient_name" => [
                                                "type" => "string",
                                                "description" => "The name of the ingredient as identified from the image analysis, included only if the status code is 200."
                                            ],
                                            "ingrediente_weight_grams" => [
                                                "type" => "number",
                                                "description" => "The estimated weight of the ingredient in grams, based on visual analysis and context from the image, provided only if the status code is 200."
                                            ]
                                        ],
                                        "required" => [
                                            "ingredient_name",
                                            "ingrediente_weight_grams"
                                        ]
                                    ]
                                ],
                                "nutritional_information" => [
                                    "type" => "object",
                                    "description" => "If the status is 200, this object provides the estimated nutritional information for the analyzed food item or beverage.",
                                    "properties" => [
                                        "calories" => [
                                            "type" => "integer",
                                            "description" => "If the status is 200, this field represents the estimated total calorie count. When exact measurements are challenging due to unknowns (e.g., cooking method), provide a close approximation. Total calories are the sum of estimated portions for each ingredient, utilizing contextual cues (such as plating or packaging) to enhance portion accuracy."
                                        ],
                                        "carbohydrates" => [
                                            "type" => "number",
                                            "description" => "If the status is 200, this field represents the estimated carbohydrate content. Use a close approximation when specifics (e.g., cooking method) are unknown. This is the sum of carbohydrate content across ingredients, approximated based on context (such as plating or surrounding objects)."
                                        ],
                                        "proteins" => [
                                            "type" => "number",
                                            "description" => "If the status is 200, this field represents the estimated protein content. If precise values are unavailable, approximate based on visible factors. The value is the sum of protein content across ingredients, estimated using visual cues and context."
                                        ],
                                        "fats" => [
                                            "type" => "number",
                                            "description" => "If the status is 200, this field represents the estimated fat content. Provide a close estimate if exact fat amounts are unavailable. The value is the sum of fat content across ingredients, approximated using context such as plating."
                                        ],
                                        "saturated_fats" => [
                                            "type" => "number",
                                            "description" => "If the status is 200, this field represents the estimated saturated fat content. Use visual cues to approximate, as needed. This is the sum of saturated fats across ingredients, estimated based on context."
                                        ],
                                        "dietary_fiber" => [
                                            "type" => "number",
                                            "description" => "If the status is 200, this field represents the estimated dietary fiber content. Provide an approximate value based on visual indicators and contextual cues. This is the sum of dietary fiber content across ingredients, estimated as closely as possible."
                                        ],
                                        "sugars" => [
                                            "type" => "number",
                                            "description" => "If the status is 200, this field represents the estimated sugar content. Approximations should use visual cues when specifics are unavailable. The value is the sum of sugars across ingredients, estimated based on context."
                                        ]
                                    ],
                                    "required" => [
                                        "calories",
                                        "carbohydrates",
                                        "proteins",
                                        "fats",
                                        "saturated_fats",
                                        "dietary_fiber",
                                        "sugars"
                                    ]
                                ]
                            ]
                        ]
                    ],
                    "required" => [
                        "status",
                        "status_code",
                        "message",
                        "data"
                    ]
                ]
            ];

            $prompt = "Analyze the provided image to extract nutritional information. Follow these guidelines closely:
Image Validation: If the image shows a recognizable food dish, appetizer, beverage, or general food item, proceed with nutritional analysis. If the image does not correspond to any food or beverage, return an error message as specified in the response structure.
Quality Requirements: If the image quality is too low to allow a reliable nutritional assessment, return an error indicating insufficient image quality.
Nutritional Information Extraction: Always provide nutritional estimates when analyzing food or beverages, even when details are uncertain. Use the closest possible approximations based on the image, and rely on averages where specific information is missing. For complex dishes or when details such as cooking method are unknown (e.g., if a dish contains chicken but its preparation style is unclear), generalize by using a nutritional average for common cooking methods and select the highest value in each generalization to ensure a comprehensive estimate. Ensure all primary nutritional information, such as calories, carbohydrates, proteins, and fats, is included based on visual cues.
Ingredient Portion Estimation: Estimate the portion size or weight of each visible ingredient within the dish or beverage to determine its nutritional impact. Base portion sizes on visual references, such as plating, surrounding items, or packaging in the image.
Always return a nutritional profile for food or beverages, adhering to the response structure provided. If the image does not correspond to a food item or beverage, or if the quality is insufficient, return an error status with the appropriate message and code as defined. GENERATE OUTPUT IN JSON";

            //TEST WITH A IMAGE PROMPT AND TEST HISTORY CONTEXT.
            $testImagePath = storage_path('app/public/test_image_food.jpeg');

            if (!file_exists($testImagePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Test image not found',
                ], 404);
            }

            $uploadFileToGeminiResult = UploadFileToGemini::processFileFromPath($testImagePath);
            $uri = $uploadFileToGeminiResult->getUri();
            $mimeType = $uploadFileToGeminiResult->getMimeType();


            Log::info('-------------Init chat-----------------');
            $geminiChat1 = Gemini::newChat();

            Log::info('-------------Change config to use JSON MODE-----------------');
            $geminiChat1->setGeminiModelConfig(1, 40, 0.95, 8192, 'application/json', $responseSchema);
            $geminiChat1->setGeminiModelConfig(1, 64, 0.95, 8192, 'application/json', $responseSchema);

            Log::info('-------------First prompt-----------------');
            $response1 = $geminiChat1->newPrompt($prompt, $uri, $mimeType);

            Log::info('-------------DEBUG-----------------');
            Log::info($response1);
            Log::info('-------------DEBUG-----------------');

            Log::info('-------------DEBUG-----------------');
            $response1_decode_to_object = json_decode($response1);
            Log::info($response1_decode_to_object->status);
            Log::info('-------------DEBUG-----------------');

            // Log::info('-------------DEBUG-----------------');
            // $response1_decode_array = json_decode($response1, true);
            // Log::info($response1_decode_array['recipes'][0]['recipe_name']);
            // Log::info('-------------DEBUG-----------------');

            // Log::info('-------------DEBUG-----------------');
            // $response1_decode_object = json_decode($response1);
            // Log::info($response1_decode_object->recipes[0]->recipe_name);
            // Log::info('-------------DEBUG-----------------');

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'response' => $response1_decode_to_object->status
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

    public function testGeminiChatHistory()
    {
        try {
            $gemini = Gemini::newChat();
            $response1 = $gemini->newPrompt('Hola');
            $response2 = $gemini->newPrompt('¿Cómo estás?');
            $history = $gemini->getHistory();

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'history' => $history,
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
