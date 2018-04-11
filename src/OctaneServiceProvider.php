<?php

namespace Octane;

use JeroenNoten\LaravelAdminLte\Http\ViewComposers\AdminLteComposer;
use Octane\Console\InstallOctaneCommand;
use Octane\Menu;
use Octane\Console\CreateUserCommand;
use Illuminate\Database\QueryException;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Octane\Modules\ModuleModel;

class OctaneServiceProvider extends ServiceProvider
{
    /**
     * @var Collection
     */
    protected $modules;

    protected $routeMiddleware =  [
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $this->mergeConfigFrom(__DIR__.'/config/octane.php', 'octane');

        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'octane');

        $this->bootModules($events);

        $this->commands([
            CreateUserCommand::class,
            InstallOctaneCommand::class,
        ]);

        $this->registerMiddleware();

        $this->app['view']->composer('octane::adminlte.page', AdminLteComposer::class);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function registerRoutes($events)
    {
        try {
            $rolesForModules = ModuleModel::all();
        } catch (QueryException $e) {
            $rolesForModules = collect();
        } catch (\PDOException $e) {
            $rolesForModules = collect();
        }

        $this->modules->each(function ($module) use ($rolesForModules) {
            $roles = implode('|', $this->getRoles($module, $rolesForModules));
            $this->app['router']->group([
                'prefix' => "admin/{$module->getLowerCaseName()}",
                'as' => "admin.{$module->getLowerCaseName()}.",
                'namespace' => "Octane\\Modules\\{$module->getControllerName()}\Http\Controllers",
                'middleware' => ['web', "role:superadmin|{$roles}"]
            ], function ($router) use ($module) {
                $module->routes($router);
            });
        });

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) use ($rolesForModules) {
            $this->modules->each(function ($module) use ($event, $rolesForModules) {
                $roles = $this->getRoles($module, $rolesForModules);
                if (request()->user()->hasAnyRole($roles)) {
                    $event->menu->add($module->getMenuItem()->convert());
                }
            });
        });
    }

    private function bootModules($events)
    {
        $this->modules = collect(config('octane.modules'))->map(function ($module) {
            return $this->app->make($module);
        });

        $this->modules->each(function ($module) {
            if (method_exists($module, 'boot')) {
                app()->call(get_class($module) . "@boot");
            }
        });

        $this->registerRoutes($events);
    }

    private function getRoles($module, $rolesForModules)
    {
        $dbModule = $rolesForModules->first(function ($dbModule) use ($module) {
            return $dbModule->module_class_name === class_basename($module);
        });

        if (!$dbModule) {
            return [];
        }

        if (array_key_exists('roles', $dbModule->visible_to)) {
            return $dbModule->visible_to['roles'];
        }
    }

    private function registerMiddleware()
    {
        collect($this->routeMiddleware)->each(function ($middleware, $key) {
            $this->app['router']->aliasMiddleware($key, $middleware);
        });
    }
}