<?php

declare(strict_types=1);

namespace MetasyncSite\SanctumTokens;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ToolServiceProvider extends ServiceProvider
{
    public static string $name = 'sanctum-tokens';

    public function boot(): void
    {
        $this->app->booted(function (): void {
            $this->routes();
        });

        $this->publishes([
            __DIR__.'/../resources/lang' => lang_path(
                'vendor/'.static::$name
            ),
        ]);

        $this->publishes([
            __DIR__.'/../config/sanctum-tokens.php' => config_path('sanctum-tokens.php'),
        ], 'sanctum-tokens-config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_stored_tokens_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_stored_tokens_table.php'),
            __DIR__.'/../database/migrations/add_description_to_personal_access_tokens_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time() + 1).'_add_description_to_personal_access_tokens_table.php'),
        ], 'sanctum-tokens-migrations');

        Nova::serving(function (ServingNova $event): void {
            Nova::script('sanctum-tokens', __DIR__.'/../dist/js/tool.js');
            Nova::style('sanctum-tokens', __DIR__.'/../dist/css/tool.css');
            Nova::translations(static::getTranslations());
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/sanctum-tokens.php',
            'sanctum-tokens'
        );
    }

    protected function routes(): void
    {
        if (
            $this->app->routesAreCached() ||
            config('sanctum.routes') === false
        ) {
            return;
        }

        Route::middleware(['nova'])
            ->prefix('nova-vendor/'.static::$name)
            ->group(__DIR__.'/../routes/api.php');
    }

    private static function getTranslations(): array
    {
        $translationFile = lang_path(
            'vendor/'.static::$name.'/'.app()->getLocale().'.json'
        );

        if (! is_readable($translationFile)) {
            $translationFile =
                __DIR__.'/../resources/lang/'.app()->getLocale().'.json';

            if (! is_readable($translationFile)) {
                return [];
            }
        }

        return json_decode(file_get_contents($translationFile), true);
    }
}
