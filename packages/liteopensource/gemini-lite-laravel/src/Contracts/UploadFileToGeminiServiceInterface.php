<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface UploadFileToGeminiServiceInterface
{
    public function getURIFromFile($file): mixed;
    public function getURIFromPath(string $filePath): mixed;
    public function getfileMimeType(): string;
}
