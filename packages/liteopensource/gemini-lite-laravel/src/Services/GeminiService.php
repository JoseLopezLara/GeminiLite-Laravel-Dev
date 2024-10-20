<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;
use Liteopensource\GeminiLiteLaravel\Src\Clases\GeminiChat;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiServiceInterface;
use Liteopensource\GeminiLiteLaravel\Src\Traits\GeminiAPIJSONProperties;

class GeminiService implements GeminiServiceInterface
{

    /*
        --------------------------------------------------------------------------
        --------------------------- PREPERTIES SECTION ---------------------------
        --------------------------------------------------------------------------
    */

    // Token information about chat
    // --> "promptTokenCount" represent the number of token in last prompt
    // --> "candidatesTokenCount" represent the number of tokens returned to last respose
    // --> "totalTokenCount" represent the number of total tokens
    //      between input and output
    public $promptTokenCount;
    public $candidatesTokenCount;
    public $totalTokenCount;
    public $totalTokenHistoryChatCount;

    //JSON structures to assemblig request into trait
    use GeminiAPIJSONProperties;
    //Guzzle client
    private $guzzleClient;
    // URL TO MAKE REQUEST
    private $urlAPI = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=';
    /*
        --------------------------------------------------------------------------
        --------------------------- CONSTRUCT SECTION ----------------------------
        --------------------------------------------------------------------------
    */


    public function __construct($secretAPIKey){
        Log::info("[ IN GeminiService ->  __construct: ]. SecretAPIKey: ". $secretAPIKey);

        $this->initGlobalProperties($secretAPIKey);
        $this->initDefaultConfigGeminiAPIJSON();
    }

    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------

    //TODO: I should return a interface object of GeniniChat class instead mixed type
    public function gemini(): mixed
    {
        $geminiModelConfig = $this->getGeminiModelConfig();
        return new GeminiChat($geminiModelConfig);
    }

    //TODO: I should return a array instead mixed type
    //TODO: Verify if I need to add control error management when request getGeminiModelConfig and this is the first funciotn caller. Maybe this function can return error null parameters.
    public function getGeminiModelConfig(): mixed
    {
        return [
            'temperature' => $this->modelConfigJSON['temperature'],
            'top_k' => $this->modelConfigJSON['top_k'],
            'top_p' => $this->modelConfigJSON['top_p'],
            'max_output_tokens' => $this->modelConfigJSON['max_output_tokens'],
            'response_content_type' => $this->modelConfigJSON['response_content_type']
        ];

    }

    public function setGeminiModelConfig($temperature, $topK, $topP, $maxOutputTokens, $responseMimeType)
    {
        $this->modelConfigJSON['temperature'] = $temperature;
        $this->modelConfigJSON['top_k'] = $topK;
        $this->modelConfigJSON['top_p'] = $topP;
        $this->modelConfigJSON['max_output_tokens'] = $maxOutputTokens;
        $this->modelConfigJSON['response_content_type'] = $responseMimeType;
    }

    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    private function initGlobalProperties($secretAPIKey)
    {
        $this->guzzleClient = new Client();
        $this->urlAPI = $this->urlAPI. $secretAPIKey;
        $this->promptTokenCount = 0;
        $this->candidatesTokenCount = 0;
        $this->totalTokenCount = 0;
        $this->totalTokenHistoryChatCount = 0;
    }

    private function initDefaultConfigGeminiAPIJSON()
    {
        $this->modelConfigJSON['temperature'] = 1;
        $this->modelConfigJSON['top_k'] = 64;
        $this->modelConfigJSON['top_p'] = 0.95;
        $this->modelConfigJSON['max_output_tokens'] = 8192;
        $this->modelConfigJSON['response_content_type'] = "text/plain";
    }

}
