<?php namespace App;

use App\Meter; use App\Payment; use App\PaymentItem;

use Illuminate\Database\Eloquent\Model;

class query extends Model{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	public function filter_transactions($date){
		
		$groupBy =  "day(payments.created_at), month(payments.created_at), year(payments.created_at)" ;
		$meter_details = PaymentItem::join("payments","payments.id","=","payment_items.payment_id")->join("meters","meters.id","=","payment_items.meter_id")->where("meters.meter_id",$meter_id)->where("payments.payment_type",'meter_hire')->where( function( $obj ) use($inputs) {

				
					if( !empty($inputs["start_date"]) ){
						$obj->where("payments.created_at_",">=",date("Y-m-d H:i:s",strtotime($inputs["start_date"]." 00:00:00")));
					}
					if( !empty($inputs["end_date"]) ){
						$obj->where("payments.created_at","<=",date("Y-m-d H:i:s",strtotime($inputs["end_date"]." 23:59:00")));
					}
				
		} )->groupBy(DB::raw($groupBy))->select(DB::raw("payments.created_at as trans_day, sum(payment_items.hours) as hours, sum(payments.amount) as trans_amount,
count(payments.id) as transactions "))->get();
	}
}



