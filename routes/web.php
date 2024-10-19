<?php

use App\Http\Controllers\UploadFileToGeminiTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/uploadFileToGeminiTest', [UploadFileToGeminiTestController::class, 'getUploadFileToGeminiTest']);
Route::get('/test', [UploadFileToGeminiTestController::class, 'test']);
