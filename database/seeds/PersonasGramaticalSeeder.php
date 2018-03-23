<?php

use Illuminate\Database\Seeder;
use hispanicus\PersonasGramatical;

class PersonasGramaticalSeeder extends Seeder
{
    
  public function run()
  {
		$data = [

			["pronombre" => json_encode(['yo']),
			'persona_gramatical' => 1, "region_id" => 1, "plural" => false, "formal" => false],
			
			["pronombre" => json_encode(['el','ella','usted']),
			'persona_gramatical' => 3, "region_id" => 1, "plural" => false, "formal" => false],
			
			["pronombre" => json_encode(['ustedes']),
			'persona_gramatical' => 2, "region_id" => 1, "plural" => true, "formal" => true],

			["pronombre" => json_encode(['nosotros','nosotras']),
			'persona_gramatical' => 1, "region_id" => 1, "plural" => true, "formal" => false],
			
			["pronombre" => json_encode(['vos']),
			'persona_gramatical' => 2, "region_id" => 3, "plural" => false, "formal" => false],

			["pronombre" => json_encode(['vosotros','vosotras']),
			'persona_gramatical' => 2, "region_id" => 4, "plural" => true, "formal" => false],		

			["pronombre" => json_encode(['tu']),
			'persona_gramatical' => 2, "region_id" => 5, "plural" => false, "formal" => false],

			["pronombre" => json_encode(['ellos','ellas']),
			'persona_gramatical' => 3, "region_id" => 1, "plural" => true, "formal" => false],
		]; 

      foreach ($data as $key) {
      	PersonasGramatical::create($key);
      }
  }
}
