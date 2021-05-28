<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\View;
use App\Model\Team;
use App\Model\Tag;


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
        if((config('app.env') === 'production') || (config('app.env') === 'development')) {
            \URL::forceScheme('https');
        }
        Builder::defaultStringLength(191);

        View::composer('modals.add-agent', function($view)
        {
            $teams = Team::select('id', 'name')->get();
            $view->with(["teams"=>$teams]);
        });

        View::composer('modals.add-customer', function($view)
        {
            $tags = Tag::select('id', 'name')->get();
            $view->with(["tags"=>$tags]);
        });

        //Passport::hashClientSecrets();

        //Passport::withCookieSerialization();
    }
}