<?php

namespace App;
 
use Illuminate\Database\Eloquent\Model;
use Session;
define ('MY_PREFIX_CONSTANT',Session::get('company_id'));

class Capability extends Model
{
    protected $prefix;
    protected $table ="_capabilities";
	//protect $dbPrefixOveride = '';
	protected $fillable = ['user_id','capabilities'];
    public function __construct(array $attributes = [])
    {
		
        if(isset($this->prefix)){
			
            $this->table = $this->prefix.$this->table;
        } else {
            $this->table = MY_PREFIX_CONSTANT.$this->table;
        }

        parent::__construct($attributes);
    }
}