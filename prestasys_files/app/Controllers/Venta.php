<?php 
namespace App\Controllers;
 
use App\Models\Monedas_model; 
use App\Models\Usuario_model;
use App\Models\Ventas_model; 
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Venta extends ResourceController {
 

	protected $modelName = "App\Models\Ventas_model";
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


 

	public function index( $api_mode= "S"){

		$this->API_MODE=  $api_mode == "S";
		$ventas= (new Ventas_model());
		$lista_co= $ventas->where("ruc", session("ruc"))
		->where("dv", session("dv"))
		->where("codcliente", session("id") );

		if ($this->API_MODE) {
			$lista_co = $lista_co->get()->getResult();
			return $this->respond(array("data" => $lista_co, "code" => 200));
		} else {
			$lista_pagi = $lista_co->paginate(15);
			 
			return view("movimientos/informes/grill_ventas",  ['ventas' =>  $lista_pagi, 'ventas_pager'=> $lista_co->pager]);
		}
		 
	}


	
 

	public function create($api_mode = "S")
	{
		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET")
		return view("movimientos/comprobantes/f_venta");
		//Manejo POST

		$this->API_MODE =  $api_mode == "S";
		
		$usu = new Ventas_model();

		$data = $this->request->getRawInput();

		if( $this->API_MODE)  $data['origen']= "A";


		if ($this->validate('ventas')) { //Validacion OK

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
				else  return view("movimientos/comprobantes/f_venta", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
		return $resultadoValidacion;
		else  return view("movimientos/comprobantes/f_venta", array("error" => $resultadoValidacion['msj']));
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
