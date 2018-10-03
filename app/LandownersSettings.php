<?php

	namespace App;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Validator;
	
	class LandownersSettings extends Model {
		/**
		 * [$table description]
		 * @var string
		 */
		protected $table = 'landowners_settings';
		
		protected $fillable=['user_id','variable_rates','sms_feature','email_feature','recipient_mobile','recipient_email'];
		
		private $rules = [
			'user_id' => 'required|numeric',
			'recipient_mobile' => 'unique:landowners_settings',
			'recipient_email' => 'unique:landowners_settings|max:255|email',
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
		
		public function settings_exists($userid)
		{
			$settings_exists = LandownersSettings::where('user_id',$userid)->get();
			if( count($settings_exists) > 0 ){
				// Update
				return  $settings_exists[0]->id; 
			}else{
				// Insertion
				return 0;
			}
			
			
		}
		public function settings_select($userid)
		{
			$settings_select = LandownersSettings::where('user_id',$userid)->get();
			if( count($settings_select) > 0 ){
				
				return  $settings_select[0]; 
			}
		
		}
	}
?>