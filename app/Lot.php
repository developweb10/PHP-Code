<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Lot extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'lots';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['lot_name','lot_address','user_id','price'];
	
	private $rules = array(
        'lot_name' => 'required|max:255',
		'lot_address' => 'required|max:255',
		'price' => 'required|numeric'
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
