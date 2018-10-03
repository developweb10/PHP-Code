<?php

	namespace App;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Validator;
	
	class VariableRate extends Model {
		/**
		 * [$table description]
		 * @var string
		 */
		protected $table = 'variable_rates';
		
		protected $fillable=['lot_id','price','hr_float','offer_duration','start_time','end_time','start_date','end_time'];
		
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
?>