<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Project;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

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
        if(env('FORCE_HTTPS',false)) {
            error_log('configuring https');

            $app_url = config("app.url");
            URL::forceRootUrl($app_url);
            $schema = explode(':', $app_url)[0];
            URL::forceScheme($schema);
        }

        View::composer('*', function ($view) {
            if (auth()->check()) {
                $projects = auth()->user()->projects()->wherePivot('invite_status', 'accepted')->get();
                $view->with('projects', $projects);
            }
        });

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadCount = Notification::where('receiver_id', Auth::id())
                    ->where('read_status', false)
                    ->count();
                $view->with('unreadCount', $unreadCount);
            }
        });
    }
}
