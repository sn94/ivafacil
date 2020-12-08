<?php 
namespace App\Controllers;

use App\Models\Cargo_model;
use App\Models\Cliente_model;
use App\Models\Compras_model;
use App\Models\Monedas_model;
use App\Models\Usuario_model;
use CodeIgniter\HTTP\Request;
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Movimiento extends ResourceController {
 

	protected $modelName = "App\Models\Compras_model";
	protected $format = "json";
	private $API_MODE= true;


	 public function __construct(){
	 
		date_default_timezone_set("America/Asuncion");
		 
	 }

	 

	 
	private function genericResponse($data, $msj, $code)
	{

		if ($code == 200) {
			if ($this->API_MODE)
			return $this->respond(array("data" => $data, "code" => $code)); //, 404, "No hay nada"
			else return array("data" => $data, "code" => $code);
		} else {
			if ($this->API_MODE) return $this->respond(array("msj" => $msj, "code" => $code));
			else return array("msj" => $msj, "code" => $code);
		}
	}


	
 
	public function index(){  
		return view("movimientos/comprobantes/index"  ); 
	}


	public function r_f_compra($api_mode = "S")
	{
		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET")
		return view("movimientos/comprobantes/f_compra");
		//Manejo POST

		$this->API_MODE =  $api_mode == "S";
		$usu = new Compras_model();

		$data = $this->request->getRawInput();
		if ($this->validate('compras')) { //Validacion OK

			$cod_cliente =  $data["codcliente"];
			if (!$cod_cliente && !is_null((new Usuario_model())->find($cod_cliente))) {
				return  $this->genericResponse(null,  "Codigo de cliente: $cod_cliente no existe", 500);
			}

			$moneda =  $data["moneda"] ;
			if (!$moneda && !is_null((new Monedas_model())->find($moneda))) {
				return $this->genericResponse(null,  "Codigo de moneda: $moneda no existe", 500);
			}
			 
			if( $moneda != "1" && (  !isset( $data['tcambio'] )  ||  $data['tcambio']=="")   ){
				return $this->genericResponse(null,  "Indique el monto para cambio de moneda", 500);
			}
			$resu = []; //Resultado de la operacion
			try {
				if ($this->API_MODE)  $data['origen'] = "A"; //ORIGEN Aplicacion
				$id = $usu->insert($data);
				$resu = $this->genericResponse($this->model->find($id), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) return redirect()->to(base_url("movimiento/index"));
				else  return view("movimientos/comprobantes/f_compra", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
		return $resultadoValidacion;
		else  return view("movimientos/comprobantes/f_compra", array("error" => $resultadoValidacion['msj']));
	}



	public function r_f_venta(){
		return view("movimientos/comprobantes/f_venta");
	}
	public function r_retencion(){
		return view("movimientos/comprobantes/retencion");
	}
	public function r_cierre(){
		return view("movimientos/cierre");
	}

	public function resumen_anio(){
		return view("movimientos/resumen_anio");
	}

	public function informe_mes(){
		return view("movimientos/informes/index");
	}


	 
     
	  
}
