<?php

namespace App\Model;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    protected $primaryKey = 'user_id';

    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'user_type', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function clientPersonal()
    {
        return $this->hasOne('App\Model\ClientPersonal', 'user_id');
    }
    public function clientCorporate()
    {
        return $this->hasOne('App\Model\ClientCorporate', 'user_id');
    }
    public function partnerPersonal()
    {
        return $this->hasOne('App\Model\PartnerPersonal', 'user_id');
    }
    public function partnerCorporate()
    {
        return $this->hasOne('App\Model\PartnerCorporate', 'user_id');
    }
    public function admin()
    {
        return $this->hasOne('App\Model\Admin', 'user_id');
    }
}
