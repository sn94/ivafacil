<?php

namespace App\Controllers;

use App\Helpers\Facturacion;
use App\Helpers\Utilidades;
use App\Libraries\pdf_gen\PDF;
use App\Models\Monedas_model;
use App\Models\Usuario_model;
use App\Models\Ventas_model;
use CodeIgniter\Database\Database;
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Venta extends ResourceController
{


	protected $modelName = "App\Models\Ventas_model";
	protected $format = "json";
	private $array_response =  [];


	public function __construct()
	{

		date_default_timezone_set("America/Asuncion");
	}




	private function genericResponse($data, $msj, $code)
	{

		if ($code == 200) {
			$this->array_response = array("data" => $data, "code" => $code);
			if ($this->isAPI())
				return $this->respond($this->array_response); //, 500, "No hay nada"
			else return $this->array_response;
		} else {
			$this->array_response =  array("msj" => $msj, "code" => $code);
			if ($this->isAPI()) return $this->respond($this->array_response);
			else return $this->array_response;
		}
	}



	private function getClienteId()
	{
		$usu = new Usuario_model();
		$request = \Config\Services::request();
		$IVASESSION = is_null($request->getHeader("Ivasession")) ? "" :  $request->getHeader("Ivasession")->getValue();
		$res = $usu->where("session_id",  $IVASESSION)->first();

		if ($this->isAPI()) {
			if (is_null($res)) {
				return "false";
			} else {
				return $res->regnro;
			}
		} else {
			return session("id");
		}
	}






	private function isAPI()
	{

		$request = \Config\Services::request();
		$uri = $request->uri;
		if (sizeof($uri->getSegments()) > 0 &&  $uri->getSegment(1) == "api") {
			return true;
		}
		return false;
	}



	private function isAdminView()
	{
		$request = \Config\Services::request();
		$uri = $request->uri;
		return (sizeof($uri->getSegments()) > 0  && $uri->getSegment(1) == "admin");
	}






	public function index_se($CLIENTE,  $MES,   $ANIO,  $estado_ = "A")
	{
		//Segun los parametros
		//Parametros: mes y anio

		$cliente =  $CLIENTE;
		$year =    $ANIO;
		$month =   $MES;
		$estado =  $estado_;
		$ventas = (new Ventas_model());
		$ventas = $ventas
			->where("codcliente", $cliente)->where("year(fecha)", $year)
			->where("month(fecha)", $month)
			->where("estado", $estado);

		$TotalRegistros =   $ventas->countAllResults();

		if ($this->isAPI()) {
			$ventas = $ventas
				->where("codcliente", $cliente)->where("year(fecha)", $year)
				->where("month(fecha)", $month)
				->where("estado", $estado)
				->get()->getResult();
			return $this->respond(array("data" => $ventas, "code" => 200));
		} else {

			$numero_filas = 10;
			$pagina =  isset($_GET['page']) ?  $_GET['page']  : 0;

			//filtro
			$lista_pagi =  $ventas
				->where("codcliente", $cliente)->where("year(fecha)", $year)
				->where("month(fecha)", $month)
				->where("estado", $estado)
				->limit($numero_filas,  $pagina)->get()->getResult();

			$ViewParams =   [
				'ventas' =>  $lista_pagi,
				//'ventas_pager' => $lista_co->pager,
				'year' => $year,
				'month' => $month,
				'estado' => $estado_,
				'TotalRegistros' =>  $TotalRegistros,
				'CLIENTE' =>  $cliente,
				'EVENT_HANDLER' => "_informe_ventas(event)",
				'MODO' =>  $this->isAdminView() ? "ADMIN" :  "CLIENT"
			];

			//Seleccionar vista
			return view("movimientos/informes/grill_ventas",  $ViewParams);
			/*	if( $this->isAdminView())
			{$LaVista=  "admin/clientes/movimientos/grill_ventas";
				if( $estado == "B")  $LaVista=  "admin/clientes/movimientos/grill_ventas_anuladas";

				return view(  $LaVista, array_merge ( $ViewParams,  ['Link'=>  base_url("admin/clientes/ventas/$cliente/$month/$year")]));}
			else
			{
				$LaVista=  "movimientos/informes/grill_ventas";
				if( $estado == "B")  $LaVista=  "movimientos/informes/grill_ventas_anuladas";
				return view(  $LaVista, 
				 array_merge($ViewParams,   ['Link'=>  base_url("admin/clientes/ventas/$cliente/$month/$year")])  );
			}*/
		}
	}




	public function index($MES = NULL,   $ANIO =   NULL,  $estado_ = "A")
	{


		$CLIENTE = $this->getClienteId();

		$lista_co = [];


		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year =   is_null($ANIO) ?  date("Y")  :  $ANIO;
		$month = is_null($MES) ?  date("m") :  $MES;
		$estado =  $estado_;



		if ($this->request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
			if (array_key_exists("anulados",  $parametros))  $estado =  $parametros['anulados'];
			else 	$estado = $estado_;
		} else {
			$estado = $estado_;
		}

		$lista_co = $this->index_se($CLIENTE,  $month, $year, $estado);
		return  $lista_co;
	}


	public  function  total_mes($cod_cliente, $mes = NULL, $anio = NULL)
	{
		$ventas = (new Ventas_model())->where("codcliente", $cod_cliente);

		$lista_co = [];

		//Segun los parametros
		//Parametros: mes y anio 
		$year = is_null($anio) ?  date("Y") :   $anio;
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

	public  function  total_anio($cod_cliente, $anio = NULL)
	{
		$ventas = (new Ventas_model())->where("codcliente", $cod_cliente);
		$lista_co = [];
		//Segun los parametros
		//Parametros: mes y anio 
		$year = is_null($anio) ?  date("Y") :   $anio;
		$lista_co = $ventas->where("year(fecha)", $year)
			->select('if( sum(iva1) is null, 0,  ROUND(sum(iva1)) ) as iva1, if( sum(iva2) is null, 0,  ROUND(sum(iva2)) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3,
		if( sum(importe1) is null, 0, sum(importe1) ) as total10, 
		if( sum(importe2) is null, 0, sum(importe2) ) as total5,
		if( sum(importe3) is null, 0, sum(importe3) ) as totalexe
		')
			->where("estado", "A")
			->first();
		return $lista_co;
	}



	public  function  total_($cod_cliente, $mes, $anio)
	{
		$request = \Config\Services::request();

		$ventas = (new Ventas_model())->where("codcliente", $cod_cliente);

		$lista_co = [];

		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year = is_null($anio) ?  date("Y") :   $anio;
		$month = is_null($mes) ? date("m") :  $mes;
		if ($request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
		}
		$lista_co = $ventas->where("year(fecha)", $year)
			->where("month(fecha)", $month)
			->select('if( sum(iva1) is null, 0,  round(sum(iva1)) ) as iva1, if( sum(iva2) is null, 0,  round(sum(iva2)) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3,
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
		$cod_cliente = $this->getClienteId();
		$compras = (new Ventas_model());

		$MES = date("m");
		$ANIO = date("Y");
		$lista_co = $this->total_($cod_cliente,  $MES, $ANIO);
		return $response->setJSON($lista_co);
	}






	public  function  anuladas_($Cliente, $MES = NULL, $ANIO = NULL)
	{
		$ventas = (new Ventas_model());
		$lista_co = $ventas->where("codcliente", $Cliente);

		$year =  is_null($ANIO) ?  date("Y") : $ANIO;

		$lista_co = $lista_co->where("year(fecha)", $year)
			->select('count(regnro) as cantidad, if(sum(total) is null, 0, sum(total)) as total, if(sum(iva1+iva2 ) is null, 0, sum(iva1+iva2 )) as total_iva')
			->where("estado", "B");
		if (!is_null($MES))
			$lista_co =  $lista_co->where("month(fecha)",  $MES);

		$lista_co =  $lista_co->first();
		return $lista_co;
	}




	public  function  anuladas($MES = NULL, $ANIO = NULL)
	{
		$response = \Config\Services::response();
		$Cliente = $this->getClienteId();
		$respuesta = $this->anuladas_($Cliente, $MES, $ANIO);
		return $response->setJSON($respuesta);
	}






	public function generar_numero_factura()
	{
		$ultimo_numero = "";
		$cli_cod = $this->getClienteId();
		$cli_obj = (new Usuario_model())->find($cli_cod);
		if (!is_null($cli_obj)) {
			try {
				$ultimo_numero = $cli_obj->ultimo_nro;

				$firs = (new Ventas_model())->where("codcliente", $cli_cod)->orderBy("created_at", "DESC")->first();
				/*
				if (!is_null($firs)   &&   ($firs->factura) != "")
					$ultimo_numero = $firs->factura;
				else $ultimo_numero = $cli_obj->ultimo_nro;
				*/

				$ultimo_numero =  preg_replace("/-/", "", $ultimo_numero);

				//guardar los primeros 3
				$fv1 =  intval(substr($ultimo_numero,  0, 3));
				$fv2 = intval(substr($ultimo_numero, 3,  3));
				//obtener los ultimos 7
				$ultimo_numero =  substr($ultimo_numero,  6, 7);
				$ultimo_numero = intval($ultimo_numero)  + 1;
				//rellenar con ceros
				$ultimo_numero = str_pad($ultimo_numero, 7, "0", STR_PAD_LEFT);
				$PAD1 = (str_pad($fv1, 3, "0", STR_PAD_LEFT));
				$PAD2 = (str_pad($fv2, 3, "0", STR_PAD_LEFT));

				$ultimo_numero =  $PAD1 . $PAD2 . $ultimo_numero;
			} catch (Exception $d) {
				$ultimo_numero = "";
			}
		}

		if ($this->isAPI())

			return $this->response->setJSON(['data' =>   $ultimo_numero,  "code" => "200"]);
		else
			return $ultimo_numero;
	}



	private function operacion_habilitada($ClienteCOD, $fecha_compro)
	{
		$mes_fecha_compro =   date("m",   strtotime($fecha_compro));
		$anio_fecha_anio =   date("Y",   strtotime($fecha_compro));

		//Al dia
		$habilitado =  (new Usuario())->servicio_habilitado($ClienteCOD);
		if (array_key_exists("msj",  $habilitado))
			return $this->response->setJSON(['msj' =>  $habilitado['msj'],  'code' => "500"]);

		elseif ((new Cierres())->esta_cerrado($mes_fecha_compro,  $anio_fecha_anio))
			return  $this->response->setJSON(['msj' =>  "El mes ya esta cerrado",  "code" =>  "500"]);
		else  return NULL;
	}








	public function create()
	{

		$request = \Config\Services::request();
		$db = \Config\Database::connect();


		$ClienteCOD = $this->getClienteId();
		if ($request->getMethod(true) == "GET") {

			//Obtener ultimo numero de factura
			$ultimo_nro = $this->generar_numero_factura();
			//servicio habilitado
			$habilitado =  (new Usuario())->servicio_habilitado($ClienteCOD);
			if (array_key_exists("msj",  $habilitado))
				return view("movimientos/comprobantes/venta/create", ['ultimo_numero' => $ultimo_nro, 'error' => $habilitado['msj']]);

			//Verificar timbrado
			$existe_timbrado = ((new Usuario_model())->find($ClienteCOD)->timbrado)  !=  "";
			if (!$existe_timbrado) 	return view("movimientos/comprobantes/venta/create", ['ultimo_numero' => $ultimo_nro, 'error' => "Recuerde registrar el número de timbrado en *MIS DATOS*"]);
			return view("movimientos/comprobantes/venta/create", ['ultimo_numero' => $ultimo_nro]);
		}


		//Manejo POST 
		$usu = new Ventas_model();
		$data = $this->request->getRawInput();
		$fecha_compro =  $data['fecha'];
		//Operacion habilitada
		$oper_habilitada =   $this->operacion_habilitada($ClienteCOD,  $fecha_compro);
		if (!is_null($oper_habilitada))  return  $oper_habilitada;

		//Timbrado registrado?
		$existe_timbrado = isset($data['timbrado']) ? ($data['timbrado'] == "" ? false : true) : false;
		$estado_anu = isset($data['estado']) ? ($data['estado'] == "B" ? true : false) : false;
		if ($estado_anu && !$existe_timbrado)  return $this->genericResponse(null, "Debe registrar un número de timbrado", 500);


		//inferir otros datos del cliente
		$ModeloCliente =  (new Usuario_model())->find($ClienteCOD);
		$data["codcliente"] = $ClienteCOD;
		$data['ruc'] =  $ModeloCliente->ruc;
		$data['dv'] = $ModeloCliente->dv;
		$data['origen'] =   $this->isAPI() ?  "A"   : "W";
		 


		//Validar factura normal o anulada?
		$validacion_selectiva = (array_key_exists("estado", $data)  &&  $data['estado']=="B") 
		? $this->validate("ventas_anuladas")  : 
		 $this->validate("ventas");

		if ($validacion_selectiva) { //Validacion OK

			$moneda =  $data["moneda"];
			if (!$moneda && !is_null((new Monedas_model())->find($moneda))) {
				return $this->genericResponse(null,  "Codigo de moneda: $moneda no existe", 500);
			}

			if ($moneda != "1" && (!isset($data['tcambio'])  ||  $data['tcambio'] == "")) {
				return $this->genericResponse(null,  "Indique el monto para cambio de moneda", 500);
			}
			$resu = []; //Resultado de la operacion

			$db->transStart();
			try {
				$data['origen'] = ($this->isAPI()) ? "A" : "W"; //ORIGEN Aplicacion
				$data['iva_incluido']= "S";/**Unico modo hasta ahora */
				//calculo interno del iva
				$data = Facturacion::calcular_iva($data);
				$data['estado'] =  array_key_exists("estado",  $data) ? $data['estado'] : "A";


				//Convertir a guaranies
				if ($moneda != 1) {
					$data = Facturacion::convertir_a_moneda_nacional($data);
				}

				//Crear nuevo registro de ejercicio si es necesario
				(new Cierres())->crear_ejercicio();
				$id = $usu->insert($data);
				(new Usuario())->actualizar_ultimo_nro_fv($data['factura']);
				$resu = $this->genericResponse((new Ventas_model())->find($id), null, 200);
				$db->transCommit();
			} catch (Exception $e) {
				$db->transRollback();
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			$db->transComplete();
			//Evaluar resultado
			if ($this->isAPI()) return  $resu;
			else {
				if ($resu['code'] == 200)
					return $this->response->setJSON(['data' =>  'Guardado', 'code' => '200']);
				else 	return $this->response->setJSON($resu);
				//return redirect()->to(base_url("movimiento/informe_mes"));
				//	else  return view("movimientos/comprobantes/venta/create", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->isAPI())
			return $resultadoValidacion;
		else
			return $this->response->setJSON(['msj' =>  $resultadoValidacion['msj'], 'code' => '500']);
		// return view("movimientos/comprobantes/f_venta", array("error" => $resultadoValidacion['msj']));
	}





	//ruc=14455&dv=23&codcliente=9&fecha=2020-12-11&moneda=1&factura=0020037892222&total=140000000
	public function update($cod_venta = NULL)
	{

		$request = \Config\Services::request();
		$ClienteCOD =  $this->getClienteId();

		if ($request->getMethod(true) == "GET") {
			$regis =  (new Ventas_model())->find($cod_venta);

			//servicio habilitado
			$habilitado =  (new Usuario())->servicio_habilitado($ClienteCOD);
			if (array_key_exists("msj",  $habilitado))
				return view("movimientos/comprobantes/venta/update",  ['venta' => $regis,   "error" => $habilitado['msj']]);
			else
				return view("movimientos/comprobantes/venta/update",  ['venta' => $regis]);
		}
		//Manejo POST

		$usu = new Ventas_model();

		$data = $this->request->getRawInput();
		//inferir otros datos del cliente
		$ModeloCliente =  (new Usuario_model())->find($ClienteCOD);
		$data["codcliente"] = $ClienteCOD;
		$data['ruc'] =  $ModeloCliente->ruc;
		$data['dv'] = $ModeloCliente->dv;
		$data['origen'] =   $this->isAPI() ?  "A"   : "W";
		
		//Determinar el Estado 
		$ElEstadoAnulado= isset( $data['estado']) ? ( $data['estado']=="B" ? true : false ) :  false;
		if( $ElEstadoAnulado)  return $this->anular( $data['regnro'] );

		//Modelo de ventas
		$modeloVentas= (new Ventas_model())->find(   $data['regnro'] );

		$fecha_compro =  isset($data['fecha']) ?  $data['fecha']  :   $modeloVentas->fecha  ;

		//Operacion habilitada
		$oper_habilitada =   $this->operacion_habilitada($ClienteCOD,  $fecha_compro);
		if (!is_null($oper_habilitada))  return  $oper_habilitada;
		


		if ($this->validate('ventas_update')) { //Validacion OK

			$moneda = "";
			$Moneda_definida = array_key_exists("moneda",  $data);

			if (array_key_exists("moneda",  $data)) {
				$moneda =  $data["moneda"];
				if (!$moneda && !is_null((new Monedas_model())->find($moneda))) {
					return $this->genericResponse(null,  "Codigo de moneda: $moneda no existe", 500);
				}

				if ($moneda != "1" && (!isset($data['tcambio'])  ||  $data['tcambio'] == "")) {
					return $this->genericResponse(null,  "Indique el monto para cambio de moneda", 500);
				}
			}

			$resu = []; //Resultado de la operacion
			 

			try {

				//calculo interno del iva
				$data =  Facturacion::calcular_iva($data);
				$data['estado'] =  array_key_exists("estado",  $data) ? $data['estado'] : "A";

				//Convertir a guaranies
				if ($Moneda_definida  &&  $moneda != 1) {
					$data =  Facturacion::convertir_a_moneda_nacional($data);
				}

				$usu->where("codcliente", $ClienteCOD)
					->where("regnro", $data['regnro'])
					->set($data)
					->update();
				$resu = $this->genericResponse((new Ventas_model())->find($data['regnro']), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->isAPI()) return  $resu;
			else {

				return $this->response->setJSON($resu);
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->isAPI())
			return $resultadoValidacion;
		else
			return $this->response->setJSON($resultadoValidacion);
		// return view("movimientos/comprobantes/f_venta", array("error" => $resultadoValidacion['msj']));
	}


	public function anular($cod_venta = NULL)
	{
		try {
			(new Ventas_model())->where("codcliente", $this->getClienteId())
				->where("regnro",   $cod_venta)
				->set(['estado' =>  'B'])
				->update();
			return $this->response->setJSON(["data" => "La factura ha sido anulada",  "code" => "200"]);
		} catch (Exception $ex) {
			return $this->response->setJSON(["msj" => $ex,  "code" => "500"]);
		}
	}

	public function show($id = null)
	{
		$re = (new Ventas_model())->find($id);
		if (is_null($re))
			return $this->genericResponse(null, "Este registro de Venta no existe", 500);
		else
			return $this->genericResponse($re, null, 200);
	}





	public function delete($id = null)
	{

		$us = (new Ventas_model())->find($id);

		if (is_null($us))
			return $this->genericResponse(null, "Venta  no existe",  500);
		else {
			(new Ventas_model())->where("regnro", $id)->delete($id);
			return $this->genericResponse("Venta eliminada", null,  200);
		}
	}







	public function informes($tipo)
	{
		try {
			//parametros
			$params =  $this->request->getRawInput();
			$Mes = $params['month'];
			$Anio =  $params['year'];
			$Cliente =   (array_key_exists("cliente",  $params))  ?  $params['cliente']  : session("id");
			$estado = "A";
			if (array_key_exists("anulados",  $params))  $estado =  $params['anulados'];

			$lista =	(new Ventas_model())
				->where("codcliente",   $Cliente)
				->where("year(fecha)", $Anio)
				->where(" month( fecha) ",  $Mes)
				->where("estado", $estado)
				->get()->getResult();


			if ($tipo == "PDF") return  $this->pdf($lista, $Cliente);
			if ($tipo == "JSON") return $this->response->setJSON($lista);
		} catch (Exception $e) {
			return $this->response->setJSON([]);
		}
	}



	public function pdf($lista, $CLIENTE = NULL)
	{


		$html = <<<EOF
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

		$t_exenta = 0;
		$t_iva5 = 0;
		$t_iva10 = 0;

		foreach ($lista as $row) {
			$fecha = Utilidades::fecha_f($row->fecha);
			$comprobante = Utilidades::formato_factura($row->factura);
			$exenta = Utilidades::number_f($row->importe3);
			$iva5 = Utilidades::number_f($row->importe2);
			$iva10 = Utilidades::number_f($row->importe1);

			$t_exenta += intval($row->importe3);
			$t_iva5 += intval($row->importe2);
			$t_iva10 += intval($row->importe1);

			$html .= "<tr> <td style=\"text-align:center;\">$fecha</td>  <td style=\"text-align:center;\">$comprobante</td> <td style=\"text-align:right;\" >$exenta</td> <td style=\"text-align:right;\">$iva5</td><td style=\"text-align:right;\">$iva10</td> </tr>";
		}
		$t_exenta = Utilidades::number_f($t_exenta);
		$t_iva5 = Utilidades::number_f($t_iva5);
		$t_iva10 = Utilidades::number_f($t_iva10);

		//totales
		$html .= "<tr class=\"footer\"> <td></td> <td style=\"text-align:center;\">Totales</td> <td style=\"text-align:right;\" >$t_exenta</td> <td style=\"text-align:right;\">$t_iva5</td><td style=\"text-align:right;\">$t_iva10</td> </tr>";

		$html .= "</tbody> </table> ";
		/********* */

		$tituloDocumento = "IVA_Venta-" . date("d") . "-" . date("m") . "-" . date("yy");

		$pdf = new PDF();
		$Cliente =  is_null($CLIENTE) ? session("id")  :  $CLIENTE;
		$RUCCLIENTE = (new Usuario_model())->where("regnro", $Cliente)->first();
		$TITULO_DOCUMENTO =  "RUC:" . $RUCCLIENTE->ruc . "-" . $RUCCLIENTE->dv . " (VENTAS)";
		$pdf->prepararPdf("$tituloDocumento.pdf",  $TITULO_DOCUMENTO, "");
		$pdf->generarHtml($html);
		$pdf->generar();
	}








	//Ultima fecha de factura venta en el mes activo
	public function ultima_fecha_carga()
	{

		$cliente = $this->getClienteId();
		$db =  \Config\Database::connect();

		$ULT = "";
		try {
			$ULT = $db->query("
		select fecha from ventas where ventas.codcliente=$cliente and 
		if((select  mes  from estado_mes where codcliente=ventas.codcliente and mes=month(ventas.fecha) 
		and anio=year(ventas.fecha) limit 0,1 ) is null,0, 1)=0   order by ventas.created_at DESC ")->getRow();
		} catch (Exception $e) {
		}


		//FECHA INGRESADA POR USUARIO
		//FECHA REGISTRO EN EL SISTEMA
		if (is_null($ULT))
			return  $this->response->setJSON(['msj' => 'Aun no se registran facturas',  'code' => '500']);
		else
			return  $this->response->setJSON(
				[
					'data' =>    $ULT->fecha,   'code' => '200'
				]
			);
	}
}
