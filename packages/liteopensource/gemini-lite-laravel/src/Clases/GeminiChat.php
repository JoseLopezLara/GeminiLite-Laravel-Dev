<?php
namespace Liteopensource\GeminiLiteLaravel\Src\Clases;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiChatInterface;

class GeminiChat implements GeminiChatInterface
{

    public function __construct($geminiModelConfig)
    {

    }

    public function getHistory(): mixed
    {
        return true;
    }

    public function newPrompt($textPrompt, $fileURI = null, $mimeTipe = null): mixed
    {
        return true;
    }
}
