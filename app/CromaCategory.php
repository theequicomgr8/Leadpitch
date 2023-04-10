<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CromaCategory extends Authenticatable
{
    protected $connection = 'mysql4';
   protected $table = 'croma_category';
      
    
}
