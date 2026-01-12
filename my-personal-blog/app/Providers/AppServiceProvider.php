<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use App\Models\Category;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        // Регистрируем observer для Category
        Category::observe(CategoryObserver::class);

        // Кастомное правило: запрет пробелов в имени файла
        Validator::extend('no_spaces', function ($attribute, $value, $parameters) {
            // Для загружаемых файлов $value — UploadedFile
            if (is_object($value) && method_exists($value, 'getClientOriginalName')) {
                return !preg_match('/\s/', $value->getClientOriginalName());
            }
            return true;
        });
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
