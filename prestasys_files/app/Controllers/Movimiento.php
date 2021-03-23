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


class Movimiento extends BaseController {
 
 
	private $API_MODE= true;


	 public function __construct(){
	 
		date_default_timezone_set("America/Asuncion");
		 
	 }

	 

	  
 

  

//vista general de informes de movimiento
//resumen de compra
//de venta
//de retencion 
//en el mes
	public function informe_mes(  $CLI= NULL,  $MES= NULL, $ANIO= NULL) {

		$mes= is_null( $MES)  ?   date("m")  :   $MES;
		$anio= is_null( $ANIO) ?  date("Y") :  $ANIO;

		$CLIENTE = is_null( $CLI) ?  $this->getClienteId() : $CLI;
		$MODO=  $this->isAdminView() ? "ADMIN"  : "CLIENT";
		return view(
			"movimientos/informes/index",

			['CLIENTE' =>  $CLIENTE, 'year' =>  $anio,   'month' => $mes,  "MODO"=>  $MODO]
		);
	}
	 
	 


 
	
	 


	
	public function r_cierre(){
		return view("movimientos/cierre_mes/index");
	}




	/**
	 * 
	 * 
	 * informes
	 */
	public function resumen_anio(){
		return view("movimientos/resumen_anio");
	}
     
	  
}
