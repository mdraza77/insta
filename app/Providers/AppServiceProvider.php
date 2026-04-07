<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Sirf 'includes.right-sidebar' file ko ye data bhejo automatically
        View::composer('includes.right-sidebar', function ($view) {
            $suggestions = User::where('id', '!=', auth()->id())
                ->whereDoesntHave('followers', function ($query) {
                    $query->where('follower_id', auth()->id());
                })
                ->limit(5)
                ->get();

            $view->with('suggestions', $suggestions);
        });
    }
}
