<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model {

	//
	protected $table = "offers";
	protected $fillable = ['meter_id','offer_name','start_time','end_time','price'];
	
	
	function save_offer($data){
	}

	public function show_test()
    {
		return "TESTy";
		
		//$offer = offer::all();
		//return $offer;
  		//$users = DB::table('users')->get();

        //return view('home', ['users' => $users]);
    }


}
