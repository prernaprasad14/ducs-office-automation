<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

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
        Route::bind('role', function ($value) {
            return Role::where('name', $value)->first() ?? abort(404);
        });

        Blade::directive('csrf_token', function () {
            return '<input type="hidden" name="<?php echo config(\'app.csrf_token_name\') ?>" value="<?php echo csrf_token(); ?>">';
        });
    }
}
