<?php 
namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\pdf_gen\PDF;
use App\Models\Monedas_model;
use App\Models\Retencion_model;
use App\Models\Usuario_model; 
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Retencion extends ResourceController {
 

	protected $modelName = "App\Models\Retencion_model";
	protected $format = "json";
	private $API_MODE= true;


	 public function __construct(){
	 
		date_default_timezone_set("America/Asuncion");
		 
	 }

	 


	 
	private function isAPI(){

        $request = \Config\Services::request();
       $uri = $request->uri;
        if (sizeof($uri->getSegments()) > 0 &&  $uri->getSegment(1) == "api") {
            return true;
        } return false; 
	}
	

	 
	private function genericResponse($data, $msj, $code)
	{

		if ($code == 200) {
			if ($this->API_MODE)
			return $this->respond(array("data" => $data, "code" => $code)); //, 500, "No hay nada"
			else return array("data" => $data, "code" => $code);
		} else {
			if ($this->API_MODE) return $this->respond(array("msj" => $msj, "code" => $code));
			else return array("msj" => $msj, "code" => $code);
		}
	}


	

	private function getClienteId(){
		$usu= new Usuario_model();
        $request = \Config\Services::request();
        $IVASESSION= is_null($request->getHeader("Ivasession")) ? "" :  $request->getHeader("Ivasession")->getValue();
        $res= $usu->where( "session_id",  $IVASESSION )->first();

		if ($this->isAPI()) {
			if (is_null($res)) {
				return "false";
			} else {
				return $res->regnro;
			}
		}else{      return session("id"); }
		
	}




	
	public function index( ){

		$this->API_MODE=  $this->isAPI();
		$reten= (new Retencion_model());

		$lista_co= [];

		if ($this->API_MODE) {
			$sessionid = is_null($this->request->getHeader('Ivasession')) ? "" : $this->request->getHeader('Ivasession')->getValue();
			if ($sessionid != "") {
				$us= (new Usuario_model())->where("session_id", $sessionid)->first();
				$lista_co = $reten->where("ruc",  $us->ruc)
				->where("dv",  $us->dv)
				->where("codcliente",  $us->regnro);
			}
		}else{
			$lista_co= $reten->where("ruc", session("ruc"))
			->where("dv", session("dv"))
			->where("codcliente", session("id") );
		}
	
		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year = date("Y");
		$month = date("m");

		if ($this->request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset( $parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset(  $parametros['year']) ? $parametros['year'] : $year;
		}
		$lista_co = $lista_co->where("year(fecha)", $year)
		->where("month(fecha)", $month);

		
		if ($this->API_MODE) {
			$lista_co = $lista_co->get()->getResult();
			return $this->respond(array("data" => $lista_co, "code" => 200));
		} else {
			$lista_pagi = $lista_co->paginate(15);
			 
			return view("movimientos/informes/grill_retencion", 
			 ['retencion' =>  $lista_pagi, 'retencion_pager'=> $lista_co->pager,
			 'year'=> $year,  'month'=> $month]);
		}
		 
	}






	public  function  total_($cod_cliente)
	{
		$request = \Config\Services::request();
		$this->API_MODE =  $this->isAPI();
		$reten = (new Retencion_model())->where("codcliente", $cod_cliente);
		$lista_co = [];

		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year = date("Y");
		$month = date("m");
		if ($request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
		}
		$lista_co = $reten->where("year(fecha)", $year)
		->where("month(fecha)", $month)
		->select('if(  sum(importe) is null, 0,   sum(importe)  ) as importe')
		->first();
		return $lista_co;
	}





	public  function  total()
	{
		$response = \Config\Services::response();
		$this->API_MODE =  $this->isAPI();
		$cod_cliente = $this->getClienteId();
		$lista_co =  $this->total_($cod_cliente);
		return $response->setJSON($lista_co);
	}





	
	public  function  total_anio(  $inArray= false){
		$request = \Config\Services::request();
		$this->API_MODE=  $this->isAPI();
	
		$compras= (new Retencion_model());
	
		$lista_co=[];
	
			if ($this->API_MODE) {
				
				$sesion = is_null($request->getHeader('Ivasession')) ? "" :  $request->getHeader('Ivasession')->getValue();
				//idS de usuario
				$usunow= (new Usuario_model())->where( "session_id", $sesion)->first();
				$ruc=  $usunow->ruc;
				$dv=  $usunow->dv;
				$codcliente=  $usunow->regnro;
				//**********/ 
				$lista_co = $compras->where("dv", $dv)
				->where("ruc", $ruc) 
				->where("codcliente", $codcliente)  ;
			} else {
				$lista_co = $compras->where("ruc", session("ruc"))
				->where("dv", session("dv"))
				->where("codcliente", session("id"));
			}
			//Segun los parametros
			//Parametros: mes y anio
			$parametros = [];
			$year = date("Y"); 
	
		 
			if ($request->getMethod(true) == "POST") {
				$parametros = $this->request->getRawInput(); 
				$year =  isset(  $parametros['year']) ? $parametros['year'] : $year;
			}
			$lista_co = $lista_co->where("year(fecha)", $year) 
			->select('if(  sum(importe) is null, 0,   sum(importe)  ) as importe') 
			->first();
			$response=  \Config\Services::response();
			if( $inArray)
			return $lista_co;
			else
			return $response->setJSON(   $lista_co);
	}




   


	public function create(){

		$this->API_MODE =  $this->isAPI();
		
		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET")
		return view("movimientos/comprobantes/retencion/create");
		//Manejo POST
		$usu = new Retencion_model();
		$data = $this->request->getRawInput();

		//Verificar si el periodo-ejercicio esta cerrado o fuera de rango
		$Operacion_fecha_invalida = (new Cierres())->fecha_operacion_invalida($data['fecha']);
		if (!is_null($Operacion_fecha_invalida))  return $Operacion_fecha_invalida;
		//***** Fin check tiempo*/
			
		if( $this->API_MODE)  $data['origen']= "A";


		if ($this->validate('retencion')) { //Validacion OK

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
				//convertir
				if( $moneda != 1){
					$cambio = $data['tcambio'];
					$im1= $data['importe'];
					$data['importe']= intval(  floatval($im1 ) * intval($cambio)  );
				}
					//Crear nuevo registro de ejercicio si es necesario
					(new Cierres())->crear_ejercicio();
					
				$id = $usu->insert($data);
				$resu = $this->genericResponse($this->model->find($id), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) 
				return $this->response->setJSON( ['data'=>  'Guardado', 'code'=>'200'] );
				//return redirect()->to(base_url("movimiento/informe_mes"));
				else  return view("movimientos/comprobantes/retencion", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
		return $resultadoValidacion;
		else  
		return $this->response->setJSON( ['msj'=>  $resultadoValidacion['msj'], 'code'=>'500'] );
		//return view("movimientos/comprobantes/retencion", array("error" => $resultadoValidacion['msj']));

	 
	}
	 


	 




	public function update( $cod_retencion= null){

		$this->API_MODE =  $this->isAPI();
		$request = \Config\Services::request();

		if ($request->getMethod(true) == "GET") {
			$regis =  (new Retencion_model())->find($cod_retencion);
			return view("movimientos/comprobantes/retencion/update",  ['retencion'=>  $regis ] );
		}

		//Manejo POST 
		$data = $this->request->getRawInput();

		//Verificar si el periodo-ejercicio esta cerrado o fuera de rango
	 
		$Operacion_fecha_invalida= (new Cierres())->fecha_operacion_invalida(  $data['fecha'] );
		if (  !is_null($Operacion_fecha_invalida))  return $Operacion_fecha_invalida;
		//***** Fin check tiempo*/

		if( $this->API_MODE)  $data['origen']= "A";


		if ($this->validate('retencion')) { //Validacion OK

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
				//convertir
				if( $moneda != 1){
					$cambio = $data['tcambio'];
					$im1= $data['importe'];
					$data['importe']=  intval($im1 ) * intval($cambio);
				}
				
				$retencionObj= new Retencion_model();
				$retencionObj->set( $data)
				->where("regnro", $data['regnro'])
				->update( );
				 
				$resu = $this->genericResponse($this->model->find($cod_retencion), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				return $this->response->setJSON($resu);
				//if ($resu['code'] == 200) return redirect()->to(base_url("movimiento/index"));
				//else  return view("movimientos/comprobantes/retencion", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
		return $resultadoValidacion;
		else  
		return $this->response->setJSON($resultadoValidacion);
		//return view("movimientos/comprobantes/retencion", array("error" => $resultadoValidacion['msj']));

	 
	}




	
	public function show($id = null)
	{
		$re = (new Retencion_model())->find($id);
		if (is_null($re))
		return $this->genericResponse(null, "Este registro de retención no existe", 500);
		else
		return $this->genericResponse($re, null, 200);
	}




	
	public function delete( $id = null)
	{
		$this->API_MODE= true;
	 
		$us= (new Retencion_model())->find(  $id);
 
		if (is_null( $us))
		return $this->genericResponse(null, "Registro de retención no existe",  500);
		else { 
			(new Retencion_model())->where("regnro", $id)->delete( $id );
			return $this->genericResponse("Registro de retención eliminado", null,  200);
		}
	}


	 








	

	
	public function informes( $tipo){
		try{
			//parametros
		$params=  $this->request->getRawInput();
		$Mes= $params['month']; 
		$Anio=  $params['year'];
		$Cliente= session("id");

		$lista=	(new Retencion_model())
		->where("codcliente",   $Cliente)
		->where("year(fecha)", $Anio)
		->where(" month( fecha) ",  $Mes)->get()->getResult(); 

		
		if($tipo== "PDF") return  $this->pdf( $lista);
		if($tipo == "JSON") return $this->response->setJSON(   $lista ); 
		}catch( Exception $e)
		{return $this->response->setJSON(  [] ); }
}



public function pdf( $lista){ 
	 
	 
	$html=<<<EOF
	<style>
	table.tabla{
		color: #500040;
		font-family: Arial;
		font-size: 8pt;
		border-left: none; 
	}
	
	tr.header th{ 
		font-weight: bold;
		border-bottom: 1px solid black;
	} 
	tr.footer td{  
		font-weight: bold; 
		border-top: 1px solid black;
	} 
	 
	</style>

	<table class="tabla">
	<thead >
	<tr class="header">
	<th style="text-align:center;">COMPROBANTE</th>
	<th style="text-align:right;">IMPORTE</th>
	</tr>
	</thead>
	<tbody>
	EOF;

	$t_importe=0;

	foreach( $lista as $row){
		$comprobante= Utilidades::formato_factura( $row->retencion );
		$importe= Utilidades::number_f( $row->importe ); 

		$t_importe= intval(  $row->importe); 

		$html.="<tr> <td style=\"text-align:center;\">$comprobante</td> <td style=\"text-align:right;\" >$importe</td>    </tr>";
	}
	$t_importe= Utilidades::number_f( $t_importe); 

	//totales
	$html.="<tr class=\"footer\"> <td style=\"text-align:center;\">Totales</td> <td style=\"text-align:right;\" >$t_importe</td>  </tr>";

	$html.="</tbody> </table> ";
	/********* */

	$tituloDocumento= "Retencion-".date("d")."-".date("m")."-".date("yy");
 
		$pdf = new PDF(); 
		$Cliente= session("id");
		$RUCCLIENTE= (new Usuario_model())->where("regnro", $Cliente)->first();
		$TITULO_DOCUMENTO=  "RUC:". $RUCCLIENTE->ruc."-".$RUCCLIENTE->dv." (RETENCIONES)";
		$pdf->prepararPdf("$tituloDocumento.pdf",  $TITULO_DOCUMENTO , ""); 
		$pdf->generarHtml( $html);
		$pdf->generar();
}






	  
}
