<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Menu;
use App\Menu_rol;

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
      // para que se muestre el menú en app.blade.php:
      // view()->composer lo que hace es indicar que cada que sea
      // renderizada la vista layouts.app, se ejecutará el clousure
      // dado (en este caso, el clousure lo que hace es enviar a
      // la vista el parámetro 'menus')
      view()->composer('layouts.app', function($view) {
              $view->with('menus', Menu::menus());
          });

      view()->composer('layouts.app', function($view) {
              $view->with('menus_roles', Menu_rol::menus_roles());
          });
    }
}
