<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LclLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 自定义门面别名和具体的实现类进行绑定
        $this->app->bind('LclLog', function () {
            return new \App\Common\FacadeConcrete\LclLog();
        });
    }
}
