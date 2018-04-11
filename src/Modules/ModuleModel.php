<?php

namespace Octane\Modules;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{
    protected $table = 'modules';
    protected $guarded = [];

    protected $casts = [
        'visible_to' => 'json'
    ];

    public function setModuleClassNameAttribute($class)
    {
        $this->attributes['module_class_name'] = class_basename($class);
    }

    public function addRole($role)
    {
        $visibleTo = $this->visible_to;

        // If we already allow this role lets act like this never happened
        if (in_array($role, $visibleTo['roles'])) {
            return $this;
        }

        $visibleTo['roles'][] = $role;

        return tap($this)->update(['visible_to' => $visibleTo]);
    }

    public function addRoles(array $roles)
    {
        $visibleTo = $this->visible_to;

        // Lets assume we're syncing the roles
        $visibleTo['roles'] = $roles;

        return tap($this)->update(['visible_to' => $visibleTo]);
    }

    public function removeRole($role)
    {
        $visibleTo = $this->visible_to;

        // If that role doesn't have permission to see the module there is nothing for us to remove.
        if (! in_array($role, $visibleTo['roles'])) {
            return $this;
        }

        unset($visibleTo['roles'][$role]);

        return tap($this)->update(['visible_to' => $visibleTo]);
    }

    public function getRolesAttribute()
    {
        if (array_key_exists('roles', $this->visible_to)) {
            return $this->visible_to['roles'];
        }

        return [];
    }

    public function getNameAttribute()
    {
        return preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]/', ' $0', $this->module_class_name);
    }
}
