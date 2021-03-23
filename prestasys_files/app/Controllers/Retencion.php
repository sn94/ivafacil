<?php

namespace App\Controllers;

use App\Helpers\Facturacion;
use App\Helpers\Utilidades;
use App\Libraries\pdf_gen\PDF;
use App\Models\Monedas_model;
use App\Models\Retencion_model;
use App\Models\Usuario_model;
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Retencion extends ResourceController
{


	protected $modelName = "App\Models\Retencion_model";
	protected $format = "json";
	private $API_MODE = true;


	public function __construct()
	{

		date_default_timezone_set("America/Asuncion");
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




	private function isAdminView()
	{
		$request = \Config\Services::request();
		$uri = $request->uri;
		return (sizeof($uri->getSegments()) > 0  && $uri->getSegment(1) == "admin");
	}




	public function index($MES = null,  $ANIO = null)
	{
		$cliente =   $this->getClienteId();
		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year =   is_null($ANIO) ?  date("Y")  :  $ANIO;
		$month = is_null($MES) ?  date("m") :  $MES;
		return  $this->index_($cliente,  $month, $year);
	}


	public function index_($CLI = NULL,  $month = NULL,   $year = NULL)
	{


		$cliente =  is_null($CLI)  ? $this->getClienteId()  :  $CLI;
		$lista_co = (new Retencion_model())->where("codcliente", $cliente);

		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];

		if ($this->request->getMethod(true) == "POST") {
			$parametros = $this->request->getRawInput();
			$month = isset($parametros['month'])  ? $parametros['month'] : $month;
			$year =  isset($parametros['year']) ? $parametros['year'] : $year;
		}

		$lista_co = $lista_co
			->where("codcliente",  $cliente)
			->where("year(fecha)", $year)
			->where("month(fecha)", $month)
			->orderBy("fecha");

		$TotalRegistros =   $lista_co->countAllResults();


		if ($this->isAPI()) {
			$lista_co = $lista_co
				->where("codcliente",  $cliente)
				->where("year(fecha)", $year)
				->where("month(fecha)", $month)
				->orderBy("fecha")->get()->getResult();
			return $this->respond(array("data" => $lista_co, "code" => 200));
		} else {

			$numero_filas = 10;
			$pagina =  isset($_GET['page']) ?  $_GET['page']  : 0;
			$lista_pagi = $lista_co
				->where("codcliente",  $cliente)
				->where("year(fecha)", $year)
				->where("month(fecha)", $month)
				->orderBy("fecha")
				->limit($numero_filas, $pagina)->get()->getResult();;

			$ViewParams =  [
				'retencion' =>  $lista_pagi,
				'TotalRegistros' => $TotalRegistros,
				// 'retencion_pager'=> $lista_co->pager,
				'year' => $year,
				'month' => $month,
				'EVENT_HANDLER' => "_informe_retencion(event)",
				'MODO' =>  $this->isAdminView() ? "ADMIN" :  "CLIENT"
			];

			return view("movimientos/informes/grill_retencion",  $ViewParams);
			/* if( $this->isAdminView()){
				return view("movimientos/informes/grill_retencion", 
				array_merge(  $ViewParams,  ['Link'=>  base_url("admin/clientes/retencion/$cliente/$month/$year")])
				);
			 }else{
				return view("movimientos/informes/grill_retencion", 
				array_merge(  $ViewParams,  ['Link'=>  base_url("retencion/index/$month/$year")])
				);
			 }*/
		}
	}






	public  function  total_mes($cod_cliente, $mes, $anio)
	{
		$this->API_MODE =  $this->isAPI();
		$reten = (new Retencion_model())->where("codcliente", $cod_cliente);
		$lista_co = [];

		//Segun los parametros
		//Parametros: mes y anio 
		$year = is_null($anio) ?  date("Y") :   $anio;
		$month = is_null($mes) ? date("m") :  $mes;
		$lista_co = $reten->where("year(fecha)", $year)
			->where("month(fecha)", $month)
			->select('if(  sum(importe) is null, 0,   sum(importe)  ) as importe')
			->first();
		return $lista_co;
	}


	public  function  total_anio($cod_cliente, $anio = NULL)
	{

		$reten = (new Retencion_model())->where("codcliente", $cod_cliente);
		$lista_co = [];
		//Segun los parametros
		//Parametros: mes y anio 
		$year = is_null($anio) ?  date("Y") :   $anio;
		$lista_co = $reten->where("year(fecha)", $year)
			->select('if(  sum(importe) is null, 0,   sum(importe)  ) as importe')
			->first();
		return $lista_co;
	}


	public  function  total_($cod_cliente,  $MES,  $ANIO)
	{
		$request = \Config\Services::request();
		$this->API_MODE =  $this->isAPI();
		$reten = (new Retencion_model())->where("codcliente", $cod_cliente);
		$lista_co = [];

		//Segun los parametros
		//Parametros: mes y anio
		$parametros = [];
		$year =  is_null($ANIO) ?  date("Y") :  $ANIO;
		$month =  is_null($MES) ?  date("m") :  $MES;

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












	public function create()
	{

		$this->API_MODE =  $this->isAPI();

		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET") {

			$habilitado =  (new Usuario())->servicio_habilitado($this->getClienteId());
			if (array_key_exists("msj",  $habilitado))
				return view("movimientos/comprobantes/retencion/create",  ["error" =>   $habilitado['msj']]);
			else
				return view("movimientos/comprobantes/retencion/create");
		}
		//Manejo POST
		$usu = new Retencion_model();
		$data = $this->request->getRawInput();
		$fecha_compro =  $data['fecha'];
		$mes_fecha_compro =   date("m",   strtotime($fecha_compro));
		$anio_fecha_anio =   date("Y",   strtotime($fecha_compro));

		//Al dia
		$habilitado =  (new Usuario())->servicio_habilitado($this->getClienteId());
		if (array_key_exists("msj",  $habilitado))
			return $this->response->setJSON(['msj' =>  $habilitado['msj'],  'code' => "500"]);


		if ((new Cierres())->esta_cerrado($mes_fecha_compro,  $anio_fecha_anio))
			return  $this->response->setJSON(['msj' =>  "El mes ya esta cerrado",  "code" =>  "500"]);

		//Verificar si el periodo-ejercicio esta cerrado o fuera de rango
		//$Operacion_fecha_invalida = (new Cierres())->fecha_operacion_invalida($data['fecha']);
		//if (!is_null($Operacion_fecha_invalida))  return $Operacion_fecha_invalida;
		//***** Fin check tiempo*/

		$data['origen'] = $this->isAPI() ? "A" : "W";


		if ($this->validate('retencion')) { //Validacion OK

			$moneda =  $data["moneda"];
			if (!$moneda && !is_null((new Monedas_model())->find($moneda))) {
				return $this->genericResponse(null,  "Codigo de moneda: $moneda no existe", 500);
			}

			if ($moneda != "1" && (!isset($data['tcambio'])  ||  $data['tcambio'] == "")) {
				return $this->genericResponse(null,  "Indique el monto para cambio de moneda", 500);
			}
			$resu = []; //Resultado de la operacion
			try {


				//inferir otros datos del cliente
				$ModeloCliente =  (new Usuario_model())->find($this->getClienteId());
				$data["codcliente"] = $ModeloCliente->regnro;
				$data['ruc'] =  $ModeloCliente->ruc;
				$data['dv'] = $ModeloCliente->dv;
				 

				$data = Facturacion::convertir_a_moneda_nacional($data);

				//Crear nuevo registro de ejercicio si es necesario
				(new Cierres())->crear_ejercicio();

				$id = $usu->insert($data);
				$resu = $this->genericResponse((new Retencion_model())->find($id), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200)
					return $this->response->setJSON(['data' =>  'Guardado', 'code' => '200']);
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
			return $this->response->setJSON(['msj' =>  $resultadoValidacion['msj'], 'code' => '500']);
		//return view("movimientos/comprobantes/retencion", array("error" => $resultadoValidacion['msj']));


	}








	public function update($cod_retencion = null)
	{

		$this->API_MODE =  $this->isAPI();
		$request = \Config\Services::request();

		if ($request->getMethod(true) == "GET") {
			$regis =  (new Retencion_model())->find($cod_retencion);

			$habilitado =  (new Usuario())->servicio_habilitado($this->getClienteId());
			if (array_key_exists("msj",  $habilitado))
				return view("movimientos/comprobantes/retencion/update",  ['error' =>  $habilitado['msj'],  'retencion' =>  $regis]);
			else
				return view("movimientos/comprobantes/retencion/update",  ['retencion' =>  $regis]);
		}

		//Manejo POST 
		$data = $this->request->getRawInput();
		//inferir otros datos del cliente
		$ModeloCliente =  (new Usuario_model())->find($this->getClienteId());
		$data["codcliente"] = $ModeloCliente->regnro;
		$data['ruc'] =  $ModeloCliente->ruc;
		$data['dv'] = $ModeloCliente->dv;
		$data['origen'] =  $this->isAPI() ? "A" : "W";
		$codRetencion = 	$data['regnro'];

		/**Verificar fecha  */
		$fecha_compro =  $data['fecha'];
		$fecha_validacion = Facturacion::fechaDeComprobanteEsValida($fecha_compro);
		if (!is_null($fecha_validacion)) return $fecha_validacion;


		//Verificar si el periodo-ejercicio esta cerrado o fuera de rango

		//	$Operacion_fecha_invalida= (new Cierres())->fecha_operacion_invalida(  $data['fecha'] );
		//if (  !is_null($Operacion_fecha_invalida))  return $Operacion_fecha_invalida;
		//***** Fin check tiempo*/


		if ($this->validate('retencion_update')) { //Validacion OK

			if (isset($data["moneda"])) {
				$moneda =  $data["moneda"];
				if (!$moneda && !is_null((new Monedas_model())->find($moneda))) {
					return $this->genericResponse(null,  "Codigo de moneda: $moneda no existe", 500);
				}

				if ($moneda != "1" && (!isset($data['tcambio'])  ||  $data['tcambio'] == "")) {
					return $this->genericResponse(null,  "Indique el monto para cambio de moneda", 500);
				}
				$data = Facturacion::convertir_a_moneda_nacional($data);
			}
			/**End check moneda */

			$resu = []; //Resultado de la operacion
			try {
				$retencionObj = new Retencion_model();
				$retencionObj->set($data)
					->where("regnro", $codRetencion)
					->where("codcliente", $data["codcliente"])
					->update();

				$resu = $this->genericResponse((new Retencion_model())->find($codRetencion), null, 200);
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





	public function delete($id = null)
	{
		$this->API_MODE = true;

		$us = (new Retencion_model())->find($id);

		if (is_null($us))
			return $this->genericResponse(null, "Registro de retención no existe",  500);
		else {
			(new Retencion_model())->where("regnro", $id)->delete($id);
			return $this->genericResponse("Registro de retención eliminado", null,  200);
		}
	}














	public function informes($tipo)
	{
		try {
			//parametros
			$params =  $this->request->getRawInput();
			$Mes = $params['month'];
			$Anio =  $params['year'];
			$Cliente =  (array_key_exists("cliente",  $params))  ?  $params['cliente']  : session("id");

			$lista =	(new Retencion_model())
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
	<th style="text-align:right;">IMPORTE</th>
	</tr>
	</thead>
	<tbody>
	EOF;

		$t_importe = 0;

		foreach ($lista as $row) {
			$fecha = Utilidades::fecha_f($row->fecha);
			$comprobante = Utilidades::formato_factura($row->retencion);

			$importe = Utilidades::number_f($row->importe);

			$t_importe = intval($row->importe);

			$html .= "<tr>  <td style=\"text-align:center;\">$fecha</td> <td style=\"text-align:center;\">$comprobante</td> <td style=\"text-align:right;\" >$importe</td>    </tr>";
		}
		$t_importe = Utilidades::number_f($t_importe);

		//totales
		$html .= "<tr class=\"footer\"> <td></td> <td style=\"text-align:center;\">Totales</td> <td style=\"text-align:right;\" >$t_importe</td>  </tr>";

		$html .= "</tbody> </table> ";
		/********* */

		$tituloDocumento = "Retencion-" . date("d") . "-" . date("m") . "-" . date("yy");

		$pdf = new PDF();
		$Cliente =  is_null($CLIENTE) ? session("id")  :  $CLIENTE;
		$RUCCLIENTE = (new Usuario_model())->where("regnro", $Cliente)->first();
		$TITULO_DOCUMENTO =  "RUC:" . $RUCCLIENTE->ruc . "-" . $RUCCLIENTE->dv . " (RETENCIONES)";
		$pdf->prepararPdf("$tituloDocumento.pdf",  $TITULO_DOCUMENTO, "");
		$pdf->generarHtml($html);
		$pdf->generar();
	}
}
