<?php

use App\Http\Controllers\GeminiChatTest\GeminiTestBetweenModelController;
use App\Http\Controllers\GeminiChatTest\GeminiTestGetCurrentModelConfigController;
use App\Http\Controllers\GeminiTestController;
use App\Http\Controllers\GeminiTestNewModelsController;
use App\Http\Controllers\UploadFileToGeminiTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//Upload File to Gemini

Route::get('/testProcessFileFromPath', [UploadFileToGeminiTestController::class, 'testProcessFileFromPath']);
Route::get('/testProcessFileFromUpload', [UploadFileToGeminiTestController::class, 'testProcessFileFromUpload']);
Route::get('/test', [UploadFileToGeminiTestController::class, 'test']);

//Gemini
Route::get('/testGeminiPrompts', [GeminiTestController::class, 'testGeminiPrompts']);
Route::get('/testGeminiPromptsConfig', [GeminiTestController::class, 'testGeminiPromptsConfig']);
Route::get('/testGeminiChangeBetweenModels', [GeminiTestBetweenModelController::class, 'testGeminiBetweenModel']);
Route::get('/testGeminiJSONMode', [GeminiTestController::class, 'testGeminiJSONMode']);
Route::get('/testGemini', [GeminiTestController::class, 'testGemini']);
Route::get('/testGeminiPromptNutritionSumary', [GeminiTestController::class, 'testGeminiPromptNutritionSumary']);
Route::get('/testGetCurrentModelConfig', [GeminiTestGetCurrentModelConfigController::class, 'testGetCurrentModelConfig']);

// New Gemini Models Test
Route::get('/testGeminiFlashV2Exp', [GeminiTestNewModelsController::class, 'testGeminiFlashV2Exp']);
Route::get('/testGeminiExp1206', [GeminiTestNewModelsController::class, 'testGeminiExp1206']);
Route::get('/testLearnLMProExp', [GeminiTestNewModelsController::class, 'testLearnLMProExp']);
Route::get('/testGeminiFlashV2ThinkingExp', [GeminiTestNewModelsController::class, 'testGeminiFlashV2ThinkingExp']);

// Gemini Validations Test
Route::get('/api/gemini/validation-test', [\App\Http\Controllers\GeminiValidationTestController::class, 'testValidations']);
