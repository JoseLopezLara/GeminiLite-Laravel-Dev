<?php

namespace Liteopensource\GeminiLiteLaravel\Src\Traits;

trait GeminiAPIJSONProperties
{
    // This array belong to "constest" property from main JSON to do the request.
    // In adition, save the "newTexMessage" strutures to represent the history of conversationj
    // TODO: RETURN TO PRIVATE
    // TODO: RETURN TO PRIVATE
    public $chatHistoryJSON = [];

    // File structure that represente the JSON part to do a text message
    // This JSON require "role" and "text"
    private $newTextMessageJSON = [
        "role" => " ",
        "parts" => [
            [
                "text" => " "
            ]
        ]
    ];

    // File structure that represente the JSON part to do a file message
    // This JSON require "role", "fileUry" and "mimeType"
    private $newFileMessageJSON = [
        "role" => " ",
        "parts" => [
            [
                "fileData" => [
                    "fileUri" => " ",
                    "mimeType" => " "
                ]
            ]
        ]
    ];

    // Model Config structure that represente the JSON config and has default config
    private $modelConfigJSON = [
        "temperature" => 1,
        "topK" => 64,
        "topP" => 0.95,
        "maxOutputTokens" => 8192,
        "responseMimeType" => "text/plain"
    ];

    // Body of request
    private $bodyJSON = [
        "contents" => null,
        "generationConfig" => null
    ];

    //Headers of request
    private $headersJSON = [
        'Content-Type' => 'application/json',
    ];
}
