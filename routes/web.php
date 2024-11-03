<?php

use App\Http\Controllers\GeminiTestController;
use App\Http\Controllers\UploadFileToGeminiTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//Upload File to Gemini
Route::get('/uploadFileToGeminiTest', [UploadFileToGeminiTestController::class, 'getUploadFileToGeminiTest']);
Route::get('/test', [UploadFileToGeminiTestController::class, 'test']);

//Gemini
Route::get('/testGeminiPrompts', [GeminiTestController::class, 'testGeminiPrompts']);
Route::get('/testGeminiPromptsConfig', [GeminiTestController::class, 'testGeminiPromptsConfig']);
Route::get('/testGemini', [GeminiTestController::class, 'testGemini']);

