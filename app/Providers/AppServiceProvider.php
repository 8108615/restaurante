<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use App\Models\Ajuste;

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
        View::composer('*', function ($view) {
            $simboloDivisa = '$'; // Símbolo por defecto si algo falla

            // 1. Obtenemos el registro de ajustes
            $ajustes = Ajuste::first();

            if ($ajustes && $ajustes->divisa) {
                // 2. Ruta del archivo divisas.json dentro de public/
                $path = public_path('divisas.json');

                if (File::exists($path)) {
                    $json = File::get($path);
                    $divisas = json_decode($json, true);

                    // 3. Si el código guardado (ej. 'BOB') existe en el JSON, extraemos su 'symbol'
                    $codigoDivisa = $ajustes->divisa;
                    if (isset($divisas[$codigoDivisa])) {
                        $simboloDivisa = $divisas[$codigoDivisa]['symbol'];
                    }
                }
            }

            // Pasamos las variables a cualquier vista de forma global
            $view->with('ajustesGlobal', $ajustes);
            $view->with('simboloDivisa', $simboloDivisa);
        });
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
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
            : null,
        );
    }
}
