<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRole;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiRoleServiceInterface;

class GeminiRoleService  implements GeminiRoleServiceInterface
{
    public function assignRole($user, $role, bool $active = true): bool
    {
         $roleModel = $role instanceof GeminiLiteRole
            ? $role
            :( is_string($role)? GeminiLiteRole::where('name', $role)->firstOrFail(): GeminiLiteRole::where('id', $role)->firstOrFail());

        if ($user->roles()->where('role_id', $roleModel->id)->exists()) {
            return false;
        }
        $user->roles()->attach($roleModel->id, ['active' => $active]);

        return true;
    }
}
