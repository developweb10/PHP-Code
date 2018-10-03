<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model {

	protected $table = 'sms_log';

	protected $fillable=['to','sms_body','sms_status','meter_id','meter_owner'];

}
