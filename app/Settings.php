<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Settings extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'settings';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['sa_commission','promo_code_discount_lo','meter_price_lo'];
	
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
