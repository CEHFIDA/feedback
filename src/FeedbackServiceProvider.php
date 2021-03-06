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
        include __DIR__.'/routes.php';
        $this->app->make('Selfreliance\Feedback\FeedbackController');
        $this->loadViewsFrom(__DIR__.'/views', 'feedback');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'translate-feedback');
        $this->publishes([
            __DIR__ . '/config/feedback.php' => config_path('feedback.php')
        ], 'config');
        $this->publishes([
            __DIR__.'/js/core.js' => public_path('js/core.js')
        ], 'javascript');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Selfreliance\Feedback\Console\EmailParser');
        $this->app->bind('emailparser', function(){
            return new \Selfreliance\Feedback\EmailParser();
        });
    }
}