<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\GeminiTokenCount;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteUsage;

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

    public function isActive() {
        try {
            $user = User::find(1);
            $isActive = $user->isActiveInGemini();

            return response()->json([
                'success'=> true,
                'message'=> 'Everithing okay',
                'User is active' => $isActive
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message'=> 'Something went wrong',
                'exception' => $th->getMessage(),
            ],500);
        }
    }
    public function updateUsage() {
        try {
            $tokens = GeminiTokenCount::coutTextTokens("Hola mundo");
            $user = User::find(1);

            if(!$user->canMakeRequestToGemini()){
                return response()->json([
                    "success"=> false,
                    "message"=> "User unauthorazed",
                ],403);
            }

            $user->updateUsageTracking($tokens);
            $geminiUsage = GeminiLiteUsage::where("user_id", $user->id)->first();

            return response()->json([
                'success'=> true,
                'message'=> 'Everithing okay',
                'geminiUsage' => $geminiUsage
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
