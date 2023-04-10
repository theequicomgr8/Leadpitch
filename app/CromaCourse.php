<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CromaCourse extends Authenticatable
{
    protected $connection = 'mysql';
   protected $table = 'croma_cat_course';
      
    
}
