<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
class Course extends Model
{
     
	 protected $connection = 'mysql';
     protected $table = 'croma_cat_course';
}
