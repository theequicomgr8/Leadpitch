<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
class CoursePdf extends Model
{
	 
	 
	
	
	protected $prefix;
    protected $table ="croma_coursepdf";	 
   
   /* public function __construct(array $attributes = [])
    {
		 
        if(isset($this->prefix)){			
            $this->table = Session::get('company_id').$this->table;
        } else {
            $this->table = Session::get('company_id').$this->table;
        }

        parent::__construct($attributes);
    } */
}
