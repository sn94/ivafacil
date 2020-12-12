<?php 
namespace App\Controllers;

use App\Models\Cargo_model;
use App\Models\Cliente_model;
use App\Models\Compras_model;
use App\Models\Monedas_model;
use App\Models\Retencion_model;
use App\Models\Usuario_model;
use App\Models\Ventas_model;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Movimiento extends Controller {
 
 
	private $API_MODE= true;


	 public function __construct(){
	 
		date_default_timezone_set("America/Asuncion");
		 
	 }

	 

	 
	private function genericResponse($data, $msj, $code)
	{

		$response= \Config\Services::response();

		if ($code == 200) {
			if ($this->API_MODE)
			return $response->setJSON(array("data" => $data, "code" => $code)); //, 404, "No hay nada"
			else return array("data" => $data, "code" => $code);
		} else {
			if ($this->API_MODE) return $response->setJSON(array("msj" => $msj, "code" => $code));
			else return array("msj" => $msj, "code" => $code);
		}
	}


	
 
	public function index(){  
		return view("movimientos/comprobantes/index"  ); 
	}

 
	public function r_cierre(){
		return view("movimientos/cierre");
	}




	/**
	 * 
	 * 
	 * informes
	 */
	public function resumen_anio(){
		return view("movimientos/resumen_anio");
	}


//vista general de informes de movimiento
	public function informe_mes(){
		return view("movimientos/informes/index");
	}
	 
	 
     
	  
}
