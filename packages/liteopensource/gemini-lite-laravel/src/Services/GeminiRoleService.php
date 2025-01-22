<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRole;

class GeminiRoleService
{
    public function assignRole($user, $role, bool $active = true): bool
    {
        $roleModel = $role instanceof GeminiLiteRole
            ? $role
            : GeminiLiteRole::where('id', $role)->orWhere('name', $role)->firstOrFail();

        if ($user->roles()->where('role_id', $roleModel->id)->exists()) {
            return false;
        }

        $user->roles()->attach($roleModel->id, ['active' => $active]);

        return true;
    }
}
