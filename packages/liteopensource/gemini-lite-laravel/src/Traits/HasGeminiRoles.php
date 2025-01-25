<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\GeminiRoleService;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRequestLog;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRole;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRoleAssignment;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\GeminiLimitTokenService;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteUsage;

trait HasGeminiRoles
{
    public function isActiveInGemini() : bool{
        $assigment = GeminiLiteRoleAssignment::where('user_id', $this->id)->firstOrFail();
        return $assigment->active;
    }

    public function canMakeRequestToGemini(): bool
    {
        return app(GeminiLimitTokenService::class)->canMakeRequest($this);
    }

     public function assignGeminiRole($role, bool $active = true): bool
    {
        return app(GeminiRoleService::class)->assignRole($this, $role, $active);
    }

    public function storeGeminiRequest($requestType, $consumedTokens, $requestSuccessful, $requestData, $responseData)
    {
        app(GeminiLimitTokenService::class)->logRequest($this, $requestType, $consumedTokens, $requestSuccessful, $requestData, $responseData);
    }
    public function updateUsageTracking( $tokens){
        app(GeminiLimitTokenService::class)->updateUsage($this, $tokens);

    }

    /**
     * Get all of the geminiLiteRequestLog for the HasGeminiRoles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function geminiLiteRequestLog(): HasMany
    {
        return $this->hasMany(GeminiLiteRequestLog::class, 'user_id', 'id');
    }

    public function roles(){
        return $this->belongsToMany(
            GeminiLiteRole::class,
            'gemini_lite_role_assignments', // Tabla pivote
            'user_id',                     // Clave foránea del modelo actual
            'role_id'                      // Clave foránea del rol
        )->withPivot('active')->withTimestamps();
    }

    public function geminiLiteUsage(){
        return $this->hasOne(
            GeminiLiteUsage::class,
            'user_id', // Clave foránea en gemini_lite_usage
            'id'       // Clave primaria en el modelo User
        );
    }

}