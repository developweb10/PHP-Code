<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model {

	protected $table = 'payouts';

	protected $fillable=['user_id','amount','payment_email','bal_before','bal_after','trans_id','trans_status','trans_response'];

}
