<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Meter extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'meters';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['user_id','lot_id','expiry','hours','base_price','hour_price'];
	private $rules = [
		'lot_id' => 'required|numeric',
		'user_id' => 'required|numeric'
	];
	
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
	
	/* Groups allocated to the meters of a particular client */
	public function landowners_groups($user_id){
		//$group_ids = Meter::where("user_id",$user_id)->select("group_id")->get();
		$group_ids = Meter::where("user_id",$user_id)->select("group_id")->groupBy("group_id")->get()->toArray();
		return $group_ids;
	}
}
