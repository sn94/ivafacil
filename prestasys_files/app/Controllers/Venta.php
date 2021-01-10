<?php 
namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\pdf_gen\PDF;
use App\Models\Monedas_model; 
use App\Models\Usuario_model;
use App\Models\Ventas_model; 
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Venta extends ResourceController {
 

	protected $modelName = "App\Models\Ventas_model";
	protected $format = "json";
	private $API_MODE= true;
	private $array_response=  [];


	 public function __construct(){
	 
		date_default_timezone_set("America/Asuncion");
		 
	 }

	 

	 
	private function genericResponse($data, $msj, $code)
	{

		if ($code == 200) {
			$this->array_response= array("data" => $data, "code" => $code);
			if ($this->API_MODE)
			return $this->respond( $this->array_response ); //, 500, "No hay nada"
			else return $this->array_response;
		} else {
			$this->array_response=  array("msj" => $msj, "code" => $code);
			if ($this->API_MODE) return $this->respond(  $this->array_response );
			else return $this->array_response;

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





	
	private function isAPI(){

        $request = \Config\Services::request();
       $uri = $request->uri;
        if (sizeof($uri->getSegments()) > 0 &&  $uri->getSegment(1) == "api") {
            return true;
        } return false; 
	}



	private function isAdminView()
	{
		$request = \Config\Services::request();
		$uri = $request->uri;
		return (sizeof($uri->getSegments()) > 0  && $uri->getSegment(1) == "admin");
	}





	
	public function index_se(  $CLIENTE= NULL,  $MES= NULL,   $ANIO=   NULL,  $estado_ = "A")
	{

		$this->API_MODE =  $this->isAPI(); 

		$ventas = (new Ventas_model());

	
		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$cliente=  $CLIENTE;
		$year =   is_null($ANIO) ?  date("Y")  :  $ANIO;
		$month = is_null($MES)?  date("m") :  $MES;
		$estado= "A";

		if ($this->request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$cliente = isset($parametros['cliente'])  ? $parametros['cliente'] : $cliente;
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
			if (array_key_exists("anulados",  $parametros))  $estado =  $parametros['anulados'];
			else 	$estado = $estado_;
		} else {
			$estado = $estado_;
		}
		$lista_co =  $ventas
		->where("codcliente", $cliente)->where("year(fecha)", $year)
		->where("month(fecha)", $month)
		->where("estado", $estado);

		if ($this->API_MODE) {
			$lista_co = $lista_co->get()->getResult();
			return $this->respond(array("data" => $lista_co, "code" => 200));
		} else {
			$lista_pagi = $lista_co->paginate(10);

			//Seleccionar vista
			if( $this->isAdminView())
			{$LaVista=  "admin/clientes/movimientos/grill_ventas";
				if( $estado == "B")  $LaVista=  "admin/clientes/movimientos/grill_ventas_anuladas";
				return view(  $LaVista,
				  ['ventas' =>  $lista_pagi,
				   'ventas_pager' => $lista_co->pager,
				   'year'=> $year,
				   'month'=> $month,
				   'estado'=> $estado_,
				   'CLIENTE'=>  $cliente ]
				);}
			else
			{$LaVista=  "movimientos/informes/grill_ventas";
				if( $estado == "B")  $LaVista=  "movimientos/informes/grill_ventas_anuladas";
				return view(  $LaVista,
				  ['ventas' =>  $lista_pagi,
				   'ventas_pager' => $lista_co->pager,
				   'year'=> $year,
				   'month'=> $month,
				   'estado'=> $estado_]
				);}
		}
		
	}




	public function index(  $MES= NULL,   $ANIO=   NULL,  $estado_ = "A")
	{

		$this->API_MODE =  $this->isAPI();
		$CLIENTE= NULL; 

		$lista_co = [];

		if ($this->API_MODE) {
			$request = \Config\Services::request();
			$sesion = is_null($request->getHeader('Ivasession')) ? "" :  $request->getHeader('Ivasession')->getValue();
			//idS de usuario
			$usunow = (new Usuario_model())->where("session_id", $sesion)->first();
			$ruc =  $usunow->ruc;
			$dv =  $usunow->dv;
			$CLIENTE =  $usunow->regnro; 
		} else {
			$CLIENTE= session("id");
		} 

		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year =   is_null($ANIO) ?  date("Y")  :  $ANIO;
		$month = is_null($MES)?  date("m") :  $MES;
		$estado= "A";

		if ($this->request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
			if (array_key_exists("anulados",  $parametros))  $estado =  $parametros['anulados'];
			else 	$estado = $estado_;
		} else {
			$estado = $estado_;
		}
		$lista_co = $this->index_se( $CLIENTE,  $month, $year, $estado );
		 return  $lista_co;
	}


	public  function  total_mes($cod_cliente, $mes= NULL, $anio= NULL)
	{
		$ventas = (new Ventas_model())->where("codcliente", $cod_cliente);

		$lista_co = [];

		//Segun los parametros
		//Parametros: mes y anio 
		$year = is_null($anio)?  date("Y") :   $anio;
		$month = is_null($mes) ? date("m") :  $mes; 
		$lista_co = $ventas->where("year(fecha)", $year)
		->where("month(fecha)", $month)
		->select('if( sum(iva1) is null, 0,  sum(iva1) ) as iva1, if( sum(iva2) is null, 0,  sum(iva2) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3,
		if( sum(importe1) is null, 0, sum(importe1) ) as total10, 
		if( sum(importe2) is null, 0, sum(importe2) ) as total5,
		if( sum(importe3) is null, 0, sum(importe3) ) as totalexe
		')
		->where("estado", "A")
		->first(); 
		return $lista_co;
	}

	public  function  total_anio($cod_cliente, $anio= NULL)
	{ 
		$ventas = (new Ventas_model())->where("codcliente", $cod_cliente);
		$lista_co = [];
		//Segun los parametros
		//Parametros: mes y anio 
		$year = is_null($anio)?  date("Y") :   $anio; 
		$lista_co = $ventas->where("year(fecha)", $year) 
		->select('if( sum(iva1) is null, 0,  sum(iva1) ) as iva1, if( sum(iva2) is null, 0,  sum(iva2) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3,
		if( sum(importe1) is null, 0, sum(importe1) ) as total10, 
		if( sum(importe2) is null, 0, sum(importe2) ) as total5,
		if( sum(importe3) is null, 0, sum(importe3) ) as totalexe
		')
		->where("estado", "A")
		->first(); 
		return $lista_co;
	}



	public  function  total_($cod_cliente, $mes= NULL, $anio= NULL)
	{
		$request = \Config\Services::request();
		$this->API_MODE =  $this->isAPI();

		$ventas = (new Ventas_model())->where("codcliente", $cod_cliente);

		$lista_co = [];

		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year = is_null($anio)?  date("Y") :   $anio;
		$month = is_null($mes) ? date("m") :  $mes;
		if ($request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
		}
		$lista_co = $ventas->where("year(fecha)", $year)
		->where("month(fecha)", $month)
		->select('if( sum(iva1) is null, 0,  sum(iva1) ) as iva1, if( sum(iva2) is null, 0,  sum(iva2) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3,
		if( sum(importe1) is null, 0, sum(importe1) ) as total10, 
		if( sum(importe2) is null, 0, sum(importe2) ) as total5,
		if( sum(importe3) is null, 0, sum(importe3) ) as totalexe
		')
		->where("estado", "A")
		->first();
		$response =  \Config\Services::response();
		return $lista_co;
	}



	public  function  total()
	{
		$response = \Config\Services::response();
		$this->API_MODE =  $this->isAPI();
		$cod_cliente = $this->getClienteId();
		$compras = (new Ventas_model());

		$lista_co = $this->total_($cod_cliente);
		return $response->setJSON($lista_co);
	}






	public  function  anuladas_($Cliente, $MES = NULL, $ANIO = NULL)
	{
		$ventas = (new Ventas_model());
		$lista_co = $ventas->where("codcliente", $Cliente);

		$year =  is_null($ANIO) ?  date("Y") : $ANIO;

		$lista_co = $lista_co->where("year(fecha)", $year)
		->select('count(regnro) as cantidad, sum(total) as total, sum(iva1+iva2+iva3) as total_iva')
		->where("estado", "B");
		if (!is_null($MES))
		$lista_co =  $lista_co->where("month(fecha)",  $MES);

		$lista_co =  $lista_co->first();
		return $lista_co;
	}




	public  function  anuladas(  $MES= NULL, $ANIO= NULL){
		$response = \Config\Services::response();
		$this->API_MODE=  $this->isAPI();
		$Cliente= $this->getClienteId();
		$respuesta= $this->anuladas_(  $Cliente, $MES, $ANIO );
		return $response->setJSON(   $respuesta);
	}






	private function generar_numero_factura()
	{
		$ultimo_numero = "";
		$cli_cod = session("id");
		$cli_obj = (new Usuario_model())->find($cli_cod);
		if (!is_null($cli_obj)) {
			try{
				$ultimo_numero = $cli_obj->ultimo_nro;

				$firs = (new Ventas_model())->where("codcliente", $cli_cod)->orderBy("created_at", "DESC")->first();
/*
				if (!is_null($firs)   &&   ($firs->factura) != "")
					$ultimo_numero = $firs->factura;
				else $ultimo_numero = $cli_obj->ultimo_nro;
				*/
			 
				$ultimo_numero =  preg_replace( "/-/", "", $ultimo_numero );
			 
				//guardar los primeros 3
				$fv1 =  intval(substr($ultimo_numero,  0, 3));
				$fv2 = intval(substr($ultimo_numero, 3,  3));
				//obtener los ultimos 7
				$ultimo_numero =  substr($ultimo_numero,  6 , 7);
				$ultimo_numero = intval($ultimo_numero)  + 1;
				//rellenar con ceros
				$ultimo_numero = str_pad($ultimo_numero, 7, "0", STR_PAD_LEFT);
				$PAD1= (str_pad($fv1, 3, "0", STR_PAD_LEFT));
				$PAD2= (str_pad($fv2, 3, "0", STR_PAD_LEFT));
			 
				$ultimo_numero =  $PAD1 .$PAD2 . $ultimo_numero;
				 
			}catch( Exception $d ){  $ultimo_numero= ""; }
		} return $ultimo_numero;
	}


	 
	
	public function create(){
		
		$request = \Config\Services::request();
		$db = \Config\Database::connect();


		if ($request->getMethod(true) == "GET") {

			//Obtener ultimo numero de factura
			$ultimo_nro= $this->generar_numero_factura();
			return view("movimientos/comprobantes/venta/create", ['ultimo_numero'=> $ultimo_nro ]);
		}
		//Manejo POST
		$this->API_MODE =  $this->isAPI();
		$usu = new Ventas_model();
		$data = $this->request->getRawInput();
		$fecha_compro=  $data['fecha'];
		$mes_fecha_compro=   date("m",   strtotime( $fecha_compro ) );
		$anio_fecha_anio=   date("Y",   strtotime( $fecha_compro ) );

		if(  (new Cierres())->esta_cerrado(   $mes_fecha_compro,  $anio_fecha_anio  )  )
		return  $this->response->setJSON(  ['msj'=>  "El mes ya esta cerrado",  "code"=>  "500"]);
		//Verificar si el periodo-ejercicio esta cerrado o fuera de rango
	 
		//$Operacion_fecha_invalida= (new Cierres())->fecha_operacion_invalida(  $data['fecha'] );
		//if (  !is_null($Operacion_fecha_invalida))  return $Operacion_fecha_invalida;
		//***** Fin check tiempo*/

		if( $this->API_MODE)  $data['origen']= "A";
		
		//Validar factura normal o anulada?
		$validacion_selectiva= array_key_exists("estado", $data) ? $this->validate("ventas_anuladas")  :  $this->validate("ventas");

		if ($validacion_selectiva) { //Validacion OK

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

			$db->transStart();
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
					$data['estado']=  array_key_exists("estado",  $data) ? $data['estado'] : "A";
					 
				}

				
				//Crear nuevo registro de ejercicio si es necesario
				(new Cierres())->crear_ejercicio();
				$id = $usu->insert($data);
				(new Usuario())->actualizar_ultimo_nro_fv(  $data['factura'] );
				$resu = $this->genericResponse((new Ventas_model())->find($id), null, 200);
				$db->transCommit();
			 
			} catch (Exception $e) {
				$db->transRollback();
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			$db->transComplete();
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) 
				return $this->response->setJSON( ['data'=>  'Guardado', 'code'=>'200'] );
				else 	return $this->response->setJSON( $resu);
				//return redirect()->to(base_url("movimiento/informe_mes"));
			//	else  return view("movimientos/comprobantes/venta/create", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
		return $resultadoValidacion;
		else 
		return $this->response->setJSON( ['msj'=>  $resultadoValidacion['msj'], 'code'=>'500'] );
		// return view("movimientos/comprobantes/f_venta", array("error" => $resultadoValidacion['msj']));
	}





	//ruc=14455&dv=23&codcliente=9&fecha=2020-12-11&moneda=1&factura=0020037892222&total=140000000
	public function update( $cod_venta="" ){
		
		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET") {
			$regis =  (new Ventas_model())->find($cod_venta);
			return view("movimientos/comprobantes/venta/update",  ['venta' => $regis]);
		}
		//Manejo POST

		$this->API_MODE =  $this->isAPI();
		$usu = new Ventas_model();

		$data = $this->request->getRawInput();
		$fecha_compro=  $data['fecha'];
		$mes_fecha_compro=   date("m",   strtotime( $fecha_compro ) );
		$anio_fecha_anio=   date("Y",   strtotime( $fecha_compro ) );

		//Verificar si el periodo-ejercicio esta cerrado o fuera de rango
	 
		if(  (new Cierres())->esta_cerrado($mes_fecha_compro, $anio_fecha_anio)  )
		return  $this->response->setJSON(  ['msj'=>  "El mes ya esta cerrado",  "code"=>  "500"]);
		
		//$Operacion_fecha_invalida= (new Cierres())->fecha_operacion_invalida(  $data['fecha'] );
		//if (  !is_null($Operacion_fecha_invalida))  return $Operacion_fecha_invalida;
		//***** Fin check tiempo*/
		

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
				
				$cod_cliente= $data['codcliente']; 
				$usu->where("codcliente", $cod_cliente)
				->where("regnro", $data['regnro'] )
				->set(  $data)
				->update( );

				 
				$resu = $this->genericResponse( (new Ventas_model())->find( $data['regnro'] ), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {

				return $this->response->setJSON(   $resu );
				//if ($resu['code'] == 200) return redirect()->to(base_url("movimiento/index"));
				//else  return view("movimientos/comprobantes/f_venta", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
		return $resultadoValidacion;
		else 
		return $this->response->setJSON(   $resultadoValidacion );
		// return view("movimientos/comprobantes/f_venta", array("error" => $resultadoValidacion['msj']));
	}




	public function show($id = null)
	{
		$re = (new Ventas_model())->find($id);
		if (is_null($re))
		return $this->genericResponse(null, "Este registro de Venta no existe", 500);
		else
		return $this->genericResponse($re, null, 200);
	}




	
	public function delete( $id = null)
	{
		$this->API_MODE= true;
	 
		$us= (new Ventas_model())->find(  $id);
 
		if (is_null( $us))
		return $this->genericResponse(null, "Venta  no existe",  500);
		else { 
			(new Ventas_model())->where("regnro", $id)->delete( $id );
			return $this->genericResponse("Venta eliminada", null,  200);
		}
	}


	 



	
	public function informes( $tipo){
		try{
			//parametros
		$params=  $this->request->getRawInput();
		$Mes= $params['month']; 
		$Anio=  $params['year'];
		$Cliente=   (  array_key_exists("cliente",  $params) )  ?  $params['cliente']  : session("id");
		$estado= "A";
		if( array_key_exists("anulados",  $params) )  $estado=  $params['anulados'];

		$lista=	(new Ventas_model())
		->where("codcliente",   $Cliente)
		->where("year(fecha)", $Anio)
		->where(" month( fecha) ",  $Mes)
		->where("estado", $estado)
		->get()->getResult(); 

		
		if($tipo== "PDF") return  $this->pdf( $lista, $Cliente);
		if($tipo == "JSON") return $this->response->setJSON(   $lista ); 
		}catch( Exception $e)
		{return $this->response->setJSON(  [] ); }
}



public function pdf( $lista, $CLIENTE= NULL){ 
	 
	 
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
	<th style="text-align:center;">FECHA</th>
	<th style="text-align:center;">COMPROBANTE</th>
	<th style="text-align:right;">EXENTA</th>
	<th style="text-align:right;">5%</th>
	<th style="text-align:right;">10%</th>
	</tr>
	</thead>
	<tbody>
	EOF;

	$t_exenta=0; $t_iva5= 0;  $t_iva10= 0;

	foreach( $lista as $row){
		$fecha= Utilidades::fecha_f( $row->fecha );
		$comprobante= Utilidades::formato_factura( $row->factura );
		$exenta= Utilidades::number_f( $row->importe3 );
		$iva5= Utilidades::number_f( $row->importe2 );
		$iva10= Utilidades::number_f( $row->importe1 );

		$t_exenta+= intval(  $row->importe3);
		$t_iva5+= intval(  $row->importe2);
		$t_iva10+= intval(  $row->importe1);

		$html.="<tr> <td style=\"text-align:center;\">$fecha</td>  <td style=\"text-align:center;\">$comprobante</td> <td style=\"text-align:right;\" >$exenta</td> <td style=\"text-align:right;\">$iva5</td><td style=\"text-align:right;\">$iva10</td> </tr>";
	}
	$t_exenta= Utilidades::number_f( $t_exenta);
	$t_iva5= Utilidades::number_f( $t_iva5);
	$t_iva10= Utilidades::number_f( $t_iva10);

	//totales
	$html.="<tr class=\"footer\"> <td></td> <td style=\"text-align:center;\">Totales</td> <td style=\"text-align:right;\" >$t_exenta</td> <td style=\"text-align:right;\">$t_iva5</td><td style=\"text-align:right;\">$t_iva10</td> </tr>";

	$html.="</tbody> </table> ";
	/********* */

	$tituloDocumento= "IVA_Venta-".date("d")."-".date("m")."-".date("yy");
 
		$pdf = new PDF(); 
		$Cliente=  is_null($CLIENTE) ? session("id")  :  $CLIENTE;
		$RUCCLIENTE= (new Usuario_model())->where("regnro", $Cliente)->first();
		$TITULO_DOCUMENTO=  "RUC:". $RUCCLIENTE->ruc."-".$RUCCLIENTE->dv." (VENTAS)";
		$pdf->prepararPdf("$tituloDocumento.pdf",  $TITULO_DOCUMENTO , ""); 
		$pdf->generarHtml( $html);
		$pdf->generar();
}




     
	  
}
