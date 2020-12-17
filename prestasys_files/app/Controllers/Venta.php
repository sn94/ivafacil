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


 

	
	private function isAPI(){

        $request = \Config\Services::request();
       $uri = $request->uri;
        if (sizeof($uri->getSegments()) > 0 &&  $uri->getSegment(1) == "api") {
            return true;
        } return false; 
	}




	public function index()
	{

		$this->API_MODE =  $this->isAPI();
		$response = \Config\Services::response();



		$ventas = (new Ventas_model());

		$lista_co = [];

		if ($this->API_MODE) {
			$request = \Config\Services::request();
			$sesion = is_null($request->getHeader('Ivasession')) ? "" :  $request->getHeader('Ivasession')->getValue();
			//idS de usuario
			$usunow = (new Usuario_model())->where("session_id", $sesion)->first();
			$ruc =  $usunow->ruc;
			$dv =  $usunow->dv;
			$codcliente =  $usunow->regnro;
			//**********/ 
			$lista_co = $ventas->where("dv", $dv)
			->where("ruc", $ruc)
			->where("codcliente", $codcliente);
		} else {
			$lista_co = $ventas->where("ruc", session("ruc"))
			->where("dv", session("dv"))
			->where("codcliente", session("id"));
		} 

		if ($this->API_MODE) {
			$lista_co = $lista_co->get()->getResult();
			return $this->respond(array("data" => $lista_co, "code" => 200));
		} else {
			$lista_pagi = $lista_co->paginate(15);
			return view("movimientos/informes/grill_ventas",  ['ventas' =>  $lista_pagi, 'ventas_pager' => $lista_co->pager]);
		}
	}


	 
  
	 




	
	public function create(){
		
		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET")
		return view("movimientos/comprobantes/f_venta");
		//Manejo POST

		$this->API_MODE =  $this->isAPI();
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
				//Convertir a guaranies
				if( $moneda != 1){
					$cambio = $data['tcambio'];
					$im1= $data['importe1'];
					$im2= $data['importe2'];
					$im3= $data['importe3'];
					$iva1= $data['iva1'];
					$iva2= $data['iva2'];
					$iva3= $data['iva3'];
					$data['importe1'] =  intval( $cambio) * intval( $im1);
					$data['importe2'] =  intval( $cambio) * intval( $im2);
					$data['importe3'] =  intval( $cambio) * intval( $im3);
					$data['iva1'] =  intval( $cambio) * intval( $iva1);
					$data['iva2'] =  intval( $cambio) * intval( $iva2);
					$data['iva3'] =  intval( $cambio) * intval( $iva3);
					$data["total"] =  $data['importe1']  + $data['importe2']  + $data['importe3']  ;
					 
				}

				$id = $usu->insert($data);
				$resu = $this->genericResponse((new Ventas_model())->find($id), null, 200);
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





	//ruc=14455&dv=23&codcliente=9&fecha=2020-12-11&moneda=1&factura=0020037892222&total=140000000
	public function update( $cod_venta="" ){
		
		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET")
		return view("movimientos/comprobantes/f_compra");
		//Manejo POST

		$this->API_MODE =  $this->isAPI();
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

				$ruc= $data['ruc'];
				$dv= $data['dv'];
				$cod_cliente= $data['codcliente'];
 
				//$usu->where("ruc", $ruc)
				//->where("dv", $dv)
				//->where("codcliente", $cod_cliente)
				$usu->set(  $data)
				->update( $cod_venta);

				 
				$resu = $this->genericResponse( (new Ventas_model())->find($cod_venta), null, 200);
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




	public function show($id = null)
	{
		$re = (new Ventas_model())->find($id);
		if (is_null($re))
		return $this->genericResponse(null, "Este registro de Venta no existe", 404);
		else
		return $this->genericResponse($re, null, 200);
	}




	
	public function delete( $id = null)
	{
		$this->API_MODE= $this->isAPI();
	 
		$us= (new Ventas_model())->find(  $id);
 
		if (is_null( $us))
		return $this->genericResponse(null, "Venta  no existe",  404);
		else { 
			(new Ventas_model())->where("regnro", $id)->delete( $id );
			return $this->genericResponse("Venta eliminada", null,  200);
		}
	}


	 






     
	  
}
