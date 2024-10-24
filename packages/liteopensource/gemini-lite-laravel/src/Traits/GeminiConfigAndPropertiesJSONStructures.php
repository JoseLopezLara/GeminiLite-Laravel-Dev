<?php

namespace Liteopensource\GeminiLiteLaravel\Src\Traits;

trait GeminiConfigAndPropertiesJSONStructures
{
    // Model Config structure that represente the JSON config and has default config
    private $modelConfigJSON = [
        "temperature" => 1,
        "top_k" => 64,
        "top_p" => 0.95,
        "max_output_tokens" => 8192,
        "response_content_type" => "text/plain",
        "url_API" => "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key="
    ];

}