<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model {

	//
	protected $table = "offers";
	protected $fillable = ['meter_id','offer_name','start_time','end_time','price'];
	private $rules = [
		'meter_id' 		=> 'required',
		'offer_name' 	=> 'required'
	];
	
	function save_offer($data){
	}
	
	public function show_test()
    {
		$offer = offer::all();
		return $offer;
    }
	public function show_meter_id($offer_id)
    {
		$meterid = offer::select('meter_id')->where('id',$offer_id)->get();
		return $meterid;
    } 
}
