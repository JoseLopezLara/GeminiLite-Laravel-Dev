<?php

use App\Http\Controllers\GeminiChatTest\GeminiTestBetweenModelController;
use App\Http\Controllers\GeminiChatTest\GeminiTestGetCurrentModelConfigController;
use App\Http\Controllers\GeminiTestController;
use App\Http\Controllers\GeminiTestNewModelsController;
use App\Http\Controllers\TokenLimitController;
use App\Http\Controllers\UploadFileToGeminiTestController;
use App\Http\Controllers\EmbeddingTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})/*  */;
//Upload File to Gemini

Route::get('/testProcessFileFromPath', [UploadFileToGeminiTestController::class, 'testProcessFileFromPath'])->name('ProcessFileFromPath');
Route::get('/testProcessFileFromUpload', [UploadFileToGeminiTestController::class, 'testProcessFileFromUpload'])->name('ProcesssFileFromUpload');
Route::get('/test', [UploadFileToGeminiTestController::class, 'test'])->name('test');

//Gemini
Route::get('/testGeminiPrompts', [GeminiTestController::class, 'testGeminiPrompts'])->name('GeminiPrompts');
Route::get('/testGeminiRol', [GeminiTestController::class, 'testLimitTokens'])->name('rol');
Route::get('/testGeminiPromptsConfig', [GeminiTestController::class, 'testGeminiPromptsConfig'])->name('GeminiPromptsConfig');
Route::get('/testGeminiChangeBetweenModels', [GeminiTestBetweenModelController::class, 'testGeminiBetweenModel'])->name('GeminiBetweenModel');
Route::get('/testGeminiJSONMode', [GeminiTestController::class, 'testGeminiJSONMode'])->name('GeminiJSONMode');
Route::get('/testGemini', [GeminiTestController::class, 'testGemini'])->name('testGemini');
Route::get('/testGeminiPromptNutritionSumary', [GeminiTestController::class, 'testGeminiPromptNutritionSumary'])->name('GeminiPromptNutritionSumary');
Route::get('/testGetCurrentModelConfig', [GeminiTestGetCurrentModelConfigController::class, 'testGetCurrentModelConfig'])->name('CurrentModel');
Route::get('/testGeminiChatHistory', [GeminiTestController::class, 'testGeminiChatHistory'])->name('GeminiHistory');

// New Gemini Models Test
Route::get('/testGeminiFlashV2Exp', [GeminiTestNewModelsController::class, 'testGeminiFlashV2Exp'])->name('get.GeminiFlashV2Exp');
Route::get('/testLearnLMProExp', [GeminiTestNewModelsController::class, 'testLearnLMProExp'])->name('LearnLMPProExp');
Route::get('/testGeminiFlashV2ThinkingExp', [GeminiTestNewModelsController::class, 'testGeminiFlashV2ThinkingExp'])->name('GeminiFlashV2ThinkingExp');
Route::get('/testGemini20Flash', [GeminiTestNewModelsController::class, 'testGemini20Flash'])->name('Gemini20Flash');
Route::get('/testGeminiV2FlashLitePreview', [GeminiTestNewModelsController::class, 'testGeminiV2FlashLitePreview'])->name('GeminiV2FlashLitePreview');
Route::get('/testGeminiV2FlashLite', [GeminiTestNewModelsController::class, 'testGeminiV2FlashLite'])->name('GeminiV2FlashLite');
Route::get('/testGeminiFlashV2ExpImageGeneration', [GeminiTestNewModelsController::class, 'testGeminiFlashV2ExpImageGeneration'])->name('GeminiFlashV2ExpImageGeneration');
Route::get('/testGemini25ProPreview', [GeminiTestNewModelsController::class, 'testGemini25ProPreview'])->name('Gemini25ProPreview');

// Gemini Validations Test
Route::get('/api/gemini/validation-test', [\App\Http\Controllers\GeminiValidationTestController::class, 'testValidations'])->name('get.validLimits');

//Token Limit
Route::get('/testTokenCount', [TokenLimitController::class, 'tokenCounter'])->name('testTokenCounter');
Route::get('/testCanMakeRequest', [TokenLimitController::class, 'canMakeRequestT'])->name('testCanMakeRequest');
Route::get('/testIsActive', [TokenLimitController::class, 'isActive'])->name('testIsActive');
Route::get('/testGeminiUsage', [TokenLimitController::class, 'updateUsage'])->name('testGeminiUsage');
Route::get('/testAssignRole', [TokenLimitController::class, 'assigRoles'])->name('testAssignRole');
Route::get('/testLimits', [TokenLimitController::class, 'limit'])->name('testLimits');
Route::get('/testLog', [TokenLimitController::class, 'logs'])->name('testLog');
// Embedding Test Routes
Route::prefix('api/embedding')->group(function () {
    Route::post('/single', [EmbeddingTestController::class, 'testSingleEmbedding']);
    Route::post('/batch', [EmbeddingTestController::class, 'testBatchEmbedding']);
    Route::post('/similarity', [EmbeddingTestController::class, 'testSimilarity']);
});
