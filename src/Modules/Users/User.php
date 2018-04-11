<?php

namespace Octane\Modules\Users;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, HasRoles, SoftDeletes;

    protected $with = ['roles'];

    protected $guarded = [];

    protected $appends = ['role_ids'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function homepages()
    {
        return $this->hasMany(HomePage::class, 'updated_by');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getRoleIdsAttribute()
    {
        return $this->roles->pluck('name');
    }
}
