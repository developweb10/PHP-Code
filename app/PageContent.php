<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PageContent extends Model {
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'page_content';

	/**
	 * [$fillable description]
	 * @var array()
	 */
	protected $fillable=['page_name','page_content'];
	
	private $rules = array(
        'page_name' => 'required'
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
