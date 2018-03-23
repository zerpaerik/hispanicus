<?php

use Illuminate\Database\Seeder;
use hispanicus\ConfigRegion;

class RegionSeeder extends Seeder
{


	public function run()
    {
    
		$data = [
				json_encode(["todas"]),
		 		json_encode(["lan"]),
		 		json_encode(["las"]),
		 		json_encode(["esp"]),
		 		json_encode(["lan,esp")],
		 		json_encode(["lan,las")]];
        
        foreach ($data as $key => $value) {
        	ConfigRegion::create([
        		"region" => $value,
        	]);
        }
    }
}
