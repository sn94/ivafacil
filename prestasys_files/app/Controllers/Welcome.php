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
		if( session("tipo")== "V"){//SOLO VENDEDOR
			$comisiones= $this->Usuario_model->comision_acumulada( session("id"));
			 
			return view("inicio", array("comisiones"=> $comisiones->total ));	
		}else{  return view("inicio");	 }
	 
	 
	}


}
