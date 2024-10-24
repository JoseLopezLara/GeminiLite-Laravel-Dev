<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use GuzzleHttp\Client;
//TODO: Che if I need to add Exceptions and Logs
use Exception;
use Illuminate\Support\Facades\Log;
use Liteopensource\GeminiLiteLaravel\Src\Clases\GeminiChat;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiServiceInterface;
use Liteopensource\GeminiLiteLaravel\Src\Traits\GeminiConfigAndPropertiesJSONStructures;

class GeminiService implements GeminiServiceInterface
{

    /*
        --------------------------------------------------------------------------
        --------------------------- PREPERTIES SECTION ---------------------------
        --------------------------------------------------------------------------
    */
    //TODO: Change coment documentatio to JSON megminiModelConfig
    //JSON structures to assemblig request into trait
    use GeminiConfigAndPropertiesJSONStructures;
    //Guzzle client
    private $guzzleClient;
    // URL TO MAKE REQUEST
    private $urlAPI = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=';

    //---------------------------- CONSTRUCTOR SECTION --------------------------
    //---------------------------- CONSTRUCTOR SECTION --------------------------
    public function __construct($secretAPIKey){
        Log::info("[ IN GeminiService ->  __construct: ]. SecretAPIKey: ". $secretAPIKey);

        $this->guzzleClient = new Client();
        $this->initDefaultConfigGeminiAPIJSON($secretAPIKey);
    }

    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    //TODO: I should return a interface object of GeniniChat class instead mixed type
    public function gemini(): mixed
    {
        $geminiModelConfig = $this->getGeminiModelConfig();
        return new GeminiChat($geminiModelConfig, $this->guzzleClient);
    }

    //TODO: I should return a array instead mixed type
    //TODO: Verify if I need to add control error management when request getGeminiModelConfig and this is the first funciotn caller. Maybe this function can return error null parameters.
    //TODO: Thiking about before TODO, I need to verify urlAPI and secretAPIKey.
    public function getGeminiModelConfig(): mixed
    {
        return $this->modelConfigJSON;
    }

    public function setGeminiModelConfig($temperature, $topK, $topP, $maxOutputTokens, $responseMimeType)
    {
        $this->modelConfigJSON['temperature'] = $temperature;
        $this->modelConfigJSON['top_k'] = $topK;
        $this->modelConfigJSON['top_p'] = $topP;
        $this->modelConfigJSON['max_output_tokens'] = $maxOutputTokens;
        $this->modelConfigJSON['response_content_type'] = $responseMimeType;
        // TODO: Verify if I need to add urlAPI to change between models
    }

    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //TODO: Verify if I need to init default config here, because I have the same in the JSON Object in the Trait
    private function initDefaultConfigGeminiAPIJSON($secretAPIKey)
    {
        $this->modelConfigJSON['temperature'] = 1;
        $this->modelConfigJSON['top_k'] = 64;
        $this->modelConfigJSON['top_p'] = 0.95;
        $this->modelConfigJSON['max_output_tokens'] = 8192;
        $this->modelConfigJSON['response_content_type'] = "text/plain";
        //TODO: I think that is correct config secret apiKey here, but... I can to do this in the trait
        $this->modelConfigJSON['url_API'] = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $secretAPIKey;
    }

}
