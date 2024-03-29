<?php

namespace Softinline\JCrud;

use Illuminate\Support\ServiceProvider;

class JCrudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // load views
        $this->loadViewsFrom(__DIR__.'/views', 'softinline');
        
        // load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/softinline/jcrud'),
        ]);
        
        $this->publishes([
            __DIR__.'/resources' => public_path('vendor/softinline'),
        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Softinline\JCrud\JConfig');
        $this->app->make('Softinline\JCrud\JForm');
        $this->app->make('Softinline\JCrud\JTable');
    }
}
