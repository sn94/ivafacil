<?php
 namespace App\Controllers;
 
use App\Models\Ciudades_model;
use App\Models\Monedas_model;
use App\Models\Planes_model;
use App\Models\Rubro_model;   
 

class Auxiliar extends BaseController {
 
	 public function __construct(){
	 
	 
		date_default_timezone_set("America/Asuncion");
	  
	 }

	public function rubros()
	{
	
		$rubros= (new  Rubro_model)->findAll();
	/*	$this->setResponseFormat( "json");
		$this->respond( $rubros, 200, null);*/
		return $this->response->setJSON($rubros);
	 
	}
 

	public function ciudades()
	{
	
		$ciu= (new  Ciudades_model)->findAll(); 
		return $this->response->setJSON($ciu);
	 
	}
	 


	public function planes()
	{
	
		$ciu= (new  Planes_model)->findAll(); 
		return $this->response->setJSON($ciu);
	 
	}

	
	public function monedas()
	{
	
		$ciu= (new  Monedas_model)->findAll(); 
		return $this->response->setJSON($ciu);
	 
	}


}
