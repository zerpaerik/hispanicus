<?php

use Illuminate\Database\Seeder;
use hispanicus\PersonasGramatical;

class PersonasGramaticalSeeder extends Seeder
{
    
  public function run()
  {
		$data = [
			json_encode(["yo"]),
			json_encode(["t\u00fa"]),
			json_encode(["\u00e9l"," ella"]),
			json_encode(["nosotros"," nosotras"]),
			json_encode(["vosotros"," vosotras"]),
			json_encode(["ellos"," ellas"]),
			json_encode(["usted "]),
			json_encode(["ustedes"])
		]; 

      foreach ($data as $key) {
      	PersonasGramatical::create($key);
      }
  }
}
