<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Traits;

trait GeminiConfigAndPropertiesJSONStructures
{
    // Model Config structure that represente the JSON config and has default config
    private $modelConfigJSON = [
        "temperature" => 1,
        "topK" => 64,
        "topP" => 0.95,
        "maxOutputTokens" => 8192,
        "responseMimeType" => "text/plain"
    ];

    // Response schema structure that represente the JSON schema response when you use JSON MODE
    private $responseSchema = [];
    private $currentGeminiModel = "";
    private $urlAPItoGeminiFlash = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=";
    private $urlAPItoGeminiPro = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key=";

    // TODO: Add other gemini models here:

}
