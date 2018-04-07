<?php

use Illuminate\Database\Seeder;
use hispanicus\PersonasGramatical;

class PersonasGramaticalSeeder extends Seeder
{
    
  public function run()
  {

  	$yo = explode(",", utf8_encode("yo"));
    $vos = explode(",", utf8_encode("vos"));
  	$tu = explode(",", utf8_encode("tú"));
  	$ella = explode(",", utf8_encode("él,ella"));
  	$nos = explode(",", utf8_encode("nosotros,nosotras"));
  	$voso = explode(",", utf8_encode("vosotros,vosotras"));
  	$ell  = explode(",", utf8_encode("ellos,ellas"));
  	$usted  = explode(",", utf8_encode("usted"));
  	$ustds  = explode(",", utf8_encode("ustedes"));

		$data = [
			["pronombre" => json_encode($yo), "plural" => false, "formal" => false,   "persona_gramatical" => 1],
      ["pronombre" => json_encode($vos), "plural" => false, "formal" => false,  "persona_gramatical" => 1],
			["pronombre" => json_encode($tu), "plural" => false, "formal" => false,   "persona_gramatical" => 2],
			["pronombre" => json_encode($ella), "plural" => false, "formal" => false, "persona_gramatical" => 3],
			["pronombre" => json_encode($nos), "plural" => true, "formal" => false,   "persona_gramatical" => 1],
			["pronombre" => json_encode($voso), "plural" => true, "formal" => false,  "persona_gramatical" => 2],
			["pronombre" => json_encode($ell), "plural" => true, "formal" => false,   "persona_gramatical" => 3],
			["pronombre" => json_encode($usted), "plural" => false, "formal" => true, "persona_gramatical" => 3],
			["pronombre" => json_encode($ustds), "plural" => true, "formal" => true,  "persona_gramatical" => 3]
		]; 

      foreach ($data as $key => $value) {
      	PersonasGramatical::create($value);
      }
  }
}
