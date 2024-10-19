<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\UploadFileToGemini;

class UploadFileToGeminiTestController extends Controller
{
    public function test(){
       return response()->json([
        'message' => 'This is a test controller for uploading files to Gemini']);
    }

    public function getUploadFileToGeminiTest()
    {
        try {
            // Use a test image path en storage/app/public/test_image.jpeg
            $testImagePath = storage_path('app/public/test_image.jpeg');

            if (!file_exists($testImagePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Test image not found',
                ], 404);
            }
            // Use Facades\UploadFileToGemini to test get URI and get file MIME type
            $uri = UploadFileToGemini::getURI($testImagePath);

            return response()->json([
                'success' => true,
                'message' => 'Test for uploadFileToGeminiTestController',
                'data' => [
                    'uri' => $uri
                ]
            ]);

            // $mimeType = UploadFileToGemini::getfileMimeType();

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Test successful',
            //     'data' => [
            //         'uri' => $uri,
            //         'mimeType' => $mimeType
            //     ]
            // ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
