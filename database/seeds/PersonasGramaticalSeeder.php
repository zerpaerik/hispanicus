<?php

use Illuminate\Database\Seeder;
use hispanicus\PersonasGramatical;

class PersonasGramaticalSeeder extends Seeder
{
    
  public function run()
  {
		$data = [
			["pronombre" => json_encode(["yo"]), "plural" => false, "formal" => false, "persona_gramatical" => 1],
			["pronombre" => json_encode(["t\u00fa"]), "plural" => false, "formal" => false, "persona_gramatical" => 2],
			["pronombre" => json_encode(["\u00e9l"," ella"]), "plural" => false, "formal" => false, "persona_gramatical" => 3],
			["pronombre" => json_encode(["nosotros"," nosotras"]), "plural" => true, "formal" => false, "persona_gramatical" => 1],
			["pronombre" => json_encode(["vosotros"," vosotras"]), "plural" => true, "formal" => false, "persona_gramatical" => 2],
			["pronombre" => json_encode(["ellos"," ellas"]), "plural" => true, "formal" => false, "persona_gramatical" => 3],
			["pronombre" => json_encode(["usted "]), "plural" => false, "formal" => true, "persona_gramatical" => 3],
			["pronombre" => json_encode(["ustedes"]), "plural" => true, "formal" => true, "persona_gramatical" => 3]
		]; 

      foreach ($data as $key => $value) {
      	PersonasGramatical::create($value);
      }
  }
}
