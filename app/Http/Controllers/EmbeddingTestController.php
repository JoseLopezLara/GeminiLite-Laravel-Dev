<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Embedding;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmbeddingTestController extends Controller
{
    protected function validateData(array $data, array $rules): \Illuminate\Validation\Validator
    {
        return Validator::make($data, $rules);
    }

    public function testSingleEmbedding(Request $request): JsonResponse
    {
        try {
            Log::info('[ IN EmbeddingTestController -> testSingleEmbedding ]');

            $validator = $this->validateData($request->all(), [
                'text' => 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $embedding = Embedding::embedText($request->text);

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'embedding' => $embedding
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

    public function testBatchEmbedding(Request $request): JsonResponse
    {
        try {
            Log::info('[ IN EmbeddingTestController -> testBatchEmbedding ]');

            $validator = $this->validateData($request->all(), [
                'texts' => 'required|array',
                'texts.*' => 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $embeddings = Embedding::embedBatch($request->texts);

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'embeddings' => $embeddings
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

    public function testSimilarity(Request $request): JsonResponse
    {
        try {
            Log::info('[ IN EmbeddingTestController -> testSimilarity ]');

            $validator = $this->validateData($request->all(), [
                'text1' => 'required|string|min:1',
                'text2' => 'required|string|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $embedding1 = Embedding::embedText($request->text1);
            $embedding2 = Embedding::embedText($request->text2);

            // Calcular similitud del coseno
            $similarity = $this->cosineSimilarity($embedding1, $embedding2);

            return response()->json([
                'success' => true,
                'message' => 'Test successful',
                'data' => [
                    'similarity' => $similarity,
                    'embedding1' => $embedding1,
                    'embedding2' => $embedding2
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

    private function cosineSimilarity(array $vector1, array $vector2): float
    {
        if (count($vector1) !== count($vector2)) {
            throw new \InvalidArgumentException('Los vectores deben tener la misma dimensión');
        }

        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;

        foreach ($vector1 as $i => $value1) {
            $value2 = $vector2[$i];
            $dotProduct += $value1 * $value2;
            $magnitude1 += $value1 * $value1;
            $magnitude2 += $value2 * $value2;
        }

        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);

        if ($magnitude1 == 0 || $magnitude2 == 0) {
            return 0;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }
}