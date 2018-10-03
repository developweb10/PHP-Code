<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class towing_companies extends Model {

	protected $table = 'towing_companies';

	protected $fillable=['city_name','company','contact_no','state_code'];

}
