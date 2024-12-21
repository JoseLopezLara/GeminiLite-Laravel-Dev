<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface GeminiTokenCountInterface
{
    //TODO: Change mixed return types to appropriate types
    public function coutTextTokens($content): mixed;
    public function countTokensWithImage(string $text, string $imagePath): mixed ;

}