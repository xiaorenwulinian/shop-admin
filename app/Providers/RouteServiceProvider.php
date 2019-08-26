<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapBackendRoutes();

        $this->mapMiniProgramRoutes();

        $this->mapLclapiRoutes();

        $this->mapKathyRoutes();

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * 后台管理入口
     */
    protected function mapBackendRoutes()
    {
        Route::middleware('backend')
            ->namespace($this->namespace)
            ->group(base_path('routes/backend.php'));
    }

    /**
     * 小程序路由入口
     */
    protected function mapMiniProgramRoutes()
    {
        Route::prefix('miniProgram')
            ->middleware('miniProgram')
            ->namespace($this->namespace)
            ->group(base_path('routes/miniProgram.php'));
    }


    /**
     * lclapi 测试API
     */
    protected function mapLclapiRoutes()
    {
        Route::middleware('lclapi')
            ->namespace($this->namespace)
            ->group(base_path('routes/lclapi.php'));
    }

    protected function mapKathyRoutes()
    {
        Route::middleware('kathy')
            ->namespace($this->namespace)
            ->group(base_path('routes/kathy.php'));
    }
}
