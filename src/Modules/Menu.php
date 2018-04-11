<?php

namespace Octane\Modules;

class Menu
{
    public function build()
    {
        return $this->getItems()->map(function ($menuItem) {
            return $menuItem instanceof MenuItem
                ? $menuItem->convert()
                : $this->convertMenuCategory($menuItem);
        });
    }

    protected function getItems()
    {
        return collect(config('octane.modules'))
            ->map(function ($module) {
                return app($module)->getMenuItem();
            });
    }

    protected function convertMenuCategory($menuItem)
    {
        // Logic to echo menu category
    }
}
