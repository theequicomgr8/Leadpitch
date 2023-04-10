<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Session;
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	 
	
	protected $prefix;
	protected $table = 'croma_users';
    protected $fillable = [
        'name', 'email', 'role', 'password',
    ];
 
/*	 
    public function __construct(array $attributes = [])
    {
		
        if(isset($this->prefix)){
		 
            $this->table = Session::get('company_id').$this->table;
        } else {
            
            $this->table = Session::get('company_id').$this->table;
        }

        parent::__construct($attributes);
    }
	*/
	
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'password',
		'remember_token',
    ];
	
	public function capability(){
		return $this->hasOne('App\Capability');
	}
	
	public function current_user_can($role_cap=NULL){
		if(is_null($role_cap))
			return false;
		if($this->role == $role_cap)
			return true;
		$capabilities = $this->capability()->first();
		 
		if($capabilities){
			if(isset($capabilities->capabilities) && !is_null($capabilities->capabilities)){
				$capabilities = unserialize($capabilities->capabilities);
				if(!empty($capabilities)){
				foreach($capabilities as $capability){
					if($capability == $role_cap)
						return true;
				}
			}
			}
		}
		return false;
	}
}
