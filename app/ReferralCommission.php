<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class ReferralCommission extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'referral_commissons';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['referral_id','payment_id','commission','commission_amount'];
	
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
