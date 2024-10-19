<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use GuzzleHttp\Client;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\UploadFileToGeminiServiceInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Exception;
use Illuminate\Support\Facades\Log;

class UploadFileToGeminiService implements UploadFileToGeminiServiceInterface
{
    protected $fileMimeType;
    private $secretAPIKey;

    public function __construct($secretAPIKey){
        $this->secretAPIKey = $secretAPIKey ;
        $this->fileMimeType = null;
    }


    // ---------------------------------------------------------------
    // ----------------- GETTERS AND SETTERS SECTION -----------------
    // ---------------------------------------------------------------

    public function getURI($file): mixed
    {
        // Instance of Guzzle client and RESP API URL
        $client = new Client();
        $url = 'https://generativelanguage.googleapis.com/upload/v1beta/files?key=';

        //Load and Process document
        try{

            if (!$file instanceof \Illuminate\Http\UploadedFile) {
                throw new \InvalidArgumentException('Invalid file object');
            }

            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $this->fileMimeType = $file->getMimeType();


        }catch(Exception $e){

            Log::error("SYSTEM THREW:: catch Exception in ProfessionalData.php: " . $e->getMessage());
            //dd( "ERROR: Failed to load image" );
            return null;

        }

        //Getting and returning the URI of the file processed
        try{
            $response = $client->post($url . $this->secretAPIKey, [
                'headers' => [
                    'X-Goog-Upload-Command' => 'start, upload, finalize',
                    'X-Goog-Upload-Header-Content-Length' => $fileSize,
                    'X-Goog-Upload-Header-Content-Type' => $this->fileMimeType,
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'filename' => $fileName,
                        'Mime-Type' => $this->fileMimeType,
                        'contents' => fopen($filePath, 'r'),
                    ],
                ],
            ]);

            $fileUri = json_decode($response->getBody()->getContents())->file->uri;

            return $fileUri;

        }catch(ConnectException $e){
            Log::error("SYSTEM THREW:: catch ConnectException in ProfessionalData.php: " . $e->getMessage());
            //dd( "Connection Failed. Try more latter");
            return null;

        }catch(RequestException $e ){
            Log::error("SYSTEM THREW:: catch RequestException in ProfessionalData.php: " . $e->getResponse()->getBody());
            //dd( "UPS! Something went wrong | ERROR CODE: " . $e->getResponse()->getStatusCode()) ;
            return null;

        }catch(Exception $e ){
            Log::error(" SYSTEM THREW:: catch Exception in ProfessionalData.php: " . $e->getMessage());
            //dd( "UPS! Something went wrong");
            return null;
        }
    }



    public function getfileMimeType(): string
    {
        return $this->fileMimeType ?? '';
    }
}
