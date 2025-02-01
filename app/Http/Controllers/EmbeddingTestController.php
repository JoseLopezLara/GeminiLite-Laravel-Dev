<?php

namespace App\Http\Controllers;

use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Embedding;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmbeddingTestController extends Controller
{
    /**
     * Genera un embedding para un texto individual.
     */
    public function testSingleEmbedding(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'required|string'
        ]);

        try {
            $embedding = Embedding::embedText($request->text, [
                'taskType' => 'SEMANTIC_SIMILARITY'
            ]);

            return response()->json([
                'success' => true,
                'embedding' => $embedding
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera embeddings para múltiples textos en lote.
     */
    public function testBatchEmbedding(Request $request): JsonResponse
    {
        $request->validate([
            'texts' => 'required|array',
            'texts.*' => 'required|string'
        ]);

        try {
            $embeddings = Embedding::embedBatch($request->texts, [
                'taskType' => 'SEMANTIC_SIMILARITY'
            ]);

            return response()->json([
                'success' => true,
                'embeddings' => $embeddings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Demuestra el cálculo de similitud entre dos textos usando embeddings.
     */
    public function testSimilarity(Request $request): JsonResponse
    {
        $request->validate([
            'text1' => 'required|string',
            'text2' => 'required|string'
        ]);

        try {
            // Obtener embeddings para ambos textos
            $embedding1 = Embedding::embedText($request->text1);
            $embedding2 = Embedding::embedText($request->text2);

            // Calcular similitud del coseno
            $similarity = $this->cosineSimilarity($embedding1, $embedding2);

            return response()->json([
                'success' => true,
                'similarity' => $similarity,
                'embedding1' => $embedding1,
                'embedding2' => $embedding2
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcula la similitud del coseno entre dos vectores de embedding.
     */
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