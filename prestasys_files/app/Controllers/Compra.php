<?php

namespace App\Controllers;

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
	private $API_MODE = true;


	public function __construct()
	{

		date_default_timezone_set("America/Asuncion");
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


	public function index_se($CLIENTE = NULL, $MES = NULL,   $ANIO =   NULL)
	{

		$this->API_MODE =  $this->isAPI();

		$compras = (new Compras_model());


		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$cliente= $CLIENTE;
		$year =   is_null($ANIO) ?  date("Y")  :  $ANIO;
		$month = is_null($MES) ?  date("m") :  $MES;

		if ($this->request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$cliente = isset($parametros['cliente'])  ? $parametros['cliente'] : $cliente;
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
		}
		$lista_co = [];
		$lista_co = $compras
			->where("codcliente", $cliente)
			->where("year(fecha)", $year)
			->where("month(fecha)", $month);


		if ($this->API_MODE) {
			$lista_co = $lista_co->get()->getResult();
			return $this->respond(array("data" => $lista_co, "code" => 200));
		} else {

			$lista_pagi = $lista_co->paginate(10);
			if( $this->isAdminView()) 
			return view(
				"admin/clientes/movimientos/grill_compras",
				[
					'compras' =>  $lista_pagi,
					'compras_pager' => $lista_co->pager,
					'year' => $year,
					'month' => $month,
					'CLIENTE'=>  $cliente
				]
			);
			else
			return view(
				"movimientos/informes/grill_compras",
				[
					'compras' =>  $lista_pagi,
					'compras_pager' => $lista_co->pager,
					'year' => $year,
					'month' => $month
				]
			);
		}
	}



	public function index($MES = NULL,   $ANIO =   NULL)
	{

		$this->API_MODE =  $this->isAPI();
		$cliente = null;
		if ($this->API_MODE) {
			$request = \Config\Services::request();
			$sesion = is_null($request->getHeader('Ivasession')) ? "" :  $request->getHeader('Ivasession')->getValue();
			//idS de usuario
			$usunow = (new Usuario_model())->where("session_id", $sesion)->first();
			$ruc =  $usunow->ruc;
			$dv =  $usunow->dv;
			$cliente =  $usunow->regnro;
		} else {
			$cliente = session("id");
		}
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
			->select('if( sum(iva1) is null, 0,  sum(iva1) ) as iva1, if( sum(iva2) is null, 0,  sum(iva2) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3 ,
		if( sum(importe1) is null, 0, sum(importe1) ) as total10, 
		if( sum(importe2) is null, 0, sum(importe2) ) as total5,
		if( sum(importe3) is null, 0, sum(importe3) ) as totalexe
		')
			->first();
		return  $lista_co;
	}


	public function total_($cod_cliente, $MES = NULL, $YEAR = NULL)
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
			->select('if( sum(iva1) is null, 0,  sum(iva1) ) as iva1, if( sum(iva2) is null, 0,  sum(iva2) ) as iva2, if( sum(iva3) is null, 0,  sum(iva3) ) as iva3 ,
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
		$this->API_MODE =  $this->isAPI();
		$codcliente = $this->getClienteId();
		$lista_co =  $this->total_($codcliente);
		$response =  \Config\Services::response();
		return $response->setJSON($lista_co);
	}









	public function create()
	{

		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET")
			return view("movimientos/comprobantes/compra/create");
		//Manejo POST

		$this->API_MODE =  $this->isAPI();
		$usu = new Compras_model();

		$data = $this->request->getRawInput();

		//Verificar si el periodo-ejercicio esta cerrado o fuera de rango
		$Operacion_fecha_invalida = (new Cierres())->fecha_operacion_invalida($data['fecha']);
		if (!is_null($Operacion_fecha_invalida))  return $Operacion_fecha_invalida;
		//***** Fin check tiempo*/

		if ($this->API_MODE)  $data['origen'] = "A";

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
				if ($this->API_MODE)  $data['origen'] = "A"; //ORIGEN Aplicacion
				//Convertir a guaranies
				if ($moneda != 1) {
					$cambio = $data['tcambio'];
					$im1 = $data['importe1'];
					$im2 = $data['importe2'];
					$im3 = $data['importe3'];
					$iva1 = $data['iva1'];
					$iva2 = $data['iva2'];
					$iva3 = $data['iva3'];
					$data['importe1'] =  intval($cambio) * intval($im1);
					$data['importe2'] =  intval($cambio) * intval($im2);
					$data['importe3'] =  intval($cambio) * intval($im3);
					$data['iva1'] =  intval($cambio) * intval($iva1);
					$data['iva2'] =  intval($cambio) * intval($iva2);
					$data['iva3'] =  intval($cambio) * intval($iva3);
					$data["total"] =  $data['importe1']  + $data['importe2']  + $data['importe3'];
				}
				//Crear nuevo registro de ejercicio si es necesario
				(new Cierres())->crear_ejercicio();
				$id = $usu->insert($data);
				$resu = $this->genericResponse((new Compras_model())->find($id), null, 200);
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
					return $this->response->setJSON(['data' => "Guardado", "code" => "200"]);
				//return redirect()->to(base_url("movimiento/informe_mes"));
				else  return view("movimientos/comprobantes/f_compra", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
			return $resultadoValidacion;
		else
			return $this->response->setJSON(['msj' => $resultadoValidacion['msj'], "code" => "500"]);
		// return view("movimientos/comprobantes/f_compra", array("error" => $resultadoValidacion['msj']));
	}





	//ruc=14455&dv=23&codcliente=9&fecha=2020-12-11&moneda=1&factura=0020037892222&total=140000000
	public function update($cod_compra = NULL)
	{

		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET") {
			$regis =  (new Compras_model())->find($cod_compra);

			return view(
				"movimientos/comprobantes/compra/update",
				["compra" => $regis]
			);
		}

		//Manejo POST

		$this->API_MODE =  $this->isAPI();
		$usu = new Compras_model();

		$data = $this->request->getRawInput();

		//Verificar si el periodo-ejercicio esta cerrado o fuera de rango
		$Operacion_fecha_invalida = (new Cierres())->fecha_operacion_invalida($data['fecha']);
		if (!is_null($Operacion_fecha_invalida))  return $Operacion_fecha_invalida;
		//***** Fin check tiempo*/


		if ($this->API_MODE)  $data['origen'] = "A";

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
			try {
				if ($this->API_MODE)  $data['origen'] = "A"; //ORIGEN Aplicacion

				//Convertir a guaranies
				if ($moneda != 1) {
					$cambio = $data['tcambio'];
					$im1 = $data['importe1'];
					$im2 = $data['importe2'];
					$im3 = $data['importe3'];
					$iva1 = $data['iva1'];
					$iva2 = $data['iva2'];
					$iva3 = $data['iva3'];
					$data['importe1'] =  intval($cambio) * intval($im1);
					$data['importe2'] =  intval($cambio) * intval($im2);
					$data['importe3'] =  intval($cambio) * intval($im3);
					$data['iva1'] =  intval($cambio) * intval($iva1);
					$data['iva2'] =  intval($cambio) * intval($iva2);
					$data['iva3'] =  intval($cambio) * intval($iva3);
					$data["total"] =  $data['importe1']  + $data['importe2']  + $data['importe3'];
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
			if ($this->API_MODE) return  $resu;
			else {
				return $this->response->setJSON($resu);
				//if ($resu['code'] == 200) return redirect()->to(base_url("movimiento/index"));
				//else  return view("movimientos/comprobantes/f_compra", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
			return $resultadoValidacion;
		else
			return $this->response->setJSON(['msj' => $resultadoValidacion['msj'], "code" => "500"]);
		//return view("movimientos/comprobantes/f_compra", array("error" => $resultadoValidacion['msj']));
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


		$this->API_MODE =  true;

		$us = (new Compras_model())->find($id);

		if (is_null($us))
			return $this->genericResponse(null, "Compra  no existe",  500);
		else {
			(new Compras_model())->where("regnro", $id)->delete($id);
			return $this->genericResponse("Compra eliminada", null,  200);
		}
	}











	public function informes($tipo)
	{
		try {
			//parametros
			$params =  $this->request->getRawInput();
			$Mes = $params['month'];
			$Anio =  $params['year'];
			$Cliente =  ( array_key_exists("cliente",  $params)) ?  $params['cliente']  : session("id");

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



	public function pdf($lista, $CLIENTE=NULL)
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
			$fecha=  Utilidades::fecha_f(  $row->fecha);
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
