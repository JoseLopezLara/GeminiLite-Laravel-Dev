<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class GeminiValidationTestController extends Controller
{

    protected function validateModelData(array $data, array $rules)
    {
        return Validator::make($data, $rules);
    }

    public function testValidations(Request $request)
    {
        try {
            $models = [
                'gemini-2.0-flash-exp',
                'gemini-exp-1206',
                'gemini-2.0-flash-thinking-exp-01-21',
                'learnlm-1.5-pro-experimental',
                'gemini-1.5-pro',
                'gemini-1.5-flash',
                'gemini-1.5-flash-8b'
            ];
    
            $testCases = [
                'valid_values' => [
                    'prompt' => 'Test prompt',
                    'max_tokens' => 100,
                    'temperature' => 0.5,
                    'top_p' => 0.8,
                    'top_k' => 20,
                ],
                'invalid_top_k' => [
                    'prompt' => 'Test prompt',
                    'max_tokens' => 100,
                    'temperature' => 0.5,
                    'top_p' => 0.8,
                    'top_k' => 50, // Invalid topK
                ],
                'invalid_top_p' => [
                    'prompt' => 'Test prompt',
                    'max_tokens' => 100,
                    'temperature' => 0.5,
                    'top_p' => 1.5, // Invalid topP
                    'top_k' => 20,
                ],
                'missing_required_field' => [
                    'max_tokens' => 100,
                    'temperature' => 0.5,
                    'top_p' => 0.8,
                    'top_k' => 20,
                ],
                 'null_top_k' => [
                    'prompt' => 'Test prompt',
                    'max_tokens' => 100,
                    'temperature' => 0.5,
                    'top_p' => 0.8,
                    'top_k' => null,
                ],
                'null_top_p' => [
                    'prompt' => 'Test prompt',
                    'max_tokens' => 100,
                    'temperature' => 0.5,
                    'top_p' => null,
                    'top_k' => 20,
                ],
                'no_top_k' => [
                    'prompt' => 'Test prompt',
                    'max_tokens' => 100,
                    'temperature' => 0.5,
                    'top_p' => 0.8,
                ],
                'invalid_temperature' => [
                    'prompt' => 'Test prompt',
                    'max_tokens' => 100,
                    'temperature' => 3.0, // Invalid temperature
                    'top_p' => 0.8,
                    'top_k' => 20,
                ],
            ];
    
            $results = [];
    
            foreach ($models as $model) {
                $modelResults = [];
                Log::info('-------------Init chat-----------------');
                $geminiChat = Gemini::newChat();
                Log::info('-------------Change config-----------------');
                try {
                    $geminiChat->changeGeminiModel($model);
                    foreach ($testCases as $key => $data) {
                        try {
                            $rules = [
                                'prompt' => 'required|string',
                                'max_tokens' => 'nullable|integer|min:1',
                                'temperature' => 'nullable|numeric|between:0,2',
                                'top_p' => 'nullable|numeric|between:0,1',
                                'top_k' => 'nullable|integer|min:1',
                            ];
                            $validatedData = $this->validateModelData($data, $rules);
                            if ($validatedData->fails()) {
                                $modelResults[$key] = ['status' => 'failed', 'errors' => $validatedData->errors()];
                            } else {
                                try {
                                    $geminiChat->setGeminiModelConfig(
                                        $data['temperature'] ?? 1,
                                        (in_array($key, ['no_top_k', 'null_top_k']) ? null : $data['top_k'] ?? 40) ,
                                        $data['top_p'] ?? 0.95,
                                        $data['max_tokens'] ?? 8192,
                                        'text/plain',
                                        null,
                                        $model
                                    );
                                    $response = $geminiChat->newPrompt($data['prompt'] ?? 'test');
                                    $modelResults[$key] = ['status' => 'success', 'message' => 'Validations passed', 'response' => $response];
                                } catch (\Exception $e) {
                                    $modelResults[$key] = ['status' => 'failed', 'error' => $e->getMessage()];
                                }
                            }
                        } catch (\InvalidArgumentException $e) {
                            $modelResults[$key] = ['status' => 'failed', 'error' => $e->getMessage()];
                        }
                    }
                } catch (\InvalidArgumentException $e) {
                    $modelResults['invalid_top_k'] = ['status' => 'failed', 'error' => $e->getMessage()];
                    $modelResults['null_top_k'] = ['status' => 'failed', 'error' => $e->getMessage()];
                    $modelResults['valid_values'] = ['status' => 'failed', 'error' => $e->getMessage()];
                    $modelResults['null_top_p'] =  ['status' => 'failed', 'error' => $e->getMessage()];
                    $modelResults['no_top_k'] = ['status' => 'failed', 'errors' =>$e->getMessage()];
                    $modelResults['invalid_top_p'] =  ['status' => 'failed', 'errors' =>$e->getMessage()];
                    $modelResults['missing_required_field'] = ['status' => 'failed', 'errors' =>$e->getMessage()];
                }

                $results[$model] = $modelResults;
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => $results
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