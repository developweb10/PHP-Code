<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Referral extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'referrals';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['user_id','referred_by','commission','referral_medium'];
	
	private $rules = array(
        'user_id' => 'required|numeric',
        'referred_by' => 'required|numeric',
		'commission' => 'required',
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

}
