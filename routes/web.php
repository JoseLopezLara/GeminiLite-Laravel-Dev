<?php

use App\Http\Controllers\GeminiChatTest\GeminiTestBetweenModelController;
use App\Http\Controllers\GeminiTestController;
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

