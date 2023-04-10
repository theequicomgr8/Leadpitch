<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	  protected $table = 'client';
    protected $fillable = [
        'company_id', 'name', 'email', 'mobile','from','client_to','password'
    ];

     
}
