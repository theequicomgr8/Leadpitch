<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
class Demo extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	
	
	protected $prefix;
    protected $table ="croma_demos";	 
    
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
