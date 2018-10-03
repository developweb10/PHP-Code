<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PaymentAccount extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'payment_accounts';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['user_id','FirstName','LastName','CompleteAddress','CountryIsoCode','StateId','CityId','TownId','PaymentModeId','ReceiveCurrencyIsoCode','BankId','Account','BankBranchId','created_at','updated_at'];

	private $rules = [
		'FirstName' 				=> 'required|max:255',
		'LastName' 					=> 'required|max:255',
		'CompleteAddress' 			=> 'required|max:1000',
		'CountryIsoCode' 			=> 'required|max:50',
		'StateId' 					=> 'required|max:50',
		'CityId'					=> 'required|max:50',
		'PaymentModeId'				=> 'required|max:50',
		'ReceiveCurrencyIsoCode'	=> 'required|max:50',
		'BankId'					=> 'required|max:50',
		'Account'					=> 'required|max:500',
		'BankBranchId'				=> 'required|max:500',
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
