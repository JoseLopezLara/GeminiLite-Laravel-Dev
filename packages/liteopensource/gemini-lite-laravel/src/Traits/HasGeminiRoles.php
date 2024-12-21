<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Traits;

use Illuminate\Support\Facades\Log;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRequestLog;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRole;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRoleAssignment;
use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteUsage;

trait HasGeminiRoles
{
    public function test() {
        Log::info("Esta es una linea de prueba para conprobar el ciclo de testing del paquete ");
    }

    public function isActiveInGemini() : bool{
        $assigment = GeminiLiteRoleAssignment::where('user_id', $this->id)->firstOrFail();
        return $assigment->active;
    }

    public function canMakeRequestToGemini() : bool{
        $this->validateLimits();

        $usage = $this->geminiLiteUsage()->first();
        if (!$usage) {
            $usage = $this->intiGeminiLiteUsage();
        } 
        return $usage->can_make_requests;
    }

    public function intiGeminiLiteUsage(){
        $usage = $this->geminiLiteUsage()->create([
            'can_make_requests' => true,
            'current_day_tracking_start' => now(),
            'current_month_tracking_start' => now(),
            'completed_requests_today' => 0,
            'completed_requests_this_month' => 0,
            'consumed_tokens_today' => 0,
            'consumed_tokens_this_month' => 0,
            'last_request_completion_time' => null,
        ]);
        return $usage;

    }

    public function storeGeminiRequest($request_type, $consumed_tokens, $request_successful, $request_data,$response_data)  {
        GeminiLiteRequestLog::create([
            'user_id' => $this->id,
            'request_type' => $request_type,
            'consumed_tokens' => $consumed_tokens,
            'request_successful' => $request_successful,
            'request_data' => $request_data,
            'response_data' => $response_data
        ]);
    }

    public function validateLimits(): bool{
        $this->validateDailyLimits();
        $this->validateMonthlyLimits();

        $usage = $this->geminiLiteUsage()->first();
        $role = $this->roles()->first();
        if (
            $role->daily_request_limit <= $usage->completed_requests_today || 
            $role->monthly_request_limit <= $usage->completed_requests_this_month ||
            $role->daily_token_limit <= $usage->consumed_tokens_today  ||
            $role->monthly_token_limit <= $usage->consumed_tokens_this_month
        ){
            $this->geminiLiteUsage()->update(['can_make_requests' => false]);
            return false;
        }else {
            $this->geminiLiteUsage()->update(['can_make_requests' => true]);
            return true;
        }
        return true;

    }

    public function assignGeminiRole($role, bool $active = true): bool
    {
        if (!$role instanceof GeminiLiteRole) {
            $role = GeminiLiteRole::where('id', $role)
                                  ->orWhere('name', $role)
                                  ->firstOrFail();
        }

        $existingAssignment = $this->roles()
            ->where('role_id', $role->id)
            ->exists();

        if ($existingAssignment) {
            return false; 
        }

        $this->roles()->attach($role->id, ['active' => $active]);

        return true;
    }

    public function updateUsageTracking($tokens){
        $geminiUsage = $this->geminiLiteUsage();
        $this->validateDailyLimits();
        $this->validateMonthlyLimits();

        $geminiUsage->increment('completed_requests_today');
        $geminiUsage->increment('completed_requests_this_month');
        $geminiUsage->increment('consumed_tokens_today',$tokens);
        $geminiUsage->increment('consumed_tokens_this_month',$tokens);
        $geminiUsage->update(['last_request_completion_time' => now()]);

    }

    protected function validateDailyLimits(){
        $lastRequestTime = \Carbon\Carbon::parse($this->geminiLiteUsage()->first()->last_request_completion_time);

        Log::info("Valores");
        Log::info(now()->format('Y-m-d'));
        Log::info($lastRequestTime->format('Y-m-d'));
        Log::info($lastRequestTime->format('Y-m-d') !== now()->format('Y-m-d')? 1: 0);
        if ($lastRequestTime->format('Y-m-d') !== now()->format('Y-m-d')) {
            // Reiniciar contadores diarios
            $this->geminiLiteUsage()->update([
                'completed_requests_today' => 0,
                'consumed_tokens_today' => 0,
                'current_day_tracking_start' => now()->toDateTimeString(), // Guardar como string
            ]);
        }
    }

    protected function validateMonthlyLimits(){
        $lastRequestTime = \Carbon\Carbon::parse($this->geminiLiteUsage()->first()->last_request_completion_time);

        if ($lastRequestTime->format('Y-m') !== now()->format('Y-m')) {
            // Reiniciar contadores mensuales
            $this->geminiLiteUsage()->update([
                'completed_requests_this_month' => 0,
                'consumed_tokens_this_month' => 0,
                'current_month_tracking_start' => now()->toDateTimeString(), // Guardar como string
            ]);
        }
    }
    /**
     * Get all of the geminiLiteRequestLog for the HasGeminiRoles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function geminiLiteRequestLog(): HasMany
    {
        return $this->hasMany(geminiLiteRequestLog::class, 'user_id', 'id');
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