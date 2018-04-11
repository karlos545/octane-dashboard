<?php

namespace Octane\Modules;

class MenuItem
{
    protected $name;
    protected $url;
    protected $icon;
    protected $subMenu;
    private $permissions;

    public function __construct($name, $url, $icon = 'user', array $subMenu = [], $permissions = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->icon = $icon;
        $this->subMenu = $subMenu;
        $this->permissions = $permissions;
    }

    public function convert()
    {
        $menu = [
            'text' => $this->name,
            'icon' => $this->icon,
            'url' => $this->url,
            'can' => $this->permissions,
        ];

        foreach ($this->subMenu as $subMenu) {
            $menu['submenu'][] = $subMenu->convert();
        }

        return $menu;
    }
}