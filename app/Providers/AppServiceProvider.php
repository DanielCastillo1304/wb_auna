<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Models\Goal;
use App\Models\Card;
use App\Models\Matchs;
use App\Observers\GoalObserver;
use App\Observers\CardObserver;
use App\Observers\MatchObserver;
use Illuminate\Support\Facades\DB;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('es');

        // Sólo si la conexión por defecto es pgsql
        $default = config('database.default');
        $driver = config("database.connections.{$default}.driver");

        if ($driver === 'pgsql') {
            // Ajusta según tus schemas
            DB::statement('SET search_path TO security, main, public, system');
        }
    }
}
