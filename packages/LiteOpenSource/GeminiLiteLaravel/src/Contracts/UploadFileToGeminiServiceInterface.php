<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface UploadFileToGeminiServiceInterface
{
    public function getURI(string $file): mixed;
    public function getfileMimeType(): string;
}
