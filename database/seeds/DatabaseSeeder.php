<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Role;
use App\User;
use App\Country;


class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){
	   Model::unguard();

                /*if (App::environment() === 'production')
                {
                    exit('I just stopped you getting fired. Love, Amo.');
                }*/
                DB::table('roles')->truncate();
				 Role::create([
                    'id'            => 1,
                    'name'          => 'Administrator',
                    'description'   => 'Access to whole system.'
                ]);
                Role::create([
                    'id'            => 2,
                    'name'          => 'Sales Associate',
                    'description'   => 'administrator creates their account after hired by my-meter.com. They login to view commissions/ sales reports and are provided with marketing tools. This type of account is hidden behind the scenes but they should be able to login from the home page using the same login button as landowners.'
                ]);
                Role::create([
                    'id'            => 3,
                    'name'          => 'Landowner',
                    'description'   => 'can create their own login account from homepage - creates meters, view reports, etc.  - also has access to inspections url which can be shared and does not require login to view it\'s contents.'
                ]);
                Role::create([
                    'id'            => 4,
                    'name'          => 'Customer',
                    'description'   => 'only using the website to pay from the homepage, and won\'t have a login account.'
                ]);
               /* Role::create([
                    'id'            => 1,
                    'name'          => 'Root',
                    'description'   => 'Use this account with extreme caution. When using this account it is possible to cause irreversible damage to the system.'
                ]);
                Role::create([
                    'id'            => 2,
                    'name'          => 'Administrator',
                    'description'   => 'Full access to create, edit, and update, and orders.'
                ]);
                Role::create([
                    'id'            => 3,
                    'name'          => 'Manager',
                    'description'   => 'Ability to create new companies and orders, or edit and update any existing ones.'
                ]);
                Role::create([
                    'id'            => 4,
                    'name'          => 'Company Manager',
                    'description'   => 'Able to manage the company that the user belongs to, including adding sites, creating new users and assigning licences.'
                ]);
                Role::create([
                    'id'            => 5,
                    'name'          => 'User',
                    'description'   => 'A standard user that can have a licence assigned to them. No administrative features.'
                ]);*/
				DB::table("users")->wherein("id",[1,2,3,4])->delete();
				
                User::create([
                    'id'         =>  1,
                    'role_id'    =>  1,
                    'name'       =>  'Administrator',
                    'email'      => 'admin@mymeter.com',
                    'password'   => Hash::make('admin'),
					'country'      => '1',
                ]);

                User::create([
                    'id'         =>  2,
                    'role_id'    =>  2,
                    'name'       =>  'Staff',
                    'email'      => 'staff@mymeter.com',
                    'password'   => Hash::make('password'),
					'country'      => '1',
                ]);
				
				User::create([
                    'id'         =>  3,
                    'role_id'    =>  3,
                    'name'       =>  'Landowner',
                    'email'      => 'Landowner@mymeter.com',
                    'password'   => Hash::make('password'),
					'country'      => '1',
                ]);
				
				
				
				///Create countries
				DB::table('countries')->truncate();
				Country::create([
                    'id'            => 1,
                    'iso'          => 'US',
                    'name'   => 'UNITED STATES',
                    'nicename'   => 'United States',
                    'iso3'   => 'USA',
                    'numcode'   => '840',
                    'phonecode'   => '1',
					'status'   => 1
                ]);
				Country::create([
                    'id'            => 2,
                    'iso'          => 'CA',
                    'name'   => 'CANADA',
                    'nicename'   => 'Canada',
                    'iso3'   => 'CAN',
                    'numcode'   => '124',
                    'phonecode'   => '1',
					'status'   => 1
                ]);
				Country::create([
                    'id'            => 3,
                    'iso'          => 'GB',
                    'name'   => 'UNITED KINGDOM',
                    'nicename'   => 'United Kingdom',
                    'iso3'   => 'GBR',
                    'numcode'   => '826',
                    'phonecode'   => '44',
					'status'   => 1
                ]);
				Country::create([
                    'id'            => 4,
                    'iso'          => 'AU',
                    'name'   => 'AUSTRALIA',
                    'nicename'   => 'Australia',
                    'iso3'   => 'AUS',
                    'numcode'   => '36',
                    'phonecode'   => '61',
					'status'   => 1
                ]);
				
            }



}
