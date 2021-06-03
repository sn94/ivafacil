<?php

namespace App\Controllers;

use App\Helpers\Facturacion;
use App\Helpers\Utilidades;
use App\Libraries\pdf_gen\PDF;
use App\Models\Compras_model;
use App\Models\Estado_anio_model;
use App\Models\Monedas_model;
use App\Models\Usuario_model;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Compra extends ResourceController
{


	use 	ResponseTrait;

	protected $modelName = "App\Models\Compras_model";
	protected $format = "json";


	public function __construct()
	{

		date_default_timezone_set("America/Asuncion");
	}




	private function genericResponse($data, $msj, $code)
	{

		if ($code == 200) {
			if ($this->isAPI())
				return $this->respond(array("data" => $data, "code" => $code)); //, 500, "No hay nada"
			else return array("data" => $data, "code" => $code);
		} else {
			if ($this->isAPI()) return $this->respond(array("msj" => $msj, "code" => $code));
			else return array("msj" => $msj, "code" => $code);
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




	//Subinformes


	public function index_se($CLIENTE, $MES,   $ANIO)
	{

		$compras = (new Compras_model());
		$db = \Config\Database::connect();

		//Segun los parametros
		//Parametros: mes y anio 
		$cliente = $CLIENTE;
		$year =      $ANIO;
		$month =  $MES;

		$lista_co = $compras
			->where("codcliente", $cliente)
			->where("year(fecha)", $year)
			->where("month(fecha)", $month);

		//Contar
		$TotalRegistros =   $lista_co->countAllResults();

		if ($this->isAPI()) {
			$lista_co = $lista_co
				->where("codcliente", $cliente)
				->where("year(fecha)", $year)
				->where("month(fecha)", $month)
				->orderBy("fecha")
				->get()->getResult();
			return $this->respond(array("data" => $lista_co, "code" => 200));
		} else {

			$numero_filas = 10;
			$pagina =  isset($_GET['page']) ?  $_GET['page']  : 0;

			$lista_co =  $lista_co
				->where("codcliente", $cliente)
				->where("year(fecha)", $year)
				->where("month(fecha)", $month)
				->orderBy("fecha")
				->limit($numero_filas,  $pagina)->get()->getResult();

			$lista_pagi =  $lista_co;   //; 

			$ViewParams =  	[
				'compras' =>  $lista_pagi,
				//'compras_pager' => $lista_co->pager,
				'TotalRegistros' => $TotalRegistros,
				'year' => $year,
				'month' => $month,
				'CLIENTE' =>  $cliente,
				'EVENT_HANDLER' => "_informe_compras(event)",
				'MODO' =>  $this->isAdminView() ? "ADMIN" :  "CLIENT"
			];


			return view(
				"movimientos/informes/grill_compras",
				$ViewParams
			);

			/*if ($this->isAdminView()) {
				return view(
					"admin/clientes/movimientos/grill_compras",
					array_merge($ViewParams,  ['Link'=>  base_url("admin/clientes/compras/$cliente/$month/$year")])
				);
			} else {
				return view("movimientos/informes/grill_compras", 
				array_merge(  $ViewParams,  ['Link'=>  base_url("compra/index/$month/$year")]  )
				);
			}*/
		}
	}



	public function index($MES = NULL,   $ANIO =   NULL)
	{


		$cliente = $this->getClienteId();
		$request = \Config\Services::request();

		//Segun los parametros

		//Parametros: mes y anio
		$parametros = [];
		$year =   is_null($ANIO) ?  date("Y")  :  $ANIO;
		$month = is_null($MES) ?  date("m") :  $MES;

		if ($this->request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
		}
		$compras =  $this->index_se($cliente,  $month, $year);
		return  $compras;
	}


	public function total_mes($cod_cliente, $Mes = NULL,  $Anio = NULL)
	{
		$request = \Config\Services::request();
		$compras = (new Compras_model());
		$lista_co = $compras->where("codcliente", $cod_cliente);
		//Parametros: mes y anio 
		$year =  is_null($Anio) ?  date("Y") : $Anio;
		$month = is_null($Mes) ? date("m") : $Mes;
		$lista_co = $lista_co->where("year(fecha)", $year)
			->where("month(fecha)", $month)
			->select('if( sum(iva1) is null, 0,  sum(iva1) ) as iva1, if( sum(iva2) is null, 0,  sum(iva2) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3 ,
		if( sum(importe1) is null, 0, sum(importe1) ) as total10, 
		if( sum(importe2) is null, 0, sum(importe2) ) as total5,
		if( sum(importe3) is null, 0, sum(importe3) ) as totalexe
		')
			->first();
		return  $lista_co;
	}


	public function total_anio($cod_cliente, $Anio = NULL)
	{
		$request = \Config\Services::request();
		$compras = (new Compras_model());
		$lista_co = $compras->where("codcliente", $cod_cliente);
		//Parametros: mes y anio 
		$year =  is_null($Anio) ?  date("Y") : $Anio;
		$lista_co = $lista_co->where("year(fecha)", $year)
			->select('if( sum(iva1) is null, 0,  ROUND(sum(iva1)) ) as iva1, if( sum(iva2) is null, 0,  ROUND(sum(iva2)) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3 ,
		if( sum(importe1) is null, 0, sum(importe1) ) as total10, 
		if( sum(importe2) is null, 0, sum(importe2) ) as total5,
		if( sum(importe3) is null, 0, sum(importe3) ) as totalexe
		')->first();
		return  $lista_co;
	}


	public function total_($cod_cliente, $MES, $YEAR)
	{
		$request = \Config\Services::request();
		$compras = (new Compras_model());
		$lista_co = $compras->where("codcliente", $cod_cliente);
		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year =  is_null($YEAR) ?  date("Y") :  $YEAR;
		$month =  is_null($MES) ?  date("m") :  $MES;


		if ($request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
		}
		$lista_co = $lista_co->where("year(fecha)", $year)
			->where("month(fecha)", $month)
			->select('if( sum(iva1) is null, 0,  round(sum(iva1)) ) as iva1, if( sum(iva2) is null, 0,  round(sum(iva2)) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3 ,
		if( sum(importe1) is null, 0, sum(importe1) ) as total10, 
		if( sum(importe2) is null, 0, sum(importe2) ) as total5,
		if( sum(importe3) is null, 0, sum(importe3) ) as totalexe
		')
			->first();
		return  $lista_co;
	}


	public  function  total()
	{
		$request = \Config\Services::request();

		$codcliente = $this->getClienteId();
		$MES =  date("m");
		$ANIO =  date("Y");
		$lista_co =  $this->total_($codcliente,  $MES, $ANIO);
		$response =  \Config\Services::response();
		return $response->setJSON($lista_co);
	}












	public function create()
	{

		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET") {
			$mensajes = (new Usuario())->servicio_habilitado($this->getClienteId());

			if (array_key_exists("msj",  $mensajes))
				return view("movimientos/comprobantes/compra/create", ['error' =>  $mensajes['msj']]);
			else
				return view("movimientos/comprobantes/compra/create");
		}
		//Manejo POST


		$usu = new Compras_model();

		$data = $this->request->getRawInput();
		$fecha_compro =  $data['fecha'];
		 
		//Operacion habilitada
		$oper_habilitada =   (new Cierres())->operacion_habilitada($this->getClienteId(),  $fecha_compro);
		if (!is_null($oper_habilitada))  return  $oper_habilitada;


		//inferir otros datos del cliente
		$ModeloCliente =  (new Usuario_model())->find($this->getClienteId());
		$data["codcliente"] = $ModeloCliente->regnro;
		$data['ruc'] =  $ModeloCliente->ruc;
		$data['dv'] = $ModeloCliente->dv;
		$data['origen'] =   $this->isAPI() ?  "A"   : "W";
		if (!isset($data['importe1'])) $data['importe1'] = 0;
		if (!isset($data['importe2'])) $data['importe2'] = 0;
		if (!isset($data['importe3'])) $data['importe3'] = 0;

		if ($this->validate('compras')) { //Validacion OK

			$cod_cliente =  $data["codcliente"];
			if (!$cod_cliente && !is_null((new Usuario_model())->find($cod_cliente))) {
				return  $this->genericResponse(null,  "Codigo de cliente: $cod_cliente no existe", 500);
			}

			$moneda =  $data["moneda"];
			if (!$moneda && !is_null((new Monedas_model())->find($moneda))) {
				return $this->genericResponse(null,  "Codigo de moneda: $moneda no existe", 500);
			}

			if ($moneda != "1" && (!isset($data['tcambio'])  ||  $data['tcambio'] == "")) {
				return $this->genericResponse(null,  "Indique el monto para cambio de moneda", 500);
			}
			$resu = []; //Resultado de la operacion
			//Inicio de transaccion
			$db = \Config\Database::connect();

			$db->transStart();
			try {

				//calculo interno del iva
				$data = Facturacion::calcular_iva($data);

				//Convertir a guaranies
				if ($moneda != 1) {
					$data = Facturacion::convertir_a_moneda_nacional($data);
				}
				//Crear nuevo registro de ejercicio si es necesario
				(new Cierres())->crear_periodos_ejercicios( $fecha_compro);
				$id = $usu->insert($data);
				$resu = $this->genericResponse((new Compras_model())->find($id), null, 200);
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
					return $this->response->setJSON(['data' => "Guardado", "code" => "200"]);
				//return redirect()->to(base_url("movimiento/informe_mes"));
				else  return view("movimientos/comprobantes/compra/create", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->isAPI())
			return $resultadoValidacion;
		else
			return $this->response->setJSON(['msj' => $resultadoValidacion['msj'], "code" => "500"]);
	 
	}





	//ruc=14455&dv=23&codcliente=9&fecha=2020-12-11&moneda=1&factura=0020037892222&total=140000000
	public function update($cod_compra = NULL)
	{

		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET") {

			$regis =  (new Compras_model())->find($cod_compra);
			//servicio habilitado
			$habilitado =  (new Usuario())->servicio_habilitado($this->getClienteId());
			if (array_key_exists("msj",  $habilitado))
				return view(
					"movimientos/comprobantes/compra/update",
					["compra" => $regis,  "error" =>  $habilitado['msj']]
				);
			else
				return view(
					"movimientos/comprobantes/compra/update",
					["compra" => $regis]
				);
		}

		//Manejo POST


		$usu = new Compras_model();

		$data = $this->request->getRawInput();
		//Validar codigo cliente
		//$data["codcliente"]=  $this->getClienteId();

		$fecha_compro =  $data['fecha'];
		 
		//Operacion habilitada Por fecha comprobante, y pago al dia por servicio
		$oper_habilitada =  (new Cierres())->operacion_habilitada($this->getClienteId(),  $fecha_compro);
		if (!is_null($oper_habilitada))  return  $oper_habilitada;


		//Cliente
		$ModeloCliente =  (new Usuario_model())->find($this->getClienteId());
		//inferir otros datos del cliente 
		$data["codcliente"] = $ModeloCliente->regnro;
		$data['ruc'] =  $ModeloCliente->ruc;
		$data['dv'] = $ModeloCliente->dv;
		$data['origen'] =   $this->isAPI() ?  "A"   : "W";
		//Compra
		$ModeloCompra = (new Compras_model())->find($data['regnro']);

		if ($this->validate('compras')) { //Validacion OK


			/**Validacion Moneda */
			$moneda = isset($data["moneda"]) ?  $data["moneda"] : $ModeloCompra->moneda;
			if (!$moneda && !is_null((new Monedas_model())->find($moneda))) {
				return $this->genericResponse(null,  "Codigo de moneda: $moneda no existe", 500);
			}
			if ($moneda != "1" && (!isset($data['tcambio'])  ||  $data['tcambio'] == "")) {
				return $this->genericResponse(null,  "Indique el monto para cambio de moneda", 500);
			}


			$resu = []; //Resultado de la operacion
			try {
				//calculo interno del iva
				$data =  Facturacion::calcular_iva($data);

				(new Cierres())->crear_periodos_ejercicios(	$fecha_compro );
				//Convertir a guaranies
				if ($moneda != 1) {
					$data =  Facturacion::convertir_a_moneda_nacional($data);
				}

				$cod_cliente = $data['codcliente'];
				$usu->where("codcliente", $cod_cliente)
					->where("regnro", $data['regnro'])
					->set($data)
					->update();
				$resu = $this->genericResponse((new Compras_model())->find($data['regnro']), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->isAPI()) return  $resu;
			else {
				return $this->response->setJSON($resu);
				//if ($resu['code'] == 200) return redirect()->to(base_url("movimiento/index"));
			 
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->isAPI())
			return $resultadoValidacion;
		else
			return $this->response->setJSON(['msj' => $resultadoValidacion['msj'], "code" => "500"]);
		 
	}




	public function show($id = null)
	{

		$re = (new Compras_model())->find($id);
		if (is_null($re))
			return $this->genericResponse(null, "Este registro de Compra no existe", 500);
		else
			return $this->genericResponse($re, null, 200);
	}





	public function delete($id = null)
	{


		$us = (new Compras_model())->find($id);

		if (is_null($us))
			return $this->genericResponse(null, "Compra  no existe",  500);
		else {
			(new Compras_model())->where("regnro", $id)->delete($id);
			return  $this->response->setJSON( $this->genericResponse("Compra eliminada", null,  200) );
		}
	}











	public function informes($tipo)
	{
		try {
			//parametros
			$params =  $this->request->getRawInput();
			$Mes = $params['month'];
			$Anio =  $params['year'];
			$Cliente =  (array_key_exists("cliente",  $params)) ?  $params['cliente']  : session("id");

			$lista =	(new Compras_model())
				->where("codcliente",   $Cliente)
				->where("year(fecha)", $Anio)
				->where(" month( fecha) ",  $Mes)->get()->getResult();


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
			$fecha =  Utilidades::fecha_f($row->fecha);
			$comprobante = Utilidades::formato_factura($row->factura);
			$exenta = Utilidades::number_f($row->importe3);
			$iva5 = Utilidades::number_f($row->importe2);
			$iva10 = Utilidades::number_f($row->importe1);

			$t_exenta += intval($row->importe3);
			$t_iva5 += intval($row->importe2);
			$t_iva10 += intval($row->importe1);

			$html .= "<tr><td style=\"text-align:center;\">$fecha</td> <td style=\"text-align:center;\">$comprobante</td> <td style=\"text-align:right;\" >$exenta</td> <td style=\"text-align:right;\">$iva5</td><td style=\"text-align:right;\">$iva10</td> </tr>";
		}
		$t_exenta = Utilidades::number_f($t_exenta);
		$t_iva5 = Utilidades::number_f($t_iva5);
		$t_iva10 = Utilidades::number_f($t_iva10);

		//totales
		$html .= "<tr class=\"footer\"> <td></td> <td style=\"text-align:center;\">Totales</td> <td style=\"text-align:right;\" >$t_exenta</td> <td style=\"text-align:right;\">$t_iva5</td><td style=\"text-align:right;\">$t_iva10</td> </tr>";

		$html .= "</tbody> </table> ";
		/********* */

		$tituloDocumento = "IVA_Compra-" . date("d") . "-" . date("m") . "-" . date("yy");

		$pdf = new PDF();
		$Cliente = is_null($CLIENTE) ?  session("id")   :  $CLIENTE;
		$RUCCLIENTE = (new Usuario_model())->where("regnro", $Cliente)->first();
		$TITULO_DOCUMENTO =  "RUC:" . $RUCCLIENTE->ruc . "-" . $RUCCLIENTE->dv . " (COMPRAS)";
		$pdf->prepararPdf("$tituloDocumento.pdf",  $TITULO_DOCUMENTO, "");
		$pdf->generarHtml($html);
		return $pdf->generar();
	}
}
