<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CromaLead extends Authenticatable
{
    protected $connection = 'mysql';
   protected $table = 'croma_enquiries';
      
    
}
