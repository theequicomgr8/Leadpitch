<?php

 namespace App;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
//define ('MY_PREFIX_CONSTANT',Session::get('company_id'));

class LeadFollowUp extends Model
{
    protected $prefix;
    protected $table ="croma_lead_follow_ups";	 
    /*public function __construct(array $attributes = [])
    {
		 
        if(isset($this->prefix)){			
            $this->table = Session::get('company_id').$this->table;
        } else {
            $this->table = Session::get('company_id').$this->table;
        }

        parent::__construct($attributes);
    }
    */
}



/* namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	
	 
}
 */
 