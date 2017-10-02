<?php

namespace Selfreliance\feedback;

use Illuminate\Support\ServiceProvider;

class FeedbackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        include __DIR__.'/routes.php';
        $this->app->make('Selfreliance\Feedback\FeedbackController');
        $this->loadViewsFrom(__DIR__.'/views', 'feedback');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->publishes([
            __DIR__ . '/migrations/' => base_path('/database/migrations'),
        ], 'migrations');
        
        $this->publishes([
            __DIR__ . '/notification/SupportNotification.php' => app_path('Notifications/SupportNotification.php'),
        ], 'notification');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
