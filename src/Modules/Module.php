<?php

namespace Octane\Modules;

use Illuminate\Routing\Router;

abstract class Module
{
    abstract public function getMenuItem();
    abstract public function getName();
    abstract public function routes(Router $router);

    public function getLowerCaseName()
    {
        return kebab_case($this->getName());
    }

    public function getControllerName()
    {
        return str_replace(' ', '', $this->getName());
    }
}