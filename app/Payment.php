<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Payment extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'payments';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['user_id','lot_id','meter_id','amount','hours','trans_id','trans_status','trans_response'];
	private $rules = [
		'lot_id' => 'required|numeric',
		'user_id' => 'required|numeric',
		'meter_id' => 'required|numeric',
		'amount' => 'required|numeric',
		'hours' => 'required|numeric',
		'trans_id' => 'required|numeric'
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

}
