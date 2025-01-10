<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Traits;

trait GeminiConfigAndPropertiesJSONStructures
{
    public const GEMINI_FLASH_001 = "gemini-1.5-flash";
    public const GEMINI_FLASH_002 = "gemini-1.5-flash-002";
    public const GEMINI_FLASH_V2_0_EXP = "gemini-2.0-flash-exp";
    public const GEMINI_FLASH_V2_0_THINKING_EXP = "gemini-2.0-flash-thinking-exp-1219";
    public const GEMINI_FLASH_8B = "gemini-1.5-flash-8b";
    public const GEMINI_PRO_001 = "gemini-1.5-pro";
    public const GEMINI_PRO_002 = "gemini-1.5-pro-002";

    // Model Config structure that represente the JSON config and has default config
    private $modelConfigJSON = [
        "temperature" => 1,
        "topK" => 40,
        "topP" => 0.95,
        "maxOutputTokens" => 8192,
        "responseMimeType" => "text/plain"
    ];

    // Response schema structure that represente the JSON schema response when you use JSON MODE
    private $responseSchema = [];
    private $currentGeminiModel = "";

    //Stable Models
    private $urlAPItoGeminiFlash001 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=";
    private $urlAPItoGeminiFlash8B = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-8b:generateContent?key=";
    private $urlAPItoGeminiPro001 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key=";

    //Experimental models Models
    private $urlAPItoGeminiFlashV2Exp = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent?key=";
    private $urlAPItoGeminiFlashV2ThinkingExp = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-002:generateContent?key=";

    // ! TODO: Verivy if this model are available
    private $urlAPItoGeminiFlash002 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-002:generateContent?key=";
    private $urlAPItoGeminiPro002 = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-002:generateContent?key=";

}
