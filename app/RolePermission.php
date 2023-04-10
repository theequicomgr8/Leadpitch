<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
class RolePermission extends Model
{
    
	protected $prefix;
    protected $table ="_roles_permissions";	 
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
