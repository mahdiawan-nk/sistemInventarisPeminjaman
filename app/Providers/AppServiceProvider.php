<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
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
        Blade::directive('activeClass', function ($route) {
            return "<?php echo request()->routeIs(" . var_export(trim($route, "'\""), true) . ")
        ? 'dark:bg-white/10 dark:text-white bg-black/10 text-neutral-900'
        : 'dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white';
    ?>";
        });
    }
}
