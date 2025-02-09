<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Faker\Factory as Faker;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\Gemini;
use LiteOpenSource\GeminiLiteLaravel\Src\Facades\GeminiTokenCount;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRoleAssignment;
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
    public function assigRoles() {
        $faker = Faker::create();
        try {
            $testUser = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password'=> Hash::make("1234"),
            ]);

            $role = $testUser->assignGeminiRole(1);

            $testUser2 = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password'=> Hash::make("1234"),
            ]);

            $role = $testUser2->assignGeminiRole('limited_user');

            return response()->json([
                'success'=> true,
                'message'=> 'Everithing okay',
                'User role 1' => GeminiLiteRoleAssignment::where('user_id', $testUser->id)->first(),
                'User role 2' => GeminiLiteRoleAssignment::where('user_id', $testUser2->id)->first(),
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message'=> 'Something went wrong',
                'exception' => $th->getMessage(),
            ],500);
        }
    }

    public function limit()  {
        $faker = Faker::create();
        try {
            $testUser = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password'=> Hash::make("1234"),
            ]);

            $role = $testUser->assignGeminiRole('limited_user');
            $prompt = "Hola gemini";
            $gemini = Gemini::newChat();

            for ($i = 0; $i < 3; $i++) {
                if(!$testUser->canMakeRequestToGemini()){
                    return response()->json([
                        'code' => 403,
                        "success"=> true,
                        "message"=> "It works properly",
                        'usage' => GeminiLiteUsage::where('user_id', $testUser->id)->first(),
                    ],403);
                }
                $response = $gemini->newPrompt($prompt);
                $tokens = GeminiTokenCount::coutTextTokens($prompt);
                $testUser->updateUsageTracking($tokens);
            }

            return response()->json([
                "success"=> true,
                "message"=> "Something went wrong",
            ],500);

        } catch (\Throwable $th) {
            return response()->json([
                'success'=> false,
                'message'=> 'Something went wrong',
                'exception' => $th->getMessage(),
            ],500);
        }
    }
    public function logs()  {
        $faker = Faker::create();
        try {
            $testUser = User::find(1);

            $prompt = "Hola gemini";
            $gemini = Gemini::newChat();

            if(!$testUser->canMakeRequestToGemini()){
                return response()->json([
                    'code' => 403,
                    "success"=> true,
                    "message"=> "The user cannot make requests to gemini",
                ],403);
            }

            $response = $gemini->newPrompt($prompt);
            $tokens = GeminiTokenCount::coutTextTokens($prompt);
            $testUser->updateUsageTracking($tokens);
            $testUser->storeGeminiRequest("Test", $tokens, true, ["request"=> $prompt],["response"=> $response]); 

            return response()->json([
                "success"=> true,
                "message"=> "Everithing OK",
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
