<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface GeminiLimitTokenServiceInterface
{
    public function canMakeRequest ($user) : bool;
    public function updateUsage($user, $tokens);
}