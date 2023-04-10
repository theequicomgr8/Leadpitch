<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FeesCourse extends Authenticatable
{
    protected $connection = 'mysql3';
   protected $table = 'wp_courses_details';
      
    
}
