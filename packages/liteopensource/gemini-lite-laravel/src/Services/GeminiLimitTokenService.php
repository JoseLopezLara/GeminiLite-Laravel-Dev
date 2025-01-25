<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Services;

use LiteOpenSource\GeminiLiteLaravel\Src\Models\GeminiLiteRequestLog;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\GeminiLimitTokenServiceInterface;

class GeminiLimitTokenService implements GeminiLimitTokenServiceInterface
{
    public function canMakeRequest($user): bool
    {
        $usage = $user->geminiLiteUsage;

        if (!$usage) {
            $usage = $this->initializeUsage($user);
        }

        $this->validateLimits($user );

        return $usage->can_make_requests;
    }

    private function initializeUsage($user)
    {
        return $user->geminiLiteUsage()->create([
            'can_make_requests' => true,
            'current_day_tracking_start' => now(),
            'current_month_tracking_start' => now(),
            'completed_requests_today' => 0,
            'completed_requests_this_month' => 0,
            'consumed_tokens_today' => 0,
            'consumed_tokens_this_month' => 0,
            'last_request_completion_time' => null,
        ]);
    }

    public function logRequest($user, $requestType, $consumedTokens, $requestSuccessful, $requestData, $responseData)
    {
        GeminiLiteRequestLog::create([
            'user_id' => $user->id,
            'request_type' => $requestType,
            'consumed_tokens' => $consumedTokens,
            'request_successful' => $requestSuccessful,
            'request_data' => $requestData,
            'response_data' => $responseData,
        ]);
    }
    public function updateUsage($user, $tokens){
        $geminiUsage = $user->geminiLiteUsage();
        $this->validateDailyLimits($user);
        $this->validateMonthlyLimits($user);

        $geminiUsage->increment('completed_requests_today');
        $geminiUsage->increment('completed_requests_this_month');
        $geminiUsage->increment('consumed_tokens_today',$tokens);
        $geminiUsage->increment('consumed_tokens_this_month',$tokens);
        $geminiUsage->update(['last_request_completion_time' => now()]);

    }

    private function validateLimits($user): bool{
        $this->validateDailyLimits($user);
        $this->validateMonthlyLimits($user);

        $usage = $user->geminiLiteUsage()->first();
        $role = $user->roles()->first();
        if (
            $role->daily_request_limit <= $usage->completed_requests_today || 
            $role->monthly_request_limit <= $usage->completed_requests_user_month ||
            $role->daily_token_limit <= $usage->consumed_tokens_today  ||
            $role->monthly_token_limit <= $usage->consumed_tokens_user_month
        ){
            $user->geminiLiteUsage()->update(['can_make_requests' => false]);
            return false;
        }else {
            $user->geminiLiteUsage()->update(['can_make_requests' => true]);
            return true;
        }

    }

    private function validateDailyLimits($user){
        $lastRequestTime = \Carbon\Carbon::parse($user->geminiLiteUsage()->first()->last_request_completion_time);

        if ($lastRequestTime->format('Y-m-d') !== now()->format('Y-m-d')) {
            // Reiniciar contadores diarios
            $user->geminiLiteUsage()->update([
                'completed_requests_today' => 0,
                'consumed_tokens_today' => 0,
                'current_day_tracking_start' => now()->toDateTimeString(), // Guardar como string
            ]);
        }
    }
    protected function validateMonthlyLimits($user){
        $lastRequestTime = \Carbon\Carbon::parse($user->geminiLiteUsage()->first()->last_request_completion_time);

        if ($lastRequestTime->format('Y-m') !== now()->format('Y-m')) {
            // Reiniciar contadores mensuales
            $user->geminiLiteUsage()->update([
                'completed_requests_this_month' => 0,
                'consumed_tokens_this_month' => 0,
                'current_month_tracking_start' => now()->toDateTimeString(), // Guardar como string
            ]);
        }
    }
}
