<?php
namespace App\Controllers;

use App\Models\Cargo_model;  
use Exception;

class Welcome extends BaseController {

	
	public function __construct(){ 
		date_default_timezone_set("America/Asuncion"); 
	 }


	public function index()
	{
		  return view("principal_panel");
	 
	 
	}



	public function publico()
	{
		 return view("home"); 
	}


}
