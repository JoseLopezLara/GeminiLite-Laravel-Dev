<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\GeminiTokenCount;

class TokenLimitController extends Controller
{
    public function tokenCounter() {
        try {
            $tokens = GeminiTokenCount::coutTextTokens("Hola mundo");
            return response()->json([
                'success' => true,
                'message' => 'Everything okay ',
                'tokens'=> $tokens
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'exception' => $th->getMessage(),
            ],500);
        }
    }

    public function canMakeRequestT() {
        try {
            $user = User::find(1);
            $canMakeRequest = $user->canMakeRequestToGemini();

            return response()->json([
                'success'=> true,
                'message'=> 'Everithing okay',
                'User can make request ' => $canMakeRequest
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message'=> 'Something went wrong',
                'exception' => $th->getMessage(),
            ],500);
        }
        
    }

}
