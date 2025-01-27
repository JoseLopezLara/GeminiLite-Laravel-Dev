<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Contracts;

interface GeminiRoleServiceInterface
{
    public function assignRole(string $roleId, string $roleName): bool;

}