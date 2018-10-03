<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Signage extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'signages';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['name','description','img_url'];

}
