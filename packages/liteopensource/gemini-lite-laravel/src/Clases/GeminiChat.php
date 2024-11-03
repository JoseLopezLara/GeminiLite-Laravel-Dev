<?php
namespace LiteOpenSource\GeminiLiteLaravel\Src\Clases;

use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiChatInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\GeminiConfigAndPropertiesJSONStructures;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\GeminiRequestAndResponsesJSONStructures;
use LiteOpenSource\GeminiLiteLaravel\Src\Traits\GeminiTokenPropertiesJSONStructures;

class GeminiChat implements GeminiChatInterface
{

    //---------------------------- PROPERTIES SECTION --------------------------
    //---------------------------- PROPERTIES SECTION --------------------------
    private $guzzleClient;

    use GeminiConfigAndPropertiesJSONStructures;
    use GeminiRequestAndResponsesJSONStructures;
    use GeminiTokenPropertiesJSONStructures;

    //---------------------------- CONSTRUCTOR SECTION --------------------------
    //---------------------------- CONSTRUCTOR SECTION --------------------------
    public function __construct($guzzleClient, $secretAPIKey)
    {
        $this->guzzleClient = $guzzleClient;
        $this->addAPIKeyToGeminiModels($secretAPIKey);
        $this->initTokensChatProperties();
        $this->initDefaultConfigGeminiAPIJSON();
    }

    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    //---------------------- INTERFACE FUNCTIONS SECTION -----------------------
    // TODO: Add function in future

    public function getHistory(): mixed
    {
        return true;
    }

    public function newPrompt($textPrompt, $fileURI = null, $mimeTipe = null): mixed
    {
        Log::info("[ IN GeminiChat ->  newPrompt: ]. Gemini current model config: ", [$this->modelConfigJSON, $this->currentGeminiModel]);

        // Assembling JSON File Request
        ($fileURI && $mimeTipe)
            ? $this->assemblingJSONFileRequest($textPrompt, $fileURI, $mimeTipe)
            : $this->assemblingJSONTextRequest($textPrompt);

        try {

            //Make api gemini request and return response
            if($fileURI && $mimeTipe){
                return $this->makeFileRequestToGeminiAPI();
            } else {
                return $this->makeTextRequestToGeminiAPI();
            }

        } catch (ConnectException $e) {
            Log::error("SYSTEM THREW:: catch ConnectException in GeminiAPI.php: " . $e->getMessage());
            dd( "Connection Failed. Try more latter");
            return null;

        } catch (RequestException $e) {
            Log::error("SYSTEM THREW:: catch RequestException in GeminiAPI.php: " . $e->getResponse()->getBody());
            dd( "UPS! Something went wrong | ERROR CODE: " . $e->getResponse()->getStatusCode()) ;
            return null;

        } catch (Exception $e) {
            Log::error(" SYSTEM THREW:: catch Exception in GeminiAPI.php: " . $e->getMessage());
            dd( "UPS! Something went wrong.");
            return null;
        }
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
        $this->modelConfigJSON['topK'] = $topK;
        $this->modelConfigJSON['topP'] = $topP;
        $this->modelConfigJSON['maxOutputTokens'] = $maxOutputTokens;
        $this->modelConfigJSON['responseMimeType'] = $responseMimeType;

        // TODO: Verify if I need to add urlAPI to change between models HERE
        // TODO: Maybe is better desition crear a specific fuction to change model into interface
    }

    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    //------------------------ OTHER FUNCTIONS SECTION -------------------------
    private function initTokensChatProperties()
    {
        $this->promptTokenCount = 0;
        $this->candidatesTokenCount = 0;
        $this->totalTokenCount = 0;
        $this->totalTokenHistoryChatCount = 0;
    }

    public function assemblingJSONFileRequest($textPrompt, $fileURI, $mimeTipe){
        // Assembling file message
        $this->newFileMessageJSON['role'] = "user";
        $this->newFileMessageJSON['parts'][0]['fileData']['fileUri'] = $fileURI;
        $this->newFileMessageJSON['parts'][0]['fileData']['mimeType'] = $mimeTipe;

        array_push($this->chatHistoryJSON, $this->newFileMessageJSON);

        // Assembling text prompt after file message
        $this->newTextMessageJSON['parts'][0]['text'] = $textPrompt;
        $this->newTextMessageJSON['role'] = "user";

        array_push($this->chatHistoryJSON, $this->newTextMessageJSON);

        // Assembling body of request
        $this->bodyJSON['contents'] = $this->chatHistoryJSON;
        $this->bodyJSON['generationConfig'] = $this->modelConfigJSON;
    }

    public function assemblingJSONTextRequest($textPrompt){
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. textPrompt received: " , [$textPrompt]);

        // Assembling text prompt
        $this->newTextMessageJSON['parts'][0]['text'] = $textPrompt;
        $this->newTextMessageJSON['role'] = "user";

        array_push($this->chatHistoryJSON, $this->newTextMessageJSON);
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. newTextMessageJSON: " , [$this->newTextMessageJSON]);
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. chatHistoryJSON: " , [$this->chatHistoryJSON]);

        // Assembling body of request
        $this->bodyJSON['contents'] = $this->chatHistoryJSON;
        $this->bodyJSON['generationConfig'] = $this->modelConfigJSON;
        Log::info("[ IN GeminiChat ->  assemblingJSONTextRequest: ]. bodyJSON: " , [$this->bodyJSON]);

    }

    public function makeTextRequestToGeminiAPI(){
        // Make Request to Google Gemini REST API and get data from body
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. headersJSON" , [$this->headersJSON]);
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. geminiModelConfig" , [$this->modelConfigJSON]);
        Log::info("[ IN GeminiChat ->  makeTextRequestToGeminiAPI: ]. urlAPIModel" , [$this->currentGeminiModel]);


        $response = $this->guzzleClient->request('POST', $this->currentGeminiModel, [
            'headers' => $this->headersJSON,
            'json' => $this->bodyJSON
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);

        // Get text message, update chat history and return response
        $responseTextMessage = $responseData['candidates'][0]['content']['parts'][0]['text'];

        $this->addResponseToChatHistory($responseTextMessage, $responseData);

        return $responseTextMessage;
    }

    public function makeFileRequestToGeminiAPI(){
        // Make Request to Google Gemini REST API and get data from body
        $response = $this->guzzleClient->request('POST', $this->currentGeminiModel, [
            'headers' => $this->headersJSON,
            'json' => $this->bodyJSON
        ]);

        $responseData = json_decode($response->getBody()->getContents(), true);



        // Get text message, update chat history and return response
        $responseTextMessage = $responseData['candidates'][0]['content']['parts'][0]['text'];

        $this->addResponseToChatHistory($responseTextMessage, $responseData);

        return $responseTextMessage;
    }

    private function addResponseToChatHistory($message, $responseData){
        // Add last response to chat history
        $this->newTextMessageJSON['parts'][0]['text'] = $message;
        $this->newTextMessageJSON['role'] = "model";

        array_push($this->chatHistoryJSON, $this->newTextMessageJSON);

        //Update tokens of chat
        $this->updateTokens($responseData);
    }

    private function updateTokens($responseData){
        // Update token counter
        $this->promptTokenCount = $responseData['usageMetadata']['promptTokenCount'];
        $this->candidatesTokenCount = $responseData['usageMetadata']['candidatesTokenCount'];
        $this->totalTokenCount = $responseData['usageMetadata']['totalTokenCount'];
        $this->totalTokenHistoryChatCount += $this->totalTokenCount;
    }


    private function initDefaultConfigGeminiAPIJSON()
    {
        $this->modelConfigJSON['temperature'] = 1;
        $this->modelConfigJSON['topK'] = 64;
        $this->modelConfigJSON['topP'] = 0.95;
        $this->modelConfigJSON['maxOutputTokens'] = 8192;
        $this->modelConfigJSON['responseMimeType'] = "text/plain";

        $this->currentGeminiModel = $this->urlAPItoGeminiFlash;
    }

    private function addAPIKeyToGeminiModels($secretAPIKey){
        $this->urlAPItoGeminiFlash .= $secretAPIKey;
        $this->urlAPItoGeminiPro .= $secretAPIKey;
    }

    // ! TODO: Check if I need this to updateGeminiModelConfig
    // public function updateGeminiModelConfig($newGeminiModelConfig)
    // {
    //     $this->geminiModelConfig = array_merge($this->geminiModelConfig, $newGeminiModelConfig);
    // }


}
