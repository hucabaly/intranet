<?php

namespace Rikkei\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends Model implements Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'nickname', 'email', 'employee_id', 'token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token',
    ];

    public function getAuthIdentifier()
    {

    }

    public function getAuthIdentifierName()
    {

    }

    public function getAuthPassword()
    {

    }

    public function getRememberToken()
    {

    }

    public function getRememberTokenName()
    {

    }

    public function setRememberToken($value)
    {

    }
}