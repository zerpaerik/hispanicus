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
    $vos2 = explode(",", utf8_encode('<b class="rc">vos</b>'));
    $ustds2  = explode(",", utf8_encode('<b class="rc">ustedes</b>'));
    $not_yo = explode(",", utf8_encode("*yo"));
    $not_vos = explode(",", utf8_encode("*vos"));
    $not_tu = explode(",", utf8_encode("*tú"));
    $not_ella = explode(",", utf8_encode("*él,ella"));
    $not_nos = explode(",", utf8_encode("*nosotros,nosotras"));
    $not_voso = explode(",", utf8_encode("*vosotros,vosotras"));
    $not_ell  = explode(",", utf8_encode("*ellos,ellas"));
    $not_usted  = explode(",", utf8_encode("*usted"));
    $not_ustds  = explode(",", utf8_encode("*ustedes"));
    $not_vos2 = explode(",", utf8_encode('<b class="rc">*vos</b>'));
    $not_ustds2  = explode(",", utf8_encode('<b class="rc">*ustedes</b>'));

		$data = [
			["pronombre" => json_encode($yo),         "plural" => false, "formal" => false,    "persona_gramatical" => 1],
      ["pronombre" => json_encode($vos),        "plural" => false, "formal" => false,   "persona_gramatical" => 2],
			["pronombre" => json_encode($tu),         "plural" => false, "formal" => false,    "persona_gramatical" => 2],
			["pronombre" => json_encode($ella),       "plural" => false, "formal" => false,  "persona_gramatical" => 3],
			["pronombre" => json_encode($nos),        "plural" => true, "formal" => false,    "persona_gramatical" => 1],
			["pronombre" => json_encode($voso),       "plural" => true, "formal" => false,   "persona_gramatical" => 2],
			["pronombre" => json_encode($ell),        "plural" => true, "formal" => false,    "persona_gramatical" => 3],
			["pronombre" => json_encode($usted),      "plural" => false, "formal" => true,  "persona_gramatical" => 3],
			["pronombre" => json_encode($ustds),      "plural" => true, "formal" => true,   "persona_gramatical" => 3],
      ["pronombre" => json_encode($ustds),      "plural" => true, "formal" => false,  "persona_gramatical" => 2],
      ["pronombre" => json_encode($vos2),       "plural" => false,  "formal" => false,   "persona_gramatical" => 2],
      ["pronombre" => json_encode($ustds2),     "plural" => true, "formal" => false,  "persona_gramatical" => 2],
      ["pronombre" => json_encode($not_yo),     "plural" => false, "formal" => false,    "persona_gramatical" => 1],
      ["pronombre" => json_encode($not_vos),    "plural" => false, "formal" => false,   "persona_gramatical" => 2],
      ["pronombre" => json_encode($not_tu),     "plural" => false, "formal" => false,    "persona_gramatical" => 2],
      ["pronombre" => json_encode($not_ella),   "plural" => false, "formal" => false,  "persona_gramatical" => 3],
      ["pronombre" => json_encode($not_nos),    "plural" => true, "formal" => false,    "persona_gramatical" => 1],
      ["pronombre" => json_encode($not_voso),   "plural" => true, "formal" => false,   "persona_gramatical" => 2],
      ["pronombre" => json_encode($not_ell),    "plural" => true, "formal" => false,    "persona_gramatical" => 3],
      ["pronombre" => json_encode($not_usted),  "plural" => false, "formal" => true,  "persona_gramatical" => 3],
      ["pronombre" => json_encode($not_ustds),  "plural" => true, "formal" => true,   "persona_gramatical" => 3],
      ["pronombre" => json_encode($not_ustds),  "plural" => true, "formal" => false,  "persona_gramatical" => 2],
      ["pronombre" => json_encode($not_vos2),   "plural" => false,  "formal" => false,   "persona_gramatical" => 2],
      ["pronombre" => json_encode($not_ustds2), "plural" => true, "formal" => false,  "persona_gramatical" => 2]
		]; 

      foreach ($data as $key => $value) {
      	PersonasGramatical::create($value);
      }
  }
}