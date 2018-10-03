<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsQueue extends Model {

	protected $table = "sms_queue";
	protected $fillable=['to','sms_body','send_at','meter_id','meter_owner'];

}
