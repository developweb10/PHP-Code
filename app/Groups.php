<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Groups extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'groups';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['group_name','commission'];
	
	private $rules = array(
        'group_name' => 'required|max:255',
		'commission' => 'required|numeric'
    );
	
	private $errors;
	public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules);
        // return the result
		if ($v->fails())
        {
            // set errors and return false
            $this->errors = $v->messages()->all();
            return false;
        }
        // validation pass
        return true;
    }
	
	public function errors()
    {
        return $this->errors;
    }
	public function Group_whereIn($where_col,$args){
		return $groups = Groups::whereIn($where_col,$args)->get();
	}
}
