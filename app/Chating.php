<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chating extends Model
{
    //
	protected $prefix;
    protected $table ="_chating";	 
    public function __construct(array $attributes = [])
    {
		 
        if(isset($this->prefix)){			
            $this->table = Session::get('company_id').$this->table;
        } else {
            $this->table = Session::get('company_id').$this->table;
        }

        parent::__construct($attributes);
    }
}
