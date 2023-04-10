<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CountryCode extends Authenticatable
{
    protected $connection = 'mysql3';
   protected $table = 'croma_country';
      
    
}
