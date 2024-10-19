<?php

namespace LiteOpenSource\GeminiLiteLaravel\Src\Providers;

use Illuminate\Support\ServiceProvider;
use LiteOpenSource\GeminiLiteLaravel\Src\Contracts\UploadFileToGeminiServiceInterface;
use LiteOpenSource\GeminiLiteLaravel\Src\Services\UploadFileToGeminiService;

class GeminiLiteServiceProvider extends ServiceProvider
{
    public function register()
    {
        // REGISTER: Merging config file to config file for Laravel APP
        $this->mergeConfigFrom(
            __DIR__.'/../../config/geminilite.php', 'geminilite'
        );

        // REGISTER: UploadFileToGeminiService ton service container
        $this->app->bind(UploadFileToGeminiServiceInterface::class, function ($app) {
            $geminiLiteSecretApiKey = config('app.geminilite_secret_api_key');
            return new UploadFileToGeminiService($geminiLiteSecretApiKey);
        });
    }

    public function boot()
    {
        // PUBLISH: Publishing config file to config folder for Laravel App using --tag="geminilite-config"
        $this->publishes([
            __DIR__.'/../../config/geminilite.php' => config_path('geminilite.php'),
        ], 'geminilite-config');

        // PUBLISH: Migrations
        $this->publishesMigrations([
            __DIR__ . '/../database/migrations' => database_path('migrations')
        ]);

        // PUBLISH: Comand tha run seeder comand using "php artisan geminilite:seed"
        if ($this->app->runningInConsole()) {
            $this->commands([
                \LiteOpenSource\GeminiLiteLaravel\Src\Commands\RunSeederCommand::class,
            ]);
        }
    }
}

