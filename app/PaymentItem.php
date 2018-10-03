<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PaymentItem extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'payment_items';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['payment_id','user_id','lot_id','meter_id','hours','base_price','hour_price','total_price'];
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

}
