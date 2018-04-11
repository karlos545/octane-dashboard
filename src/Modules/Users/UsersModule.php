<?php

namespace Octane\Modules\Users;

use Octane\Modules\MenuItem;
use Octane\Modules\Module;
use Illuminate\Routing\Router;

class UsersModule extends Module
{
    public function getName()
    {
        return 'Users';
    }

    public function getMenuItem()
    {
        return new MenuItem('Users', route('admin.users.index'), 'users', [
            new MenuItem('View all', route('admin.users.index'), 'list'),
            new MenuItem('Create', route('admin.users.create'), 'plus'),
        ]);
    }

    public function routes(Router $router)
    {
        $router->get('/', 'UsersController@index')->name('index');
        $router->get('create', 'UsersController@create')->name('create');
        $router->get('{user}/edit', 'UsersController@edit')->name('edit');
        $router->delete('{user}', 'UsersController@destroy')->name('delete');
    }
}