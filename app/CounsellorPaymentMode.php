<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CounsellorPaymentMode extends Authenticatable
{
    protected $connection = 'mysql4';
   protected $table = 'croma_counsellor_paymentmode';
      
    
}
