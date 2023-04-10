<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EmploymentUser extends Authenticatable
{
    protected $connection = 'mysql5';
   protected $table = 'users';
      
    
}
