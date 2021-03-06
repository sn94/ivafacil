<?php

namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\Correo;
use App\Models\Compras_model;
use App\Models\Estado_anio_model;
use App\Models\Estado_mes_model;
use App\Models\Parametros_model;
use App\Models\Retencion_model;
use App\Models\Usuario_model;
use App\Models\Ventas_model;
use CodeIgniter\Controller;
use Exception;



class Cierres extends Controller
{



	public function __construct()
	{
		date_default_timezone_set("America/Asuncion");
		helper("form");
	}



	private function isAdminView()
	{
		$request = \Config\Services::request();
		$uri = $request->uri;
		return (sizeof($uri->getSegments()) > 0  && $uri->getSegment(1) == "admin");
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






	/**
	 *
	 * @return JSON ARRAY | JSON
	 * @param MES
	 * @param ANIO
	 * @param CLIENTEID
	 * @param FORMATO (default JSON)
	 */
	public function totales_mes($MES, $ANIO, $cod_cliente = NULL,  $RETORNO =  "JSON")
	{


		$CODCLIENTE =  is_null($cod_cliente) ?  $this->getClienteId()  :   $cod_cliente;
		//Modelo de estado mes
		$ESTADO_MES = (new Estado_mes_model())
			->where("codcliente", $CODCLIENTE)
			->where("mes", $MES)->where("anio", $ANIO)->first();
		//Totales en compras ventas retencion
		$cf =  (new Compra())->total_($CODCLIENTE, $MES, $ANIO);
		$df = (new Venta())->total_($CODCLIENTE, $MES, $ANIO);
		$reten = (new Retencion())->total_($CODCLIENTE, $MES, $ANIO);

		//A favor del contribuyente
		$s_contri =  round($cf->iva1 + $cf->iva2) + intval($reten->importe);
		//A favor de hacienda
		$s_fisco =  round($df->iva1 + $df->iva2);
		//Saldo inicial del mes
		$saldo_a = $this->calcular_saldo_anterior( $MES, $ANIO, $CODCLIENTE);
		$saldo =  $s_contri -  $s_fisco;

		$response =  \Config\Services::response();
		//Pagos de iva
		$total_en_pagos = (new Estado_mes_model())->select('if( SUM(pago) is null, 0, SUM(PAGO) ) AS PAGOS ')
			->where("mes",  $MES)
			->where("codcliente",  $CODCLIENTE)->where("anio",  $ANIO)
			->first();
		$Montos = [

			'compras_total_10' => intval($cf->total10),
			'compras_total_5' => intval($cf->total5),
			'compras_total_exe' => intval($cf->totalexe),
			'compras_iva10' => round($cf->iva1),
			'compras_iva5' => round($cf->iva2),
			'compras_total_iva' =>  round($cf->iva1) +  round($cf->iva2),
			'ventas_total_10' => intval($df->total10),
			'ventas_total_5' => intval($df->total5),
			'ventas_total_exe' => intval($df->totalexe),
			'ventas_iva10' => round($df->iva1),
			'ventas_iva5' => round($df->iva2),
			'ventas_total_iva' => round($df->iva1) +   round($df->iva2),
			'retencion' => (intval($reten->importe)),
			'saldo' => $saldo,
			'saldo_anterior' => $saldo_a,
			'pago' =>  $total_en_pagos->PAGOS,
			'estado' => is_null($ESTADO_MES)  ? "P" : ($ESTADO_MES->estado)
		];

		if ($RETORNO ==  "ARRAY")   return  $Montos;
		if ($RETORNO ==  "JSON")
			return  $response->setJSON($Montos);
	}


	//Saldo del mes anterior (periodo anterior)

	private function saldo_mes_anterior($CODCLIENTE, $MES, $ANIO)
	{
		$cliente = (new Usuario_model())->find($CODCLIENTE);

		//Consultar el saldo del ultimo mes que se ha cerrado
		$meses_cerrados =
			(new Estado_mes_model())
			->where("codcliente",  $CODCLIENTE)
			->where("estado <>", "P")
			->groupStart()
			->where("mes  <",  $MES)
			->where("anio",  $ANIO)
			->groupEnd()
			->orGroupStart()
			->where("mes  <=",  $MES)
			->where("anio <",  $ANIO)
			->groupEnd()
			->orderBy("mes", "DESC")
			->first();
		/*(new Estado_mes_model())->where("codcliente",  $CODCLIENTE)
			->where("anio",  $ANIO)
			->where("mes", "<", $MES)
			->where("estado <>", "P")
			->orderBy("mes", "DESC")
			->first();*/
		//Si no se registra algun mes anterior
		if (is_null($meses_cerrados)) {
			//Consultar el saldo inicial registrado para el Ejercicio actual
			$saldo_del_anio = (new Estado_anio_model())->where("codcliente",  $CODCLIENTE)
				->where("anio",  $ANIO)->first();
			//Si no se registra algun inicial para el presente Ejercicio
			if (is_null($saldo_del_anio))
				return   $cliente->saldo_IVA; //Devolver el saldo inicial definido por usuario
			else
				return $saldo_del_anio->saldo_inicial; //Devolver saldo inicial del Ejercicio actual
		} else {
			//Existe registro del ultimo mes cerrado
			$saldo_liq = $meses_cerrados->saldo;
			$saldo_ant = $meses_cerrados->saldo_inicial;
			$saldo = intval($saldo_liq) + intval($saldo_ant); //Sumar el saldo inicial y saldo liquido del mes
			//anterior
			return $saldo;
		}
	}




	public function calcular_saldo_anterior($MES,  $ANIO, $codcliente= NULL)

	{

		$CODCLIENTE=  is_null($codcliente) ? $this->getClienteId():   $codcliente;
		$usuario =   (new Usuario_model())->find($CODCLIENTE);


		$retornar= 0;
		/*
		$esteMES=(new Estado_mes_model())
		->where("codcliente",  $CODCLIENTE)
		->where("mes",  $MES)
		->where("anio",  $ANIO)->first();
		if( ! is_null($esteMES))  return  $esteMES->saldo_inicial;
*/
		//Es un mes cerrado?
		$estaCerrado = (new Estado_mes_model())->where("codcliente",  $CODCLIENTE)->where("mes",  $MES)
			->where("anio",  $ANIO)->first();

		if (!is_null($estaCerrado)    &&   $estaCerrado->estado != "P")  $retornar= $estaCerrado->saldo_inicial;
		else{
			$retornar=  $usuario->saldo_IVA < 0 ? 0 : $usuario->saldo_IVA;
			 
		}

		
		$request= \Config\Services::request();
		$response= \Config\Services::response();

		if( ! is_null($request->getHeader("formato"))   &&  $request->getHeader("formato")->getValue() == "JSON")
		return $response->setJSON(  ["data"=>    $retornar ] );
		else 
		return $retornar;
		 
	}







	public function email_cierre_mes($idcierre)
	{
		/*******Envio de correo */
		$PARAMs = (new Parametros_model())->first();

		$dest =  is_null($PARAMs) ?  ""  :  $PARAMs->EMAIL;
		if ($dest != "") {
			//Parametros
			$cierre_mes = (new Estado_mes_model())->find($idcierre);
			//CLIENTE
			$CODCLIENTE = $cierre_mes->codcliente;
			$MES = $cierre_mes->mes;
			$ANIO = $cierre_mes->anio;
			$CLIENTE = (new Usuario_model())->find($CODCLIENTE);

			//obtener registro del cierre de mes recientemente realizado

			$parametros = [];
			//*****totales*** */
			$TOTALES = $this->totales_mes($MES, $ANIO, $CODCLIENTE, "ARRAY");
			/***Facturas anuladas */
			$ANULADAS =  (new Ventas_model())
				->select("factura")
				->where("codcliente", $CODCLIENTE)
				->where("MONTH(fecha)", $MES)
				->where("YEAR(fecha)", $ANIO)
				->where("ESTADO", "B")->get()->getResult();

			$parametros = array_merge(['CLIENTE' => $CLIENTE, 'ANULADAS' => $ANULADAS,  'MES'=>$MES, 'ANIO'=>$ANIO],  $TOTALES);

			$correo = new Correo();
			$correo->setDestinatario($dest);
			$correo->setAsunto("Cierre de Mes");
			$correo->setParametros($parametros);
			$correo->setMensaje("movimientos/cierre_mes/email");
			$correo->enviar();
		}

		/********* */
	}





	/**
	 * Devuelve una Vista Html de los datos numericos relativos al Mes, antes de efectuar el Cierre
	 */
	public function view_cierre_mes($MES = NULL,  $ANIO =  NULL, $CLIENTE =   NULL)
	{
		$MES_ = is_null($MES) ?  date("m") :  $MES;
		$ANIO_ =  is_null($ANIO) ?  date("Y") :  $ANIO;
		$codcliente =  $this->getClienteId();
		$clienteModel= (new Usuario_model())->find(  $codcliente);

		$susParametros = [];
		$susParametros['codcliente']=  $codcliente;
		$susParametros['error']=[];


		$ESTA_CERRADO = $this->esta_cerrado($MES_, $ANIO_);
		if ($ESTA_CERRADO) {
			//setear un mensaje
			array_push(  $susParametros['error'], " ESTE MES YA HA SIDO CERRADO" ) ;
		}


		//Usuario habilitado para el servicio
		$SERVICIO_HABILITADO =  (new Usuario())->servicio_habilitado($codcliente);
		if (array_key_exists("msj",  $SERVICIO_HABILITADO))
			{//Mostrar con un mensaje de aviso de pago
				echo "no habil";
			array_push(  $susParametros['error'], $SERVICIO_HABILITADO['msj']) ; }
		 
//Otros controles
		$mesesAnterioresAbiertos = ($this->mesesAnterioresAbiertos($MES_, $ANIO_,  $codcliente, false));

		if (!is_null($mesesAnterioresAbiertos))
			array_push(  $susParametros['error'], $mesesAnterioresAbiertos['msj']) ; 

		//Meses posteriores	 
		//Adjuntar los totales en el mes a los params
		$totalesMesAntesDeCerrar = $this->totales_mes($MES_, $ANIO_,  $codcliente, "ARRAY");
		
		if( ! $this->esta_cerrado($MES_,  $ANIO_))
		//usar saldo inicial de usuario 
		$totalesMesAntesDeCerrar['saldo_anterior']= $clienteModel->saldo_IVA;

		//Concatenar los errores
		
		$susParametros['error']=  join("/",  $susParametros['error']);
 
		$susParametros = array_merge($susParametros,  $totalesMesAntesDeCerrar);


		if ($this->request->isAJAX())
			return view("movimientos/cierre_mes/ajax", $susParametros);
		else
			return view("movimientos/cierre_mes/index", $susParametros);
	}




	public function mesesAnterioresAbiertos($MES, $ANIO, $CODCLIENTE, $JSON = TRUE)
	{

		$M = (new Estado_mes_model())
			->where("codcliente",  $CODCLIENTE)
			->where("estado", "P")
			->groupStart()
			->groupStart()
			->where("mes  <",  $MES)
			->where("anio",  $ANIO)
			->groupEnd()
			->orGroupStart()
			->where("anio <",  $ANIO)
			->groupEnd()
			->groupEnd()
			->orderBy("mes", "DESC")
			->first();
		if (is_null($M))  return NULL;

		$nombreMes_TARGET = Utilidades::monthDescr($MES);
		$nombreMes_ABIERTO = Utilidades::monthDescr($M->mes);
		if ($JSON)
			return $this->response->setJSON(['msj' => "No es posible cerrar  $nombreMes_TARGET . El mes de $nombreMes_ABIERTO aún esta abierto ",  'code' => "500"]);
		else return ['msj' => "No es posible cerrar  $nombreMes_TARGET . El mes de $nombreMes_ABIERTO aún esta abierto ",  'code' => "500"];
	}


	public function mesesPosterioresCerrados($MES, $ANIO, $CODCLIENTE, $JSON = TRUE)
	{

		$M = (new Estado_mes_model())
			->where("codcliente",  $CODCLIENTE)
			->where("estado <>", "P")
			->groupStart()
			->groupStart()
			->where("mes  >",  $MES)
			->where("anio",  $ANIO)
			->groupEnd()
			->orGroupStart()
			->where("anio  >",  $ANIO)
			->groupEnd()
			->groupEnd()
			->orderBy("mes", "DESC")
			->first();
		if (is_null($M))  return NULL;

		$nombreMes_TARGET = Utilidades::monthDescr($MES);
		$nombreMes_CERRADO= Utilidades::monthDescr($M->mes);
		if ($JSON)
			return $this->response->setJSON(['msj' => "No es posible cerrar  $nombreMes_TARGET . El mes $nombreMes_CERRADO ya ha sido cerrado ",  'code' => "500"]);
		else return ['msj' => "No es posible cerrar  $nombreMes_TARGET . El mes $nombreMes_CERRADO ya ha sido cerrado ",  'code' => "500"];
	}




	//recibir mes y anio como parametros get
	public function  cierre_mes($MES,  $ANIO)
	{

		$CODCLIENTE =  $this->isAPI() ?  $this->getClienteId() :    session("id");
		//Validacion 1no cerrar si no se esta al dia con el pago
		$habilitado =  (new Usuario())->servicio_habilitado($CODCLIENTE);
		if (array_key_exists("msj",  $habilitado))
			return $this->response->setJSON(['msj' => "Operación no disponible. Revise su estado de pago",  'code' => "500"]);

		//Validacion 2  No puede cerrar un mes cuando el anterior esta abierto
		$mesesAnterioresAbiertos = ($this->mesesAnterioresAbiertos($MES, $ANIO,  $CODCLIENTE));
		if (!is_null($mesesAnterioresAbiertos))   return $mesesAnterioresAbiertos;
		//Validacion 3 No puede cerrarse un mes cuando uno  posterior ha sido cerrado. Este caso especial
		//se da al intentar cerrar un mes que es anterior al MES INICIADOR, es decir el mes con el cual se inicio 
		//por primera vez el registro de operaciones  en el sistema
		$mesesPosterioresCerrados = ($this->mesesPosterioresCerrados($MES, $ANIO,  $CODCLIENTE));
		if (!is_null($mesesPosterioresCerrados))   return $mesesPosterioresCerrados;

		//Validacion 4 no cerrar un mes dos veces
		if ($this->esta_cerrado($MES,  $ANIO)) {
			if ($this->esta_cerrado_anio($ANIO))
				return $this->response->setJSON(['msj' => "No permitido. El Ejercicio ya cerró.",  'code' => "500"]);
			else {
				$nom_mes = Utilidades::monthDescr($MES);
				return $this->response->setJSON(['msj' => "No permitido. El Mes de $nom_mes ya está cerrado.",  'code' => "500"]);
			}
		}

		//Validacion 5 Clave de marangatu
		$clave_marangatu = (new Usuario())->clave_marangatu_definida($CODCLIENTE);
		if (!$clave_marangatu)
			return $this->response->setJSON(['msj' => "Para cerrar este mes, proporcione su clave de acceso de Marangatu para continuar",  'code' => "500"]);


		//numeros
		$cf =  (new Compra())->total_($CODCLIENTE,  $MES, $ANIO);
		$df = (new Venta())->total_($CODCLIENTE, $MES, $ANIO);
		$reten = (new Retencion())->total_($CODCLIENTE, $MES,  $ANIO);

		$total_importe_compras =  $cf->total10 +  $cf->total5;
		$total_importe_ventas =  $df->total10 +  $df->total5;
		$total_importe_retencion =  $reten->importe;

		//A favor del contribuyente
		$s_contri =  intval($cf->iva1) +  intval($cf->iva2) + intval($reten->importe);
		//A favor de hacienda
		$s_fisco =  intval($df->iva1) +  intval($df->iva2);
		//Saldo inicial del mes
		//tomar el saldoIVA de usuario
		//$saldo_ante= (new Usuario_model())->find($CODCLIENTE)->saldo_IVA;
		
		$saldo = ($s_contri) -  $s_fisco;

		$data_cliente = (new Usuario_model())->find($CODCLIENTE);
		//saldo anterior
		$saldo_ante =    $data_cliente->saldo_IVA ; //$this->calcular_saldo_anterior( $MES, $ANIO, $CODCLIENTE);

		//PAGOS
		$total_en_pagos = (new Estado_mes_model())->select('if( SUM(pago) is null, 0, SUM(PAGO) ) AS PAGOS ')

			->where("codcliente",  $CODCLIENTE)
			->where("mes",  $MES)
			->where("anio",  $ANIO)
			->first();
		$DATOS = [
			'codcliente' => $CODCLIENTE,
			'ruc' =>  $data_cliente->ruc,
			'dv' =>   $data_cliente->dv,
			'mes' => $MES,
			'anio' => $ANIO,
			't_impo_compras' => $total_importe_compras,
			't_impo_ventas' => $total_importe_ventas,
			't_impo_retencion' =>  $total_importe_retencion,
			't_i_compras' => (intval($cf->iva1) +  intval($cf->iva2)),
			't_i_ventas' => $s_fisco,
			't_retencion' => (intval($reten->importe)),
			'saldo' => $saldo,
			'saldo_inicial' =>  $saldo_ante,
			'estado' => 'C',
			'pago' => $total_en_pagos->PAGOS
		];

		$db =  \Config\Database::connect();
		$respuesta = "";
		$db->transStart();
		try {

			//Estado mes
			$periodo_a_cerrar = (new Estado_mes_model())->where("mes", $MES)->where("anio", $ANIO)
				->where("codcliente", $this->getClienteId())->first();
			//Actualizar registro de mes pendiente

			(new Estado_mes_model())->where("mes", $MES)->where("anio", $ANIO)
				->where("codcliente", $this->getClienteId())
				->where("regnro", $periodo_a_cerrar->regnro)
				->set($DATOS)->update();

			$id_cierre =  $periodo_a_cerrar->regnro;

			//$id_cierre = $cierre->insert($DATOS);
			//actualizar saldo en Usuario
			$saldoGeneral = $saldo + $saldo_ante;
			(new Usuario_model())->where("regnro", $this->getClienteId())->set(['saldo_IVA' =>  $saldoGeneral])
				->update();
			if ($MES == "12") //CERRAR ANIO
				$this->cierre_anio($ANIO);
			$db->transCommit();
			//Notificar

			$this->email_cierre_mes($id_cierre);
			$respuesta = ['data' =>  $id_cierre,  'code' => "200"];
		} catch (Exception $ex) {
			$db->transRollback();
			$respuesta = ['msj' => "Comunique este error a su proveedor de servicios: $ex",  'code' => "500"];
		}
		$db->transComplete();
		return $this->response->setJSON($respuesta);
	}




	//Lista detalles de los movimientos en el mes, hace inferencia sobre los valores de los meses y el anio,
	//Deberia ser Mes Anio IdCliente
	/**
	 * @return JSON
	 */
	public  function resumen_mes($Month = NULL,  $Year = NULL, $cod_cliente = NULL)
	{


		$MES =  is_null($Month)  ?  date("m")  :  $Month;
		$ANIO =  is_null($Year)  ?  date("Y")  :  $Year;

		//Deducir codigo de cliente
		$CodigoClienteEnSesion = $this->getClienteId();
		$CodigoDeCliente =  is_null($cod_cliente) ?  $CodigoClienteEnSesion : $cod_cliente;
		$Cliente = (new Usuario_model())->find($CodigoDeCliente);

		//El saldo anterior
		$saldo_anterior =  $this->saldo_mes_anterior($CodigoDeCliente, $Month, $Year);

		$Condicion = "WHERE codcliente=$CodigoDeCliente AND YEAR(fecha)=$ANIO and MONTH(fecha)=$MES";
		$db = \Config\Database::connect();
		$query_Str = "select 'IVA COMPRA' as Descripcion, factura as comprobante, fecha, importe1 as IVA_10, importe2 as IVA_5, importe3 as EXENTA, total from compras 
		 $Condicion
		union 
		
		select  if( estado='B', 'IVA VENTA (ANULADA)', 'IVA VENTA' )  as Descripcion, factura as  comprobante, fecha,importe1 as IVA_10, importe2 as IVA_5, importe3 as EXENTA,  total from ventas 
		  $Condicion 
		union
		
		select   'RETENCION' as Descripcion, retencion as 'comprobante', fecha, '0' as IVA_10, '0' as IVA_5, '0' as EXENTA, importe AS total  from  retencion 
		 $Condicion";

		$query = $db->query($query_Str);
		$results = $query->getResultArray();

		/**
		 * Totales 
		 */
		$TOTAL_COMPRA = (new Compra())->total_mes($cod_cliente, $MES, $ANIO);
		$TOTAL_VENTA = (new Venta())->total_mes($cod_cliente, $MES, $ANIO);
		$TOTAL_RETENCION = (new Retencion())->total_mes($cod_cliente, $MES, $ANIO);
		$SALDO_ACTUAL =
			round($TOTAL_COMPRA->iva1 + $TOTAL_COMPRA->iva2) + intval($TOTAL_RETENCION->importe)
			- (round($TOTAL_VENTA->iva1 + $TOTAL_VENTA->iva2));

		$LosTotales = [
			'IVA_CF_10' =>  round($TOTAL_COMPRA->iva1),
			'IVA_CF_5' =>  round($TOTAL_COMPRA->iva2),
			'COMPRA_EXENTA' =>  $TOTAL_COMPRA->iva3,
			'IVA_DF_10' =>  round($TOTAL_VENTA->iva1),
			'IVA_DF_5' =>  round($TOTAL_VENTA->iva2),
			'VENTA_EXENTA' =>  $TOTAL_VENTA->iva3,
			'RETENCION' =>   $TOTAL_RETENCION->importe,
			'SALDO_ANTE' => $saldo_anterior,
			'SALDO' =>  $SALDO_ACTUAL
		];

		$response = \Config\Services::response();

		//Preparar la respuesta
		$DescripcionMesAnio =   (Utilidades::monthDescr($MES) . "/" . $ANIO);
		$TITULO = "Cierre $DescripcionMesAnio RUC: " . $Cliente->ruc . "-" . $Cliente->dv;
		$RESPUESTA =  ['data' => $results, 'totales' => $LosTotales, 'title' => $TITULO];
		return $response->setJSON($RESPUESTA);
	}







	//Resumen del mes en base a estados de sesion actual
	public function info_mes_cierre($cod_cliente,   $Month = NULL,  $Year = NULL)
	{
		$RESPUESTA =  $this->resumen_mes($cod_cliente, $Month, $Year);

		return $RESPUESTA;
	}


	/*
*****
***************
***************
**    **    **
*
	*RESUMEN DE ANIO
*
********************
******************
********   *********
	*/

	public function comparativo_periodos($ANIOPARAM = NULL)
	{

		//Ejercicio
		$ANIO = is_null($ANIOPARAM) ? date("Y") :  $ANIOPARAM;

		//Totales comparativos entre periodos del anio seleccionado
		$res = $this->comparativo_anio($ANIO, $this->getClienteId(), "ARRAY");

		if ($this->isAPI())
			return $this->response->setJSON(["data" =>  ['anio' => $ANIO, 'meses' =>  $res], "code" => "200"]);

		if ($this->request->isAJAX())
			return view("movimientos/comparativos/periodos_ajax",  ['ANIO' => $ANIO, 'comparativo1' =>  $res]);

		//No ajax request
		//listar ejercicios cerrados del cliente
		$codcliente =  $this->getClienteId();
		//Listar los anios registrados por el cliente
		$anios =  (new Estado_anio_model())
			->select("anio")
			->where("codcliente",   $codcliente)
			->get()->getResult();
		return view("movimientos/comparativos/periodos",   ['ANIOS' =>   $anios]);
	}







	/**
	 * @return ARRAY
	 * Totales en IVA CF10 IVACF5
	 *  IVADF10 IVADF5 RETENCION VENTAS-ANULADAS
	 * @param ANIO
	 * @param CLIENTEID
	 * @param FORMATO ARRAY|JSON
	 */
	public function totales_anio($ANIO,  $CLIENTE = NULL, $FORMATO = "JSON")
	{
		$CodigoDeCliente = is_null($CLIENTE) ? $this->getClienteId() :  $CLIENTE;

		$supertotales = $this->comparativo_anio($ANIO, $CodigoDeCliente, "ARRAY");
		$ElSaldoAnteriorEnElAnio = 0;
		$SeteadoSaldoInicial = FALSE;


		$YEAR = is_null($ANIO)  ? date("Y") :   $ANIO;

		//Total en importes
		$total_importe_compras = 0;
		$total_importe_ventas = 0;
		$total_importe_retenci = 0;
		$s_contri = 0;
		$s_fisco = 0;
		//Saldo
		$saldo = 0;
		//saldo inicial
		$saldo_ini = 0;

		foreach ($supertotales as $supertotal) :
			$total_importe_compras += $supertotal['t_impo_compras'];
			$total_importe_ventas += $supertotal['t_impo_ventas'];
			$total_importe_retenci += $supertotal['t_retencion'];
			$s_contri += $supertotal['t_i_compras'];
			$s_fisco += $supertotal['t_i_ventas'];
			//Saldo
			$saldo +=   $supertotal['saldo'];
			//saldo inicial (Obtener el saldo con el que se comenzo el anio)
			if (!$SeteadoSaldoInicial &&  $supertotal['saldo_inicial'] != 0) {
				$ElSaldoAnteriorEnElAnio = $supertotal['saldo_inicial'];
				$SeteadoSaldoInicial = TRUE;
			}

			$saldo_ini += $supertotal['saldo_inicial'];

		endforeach;
		//Anulados En venta
		$fv_anuladas = (new Venta())->anuladas_($CLIENTE, NULL,  $YEAR);
		$fv_cant =  $fv_anuladas->cantidad;
		$fv_tot =  $fv_anuladas->total;
		$fv_iva =  $fv_anuladas->total_iva;
		//total en pago
		$total_en_pagos = (new Estado_mes_model())->select('if( SUM(pago) is null, 0, SUM(PAGO) ) AS PAGOS ')
			->where("codcliente",  $CLIENTE)->where("anio",  $YEAR)
			->first();

		$TOTALES_ANIO_RESPONSE =
			[
				'importe_compras' => $total_importe_compras,
				'importe_ventas' => $total_importe_ventas,
				'importe_retenc' =>  $total_importe_retenci,
				'compras' => $s_contri,
				'ventas' => $s_fisco,
				'ventas_anuladas_cant' => $fv_cant,
				'ventas_anuladas_tot' => $fv_tot,
				'ventas_anuladas_iva' => $fv_iva,
				'retencion' => $total_importe_retenci,
				'saldo' => $saldo,
				'saldo_inicial' => $ElSaldoAnteriorEnElAnio,
				//'total_saldo_inicial'=>$saldo_ini,
				'pago' =>  $total_en_pagos->PAGOS
			];
		//Segun Formato
		if ($FORMATO == "ARRAY")   return $TOTALES_ANIO_RESPONSE;
		if ($FORMATO == "JSON") return $this->response->setJSON($TOTALES_ANIO_RESPONSE);
	}






	public function email_cierre_anio($idcierre)
	{
		/*******Envio de correo */
		$PARAMs = (new Parametros_model())->first();

		$dest =  is_null($PARAMs) ?  ""  :  $PARAMs->EMAIL;
		if ($dest != "") {
			//Parametros
			$cierre_mes = (new Estado_anio_model())->find($idcierre);
			$CLIENTE = (new Usuario_model())->find($cierre_mes->codcliente);

			//obtener registro del cierre de mes recientemente realizado

			$parametros = [];
			if (!is_null($cierre_mes)) {
				$parametros['cliente'] =  ($CLIENTE->ruc) . "-" . ($CLIENTE->dv);
				$parametros['compras'] =  $cierre_mes->t_i_compras;
				$parametros['ventas'] =  $cierre_mes->t_i_ventas;
				$parametros['retencion'] =  $cierre_mes->t_retencion;
				$parametros['s_contri'] =  (intval($cierre_mes->t_i_compras) + intval($cierre_mes->t_retencion));
				$parametros['s_fisco'] =  intval($cierre_mes->t_i_ventas);
				$parametros['saldo'] =  $parametros['s_contri'] -  $parametros['s_fisco'];
				$parametros['saldo_anterior'] =  $cierre_mes->saldo_inicial;
			}
			//******** */
			$correo = new Correo();
			$correo->setDestinatario($dest);
			$correo->setAsunto("Cierre de Año");
			$correo->setParametros($parametros);
			$correo->setMensaje("movimientos/cierre_anio/email");
			$correo->enviar();
		}

		/********* */
	}







	public function  cierre_anio($ANIO)
	{

		$CODCLIENTE =  $this->isAPI() ?  $this->getClienteId() :    session("id");
		$this->crear_ejercicio($ANIO);

		//no cerrar si no se esta al dia con el pago
		if (!((new Usuario())->servicio_habilitado($CODCLIENTE)))
			return $this->response->setJSON(['msj' => "Operación no disponible. Revise su estado de pago",  'code' => "500"]);
		//no cerrar un ejercicio dos veces
		if ($this->esta_cerrado_anio($ANIO))
			return $this->response->setJSON(['msj' => "No permitido. El Año $ANIO ya está cerrado.",  'code' => "500"]);


		$cerrado = (new Estado_anio_model())->where("codcliente", $CODCLIENTE)->where("anio", $ANIO)->first();

		//numeros
		$totales_cierre = $this->totales_anio($ANIO, $CODCLIENTE, "ARRAY");
		$total_importe_compras =  $totales_cierre['importe_compras'];
		$total_importe_ventas =  $totales_cierre['importe_ventas'];
		$total_importe_retenc =  $totales_cierre['importe_retenc'];

		/**Importe IVA  */
		$cf =  $totales_cierre['compras'];
		$df = $totales_cierre['ventas'];
		$reten = $totales_cierre['retencion'];

		//A favor del contribuyente
		$s_contri =  intval($cf) + intval($reten);
		//A favor de hacienda
		$s_fisco =  intval($df);
		//Saldo
		$saldo_ante = $cerrado->saldo_inicial;
		$saldo = ($s_contri) -  $s_fisco;
		$anio = $ANIO;
		$data_cliente = (new Usuario_model())->find($CODCLIENTE);
		//Obtener total en pagos
		$total_en_pagos = (new Estado_mes_model())->select('if( SUM(pago) is null, 0, SUM(PAGO) ) AS PAGOS ')
			->where("codcliente",  $CODCLIENTE)->where("anio",  $ANIO)
			->first();

		$DATOS = [
			'codcliente' => $CODCLIENTE,
			'ruc' =>  $data_cliente->ruc,
			'dv' =>   $data_cliente->dv,
			'anio' => $anio,
			't_i_compras' => $cf,
			't_i_ventas' => $df,
			't_retencion' => $reten,
			't_impo_compras' => $total_importe_compras,
			't_impo_ventas' => $total_importe_ventas,
			't_impo_retencion' => $total_importe_retenc,
			'saldo' => $saldo,
			'estado' => "C",
			'pago' =>  $total_en_pagos->PAGOS
		];
		$db = \Config\Database::connect();

		$db->transStart();
		$respuesta = [];
		try {
			(new Estado_anio_model())->where("regnro", $cerrado->regnro)->set($DATOS)->update();
			//actualizar en usuario mis datos
			$anio_a_Cerrar =  intval($saldo) + intval($saldo_ante);
			//	(new Usuario_model())->where("regnro", $this->getClienteId())->set(['saldo_IVA' =>  $anio_a_Cerrar])
			//		->update();
			$db->transCommit();
			//Crear otro ejercicio
			$SaldoInicialNuevo = $saldo + $saldo_ante;
			$this->crear_ejercicio($ANIO + 1, $SaldoInicialNuevo);
			//Notificar 
			//	$this->email_cierre_anio($cerrado->regnro);
			$respuesta = $this->response->setJSON(['data' =>  $cerrado->regnro,  'code' => "200"]);
		} catch (Exception $ex) {
			$db->transRollback();
			$respuesta = $this->response->setJSON(['msj' => "Comunique este error a su proveedor de servicios: $ex",  'code' => "500"]);
		}
		$db->transComplete();
		return $respuesta;
	}





	public  function resumen_anio($Year, $cod_cliente = NULL)
	{

		$ANIO =  is_null($Year)  ?  date("Y")  :  $Year;
		//Deducir el cod de cliente
		$CodigoClienteEnSesion = $this->getClienteId();
		$CodigoDeCliente = is_null($cod_cliente) ?  $CodigoClienteEnSesion :  $cod_cliente;
		$Cliente = (new Usuario_model())->find($CodigoDeCliente);


		//Obtener totales de cada mes
		$totales_meses = (new Estado_mes_model())
			->select("mes,anio, t_i_compras, t_i_ventas, t_retencion, saldo, saldo_inicial")
			->where("codcliente", $cod_cliente)->where("anio", $ANIO)->get()->getResultArray();

		//el mes actual ya se cerro? sino es asi, calcular su total, porque aun no se incluye en la tabla de estados_meses
		if (!$this->esta_cerrado(date("m"), $ANIO)) {
			//----------LOS SALDOS EN ESTE MES, totales del mes actual aun no cerrado
			$TOTAL_COMPRA_m = (new Compra())->total_mes($cod_cliente,  date("m"),  $ANIO);
			$TOTAL_VENTA_m = (new Venta())->total_mes($cod_cliente,  date("m"), $ANIO);
			$TOTAL_RETENCION_m = (new Retencion())->total_mes($cod_cliente,  date("m"), $ANIO);

			$total_ahora_compras = (round($TOTAL_COMPRA_m->iva1 + $TOTAL_COMPRA_m->iva2));
			$total_ahora_ventas =  (round($TOTAL_VENTA_m->iva1 + $TOTAL_VENTA_m->iva2));
			$total_retencion =  intval($TOTAL_RETENCION_m->importe);
			$saldo = ($total_ahora_compras + $total_retencion)  -  $total_ahora_ventas;
			$saldo_anterior_a_este =  $this->saldo_mes_anterior($cod_cliente,  date("m"),   $ANIO);
			/************** END TOTAL DEL MES ACTUAL */

			$ESTADO_ESTE_MES = [
				'mes' =>  date("m"),
				'anio' => $ANIO,
				't_i_compras' =>  $total_ahora_compras,
				't_i_ventas' => $total_ahora_ventas,
				't_retencion' =>   $total_retencion,
				'saldo' =>  $saldo,
				'saldo_inicial' =>  $saldo_anterior_a_este
			];
			array_push($totales_meses,   $ESTADO_ESTE_MES);
		}
		/**
		 * Totales 
		 */
		$TOTAL_COMPRA = (new Compra())->total_anio($cod_cliente, $ANIO);
		$TOTAL_VENTA = (new Venta())->total_anio($cod_cliente, $ANIO);
		$TOTAL_RETENCION = (new Retencion())->total_anio($cod_cliente, $ANIO);

		$LosTotales = [
			'IVA_CF_10' =>  round($TOTAL_COMPRA->iva1),
			'IVA_CF_5' =>  round($TOTAL_COMPRA->iva2),
			'COMPRA_EXENTA' =>  $TOTAL_COMPRA->iva3,
			'IVA_DF_10' =>  round($TOTAL_VENTA->iva1),
			'IVA_DF_5' =>  round($TOTAL_VENTA->iva2),
			'VENTA_EXENTA' =>  $TOTAL_VENTA->iva3,
			'RETENCION' =>   $TOTAL_RETENCION->importe
		];

		$response = \Config\Services::response();

		$TITULO = "Cierre " . ($ANIO) . " RUC: " . $Cliente->ruc . "-" . $Cliente->dv;
		$RESPUESTA =  ['data' => $totales_meses, 'totales' => $LosTotales, 'title' => $TITULO];
		return $response->setJSON($RESPUESTA);
	}





	public function info_anio_cierre($cod_cliente,    $Year = NULL)
	{



		$ANIO =  is_null($Year)  ?  date("Y")  :  $Year;

		$Cliente = (new Usuario_model())->find($cod_cliente);

		$UltimoCierre = (new Estado_anio_model())->where("codcliente", $cod_cliente)->where("estado", "C")->orderBy("created_at", "DESC")->first();

		$ANIO = is_null($UltimoCierre) ? ($ANIO)  :   $UltimoCierre->anio;

		$cierres_por_mes = (new Estado_mes_model())->where("anio", $ANIO)->where("codcliente", $cod_cliente)
			->select("  mes as Mes, t_i_compras as 'IVA Crédito fiscal' , t_i_ventas as 'IVA Débito fiscal',
		t_retencion as 'Retenciones', saldo as Saldo ")
			->orderBy("mes", "ASC")
			->get()->getResultArray();

		/**
		 * Totales 
		 */
		$t_compras = 0;
		$t_ventas = 0;
		$t_retencion = 0;
		$_t_SALDO = 0;

		foreach ($cierres_por_mes as $row) {
			$t_compras += intval($row['IVA Crédito fiscal']);
			$t_ventas += intval($row['IVA Débito fiscal']);
			$t_retencion += intval($row['Retenciones']);
		}
		$Saldo__ =  ($t_compras + $t_retencion) - $t_ventas;

		$Totales = [
			'Mes' => 'TOTALES', 'IVA Crédito fiscal' => $t_compras, 'IVA Débito fiscal' => $t_ventas,
			'Retenciones' => $t_retencion, 'Saldo:' => $Saldo__
		];
		array_push($cierres_por_mes,  $Totales);
		$response = \Config\Services::response();

		$TITULO = "Cierre Año " . $ANIO . " RUC: " . $Cliente->ruc . "-" . $Cliente->dv;
		$RESPUESTA =  ['data' => $cierres_por_mes, 'title' => $TITULO];
		(new Estado_anio_model())->where("regnro", $UltimoCierre->regnro)->set("estado", "R")->update();
		return $response->setJSON($RESPUESTA);
	}











	//Funciones de consistencia

	public function crear_periodos_ejercicios($fecha_compro)
	{
		$mes_fecha_compro =   date("m",   strtotime($fecha_compro));
		$anio_fecha_anio =   date("Y",   strtotime($fecha_compro));

		$this->crear_ejercicio($anio_fecha_anio);
		$this->crear_periodo($mes_fecha_compro,  $anio_fecha_anio);
	}

	//Agrega un registro para nuevo ejercicio si aun no existe uno
	public function crear_ejercicio($ANIO =  NULL,  $SALDO_INICIAL = NULL)
	{
		if ($this->isAdminView())   return;

		$Anio =  is_null($ANIO) ? date("Y") :   $ANIO;

		$cliente = $this->getClienteId();
		$cliente_obj = (new Usuario_model())->find($cliente);

		$SaldoIni = is_null($SALDO_INICIAL) ?  $cliente_obj->saldo_IVA  :  $SALDO_INICIAL;


		$ejercicio_listo = (new Estado_anio_model())->where("codcliente", $cliente)->where("anio", $Anio)->first();
		if (is_null($ejercicio_listo)) {

			$nuevo_ejercicio = [
				'codcliente' => $cliente,
				'ruc' => $cliente_obj->ruc,
				'dv' => $cliente_obj->dv,
				'anio' =>  $Anio,
				't_i_compras' => 0,
				't_i_ventas' => 0,
				't_retencion' => 0,
				'saldo' => 0,
				'saldo_inicial' => $SaldoIni
			];

			(new Estado_anio_model())->insert($nuevo_ejercicio);
		}
	}



	public function crear_periodo($MES, $ANIO)
	{
		if ($this->isAdminView())   return;
		$SaldoIni = 0;

		$cliente = $this->getClienteId();
		$cliente_obj = (new Usuario_model())->find($cliente);
		$periodoListo = (new Estado_mes_model())->where("codcliente", $cliente)
			->where("mes", $MES)
			->where("anio", $ANIO)->first();

		if (is_null($periodoListo)) {
			$nuevoPeriodo = [
				'codcliente' => $cliente,
				'ruc' => $cliente_obj->ruc,
				'dv' => $cliente_obj->dv,
				'anio' =>  $ANIO,
				'mes' =>  $MES,
				't_i_compras' => 0,
				't_i_ventas' => 0,
				't_retencion' => 0,
				't_impo_compras' => 0,
				't_impo_ventas' => 0,
				't_impo_retencion' => 0,
				'pago' => 0,
				'saldo' => 0,
				'saldo_inicial' => $SaldoIni,
				'estado' => "P"
			];
			(new Estado_mes_model())->insert($nuevoPeriodo);
		}
	}







	public function mes_anio_fuera_de_tiempo($MES, $ANIO)
	{

		$response = \Config\Services::response();

		//Anio pasado
		if ($ANIO < date("Y"))
			return  $response->setJSON(['msj' => "No puede registrar una transacción para un ejercicio ya finalizado",  'code' => "500"]);
		//anio futuro
		if ($ANIO > date("Y"))
			return  $response->setJSON(['msj' => "No puede registrar una transacción para un ejercicio futuro",  'code' => "500"]);

		//Mes pasado
		if ($ANIO == date("Y")   &&  $MES < date("m"))
			return  $response->setJSON(['msj' => "No puede registrar una transacción para un período ya finalizado",  'code' => "500"]);
		//Mes futuro
		if ($ANIO == date("Y")   &&  $MES > date("m"))
			return  $response->setJSON(['msj' => "No puede registrar una transacción para un período futuro",  'code' => "500"]);


		return NULL;
	}


	//lA FECHA DE LA OPERACION NO PUEDE SER DEL PASADO NI DEL FUTURO
	//no puede registrarse una operacion en un mes ya cerrado  ni en un ejercicio ya finalizado
	public function fecha_operacion_invalida($FECHA)
	{
		$response = \Config\Services::response();
		$fecha_comprobante =  explode("-",  $FECHA);
		$mes =  $fecha_comprobante[1];
		$anio =  $fecha_comprobante[0];
		$fuera_de_tiempo =  $this->mes_anio_fuera_de_tiempo($mes,  $anio);
		if (!is_null($fuera_de_tiempo))  return $fuera_de_tiempo;

		$estaCerrado = $this->esta_cerrado($mes, $anio);
		if ($estaCerrado) return $response->setJSON(['msj' => "No puede registrar la transacción para un período ya cerrado", "code" => "500"]);
		else return  NULL;
	}



	public function operacion_habilitada($ClienteCOD, $fecha_compro)
	{
		$response =  \Config\Services::response();

		$mes_fecha_compro =   date("m",   strtotime($fecha_compro));
		$anio_fecha_anio =   date("Y",   strtotime($fecha_compro));

		//Al dia
		$habilitado =  (new Usuario())->servicio_habilitado($ClienteCOD);
		if (array_key_exists("msj",  $habilitado))
			return $this->response->setJSON(['msj' =>  $habilitado['msj'],  'code' => "500"]);

		elseif ($this->esta_cerrado($mes_fecha_compro,  $anio_fecha_anio)) {
			$mensaj = $this->esta_cerrado_descripcion($mes_fecha_compro,  $anio_fecha_anio);
			return  $response->setJSON(['msj' =>  $mensaj,  "code" =>  "500"]);
		} else  return NULL;
	}



	public function  esta_cerrado_anio($ANIO = NULL)
	{

		$_anio = is_null($ANIO) ?  date("Y") :  $ANIO;
		$cod_cliente = $this->getClienteId();
		$Anio = (new Estado_anio_model())->where("codcliente", $cod_cliente)->where("anio", $_anio)->first();
		if (!is_null($Anio)  &&   $Anio->estado != "P")
			return true;
		else return false;
	}

	public function  esta_cerrado_descripcion($MES = NULL,  $ANIO = NULL)
	{
		$_mes = is_null($MES) ?  date("m") :  $MES;
		$_anio = is_null($ANIO) ?  date("Y") :  $ANIO;

		$cod_cliente = $this->getClienteId();
		$Anio = (new Estado_anio_model())->where("codcliente", $cod_cliente)->where("anio", $_anio)->first();
		$Mes = (new Estado_mes_model())->where("codcliente", $cod_cliente)
			->where("anio", $_anio)->where("mes", $_mes)->first();


		if (!is_null($Anio)  &&   $Anio->estado != "P")
			return "El año $ANIO ya ha sido cerrado";
		//ejercicio abierto
		elseif (!is_null($Anio)  &&   $Anio->estado == "P") {
			if (is_null($Mes))  return "El mes aun esta abierto";
			else {
				if ($Mes->estado != "P") return "El mes ya ha sido cerrado";
				else return "El mes aun esta abierto";
			}
		}
		return   "El mes aun esta abierto";
	}

	public function  esta_cerrado($MES = NULL,  $ANIO = NULL)
	{
		$_mes = is_null($MES) ?  date("m") :  $MES;
		$_anio = is_null($ANIO) ?  date("Y") :  $ANIO;

		$cod_cliente = $this->getClienteId();
		$Anio = (new Estado_anio_model())->where("codcliente", $cod_cliente)->where("anio", $_anio)->first();

		$Mes = (new Estado_mes_model())->where("codcliente", $cod_cliente)
			->where("anio", $_anio)->where("mes", $_mes)->first();


		if (!is_null($Anio)  &&   $Anio->estado != "P")
			return true;
		//ejercicio abierto
		elseif (!is_null($Anio)  &&   $Anio->estado == "P") {
			if (is_null($Mes))  return false;
			else return  $Mes->estado != "P";
		}
	}









	//Undo


	public function  deshacer_cierre_mes(  $CLIENTE)
	{
		$db = \Config\Database::connect();

		$db->transStart();
		$respuesta = "";
		try {
			//Obtener el ultimo periodo cerrado
			$ultimoPeriodo= (new Estado_mes_model())
			->where("codcliente", $CLIENTE)
			->where("estado <>", "P")->orderBy("ANIO", "DESC")
			->orderBy("mes", "DESC")->first();
		 
			$MES=  $ultimoPeriodo->mes;
			$ANIO=  $ultimoPeriodo->anio; 

			(new Estado_mes_model())->where("codcliente", $CLIENTE)
				->where("mes",  $MES)
				->where("anio", $ANIO)
				->set([
					't_i_compras' => 0,
					't_i_ventas' => 0,
					't_retencion' => 0,
					't_impo_compras' => 0,
					't_impo_ventas' => 0,
					't_impo_retencion' => 0,
					'pago' => 0,
					'saldo' => 0,
					'saldo_inicial' => 0,
					'estado' => "P"
				])
				->update();
			if ($MES ==  "12") (new Estado_anio_model())->where("codcliente", $CLIENTE)
				->where("anio", $ANIO)
				->set(['estado' =>  'P'])
				->update();
			$db->transCommit();

			$nombreMes= Utilidades::monthDescr( $MES);
			$respuesta =  ['data' => "El mes de $nombreMes, año ($ANIO) ha sido reabierto", 'code' => '200'];
		} catch (Exception $e) {
			$db->transRollback();
			$respuesta =  ['msj' => "Error de servidor",  'code' => '500'];
		}
		$db->transComplete();
		return $this->response->setJSON($respuesta);
	}












	/**
	 * @return Array | json
	 */
	public function  comparativo_anio($ANIO, $CLIENTE = NULL, $FORMATO = "JSON")
	{

		$CodigoDeCliente = is_null($CLIENTE) ? $this->getClienteId() :  $CLIENTE;
		$TodosLosMeses = [];


		$saldoReferencial = 0;

		for ($_mes_ = 1; $_mes_  <= 12; $_mes_++) {


			$totales_mes = $this->totales_mes($_mes_,  $ANIO, $CodigoDeCliente, "ARRAY");

			//	if( $_mes_ == 1)  $saldoReferencial=  $totales_mes['saldo_anterior']; //
			//Importes de comprobantes
			$t_compras = $totales_mes['compras_total_10'] + $totales_mes['compras_total_5'];
			$t_ventas =  $totales_mes['ventas_total_10'] + $totales_mes['ventas_total_5'];
			$t_retencion =  $totales_mes['retencion'];
			//importe en IVA
			$t_i_compras =  $totales_mes['compras_iva10'] + $totales_mes['compras_iva5'];
			$t_i_ventas =  $totales_mes['ventas_iva10'] + $totales_mes['ventas_iva5'];
			//Saldos
			$saldo = $totales_mes['saldo'];
			//Pagos
			$pagos = $totales_mes['pago'];
			//evaluar saldo inicial
			$saldo_ini = $totales_mes['saldo_anterior'];
			if ($t_compras == "0"  &&  $t_ventas == "0" && $t_retencion == "0") $saldo_ini = 0;

			$cada_mes = [
				'mes' => $_mes_,  	't_impo_compras' =>  $t_compras,  't_impo_ventas' => $t_ventas, 't_impo_retencion' => $t_retencion,
				't_i_compras' =>  $t_i_compras,  't_i_ventas' => $t_i_ventas, 't_retencion' => $t_retencion, 'saldo' => $saldo,
				'saldo_inicial' => $saldo_ini,
				'pago' =>  $pagos, 'estado' =>  $totales_mes['estado']
			];
			array_push($TodosLosMeses,  $cada_mes);
		}

		if ($FORMATO == "ARRAY")   return $TodosLosMeses;
		if ($FORMATO == "JSON")
			return	$this->response->setJSON(['data' =>  $TodosLosMeses, 'code' => '200']);
	}






	public function  comparativo_ejercicios($CLIENTE = NULL)
	{

		$Cliente =   is_null($CLIENTE) ?  $this->getClienteId()  : $CLIENTE;
		//Lista de anios registrados en operaciones
		$ejercicios = (new Estado_anio_model())->select("anio")->where("codcliente", $Cliente)
			->get()->getResult();

		//Si la request no viene de la API, ni se realiza por Ajax
		if (!($this->isAPI())  &&   !($this->request->isAJAX()))
			return view("movimientos/comparativos/ejercicios");


		/**Obtencion de data para cada ejercicio */
		$comparativo = [];
		$total_general = [
			"importe_compras" => 0, "importe_ventas" => 0, "importe_retenc" => 0,
			"iva_compras" => 0, "iva_ventas" => 0, "total_iva" => 0,  "pagos" => 0
		];
		foreach ($ejercicios as   $ANIO) :

			$totales_cierre = $this->totales_anio($ANIO->anio, $Cliente, "ARRAY");
			$totales_cierre['anio'] = $ANIO->anio;

			$total_general['importe_compras'] += $totales_cierre['importe_compras'];
			$total_general['importe_ventas'] += $totales_cierre['importe_ventas'];
			$total_general['importe_retenc'] += $totales_cierre['importe_retenc'];
			$total_general['iva_compras'] += $totales_cierre['compras'];
			$total_general['iva_ventas'] += $totales_cierre['ventas'];
			//temppr
			$total_general['total_iva'] += $totales_cierre['saldo'];
			//$total_general['total_iva'] +=  $totales_cierre['compras'] + $totales_cierre['importe_retenc'] - $totales_cierre['ventas'];
			$total_general['pagos'] +=  $totales_cierre['pago'];
			/*
			'importe_compras' 'importe_ventas' 'importe_retenc' 
			'compras' 'ventas'  'ventas_anuladas_cant' 'ventas_anuladas_tot' 'ventas_anuladas_iva' 	'retencion' 
			 	'saldo'  'saldo_inicial' 
			*/
			array_push($comparativo,  $totales_cierre);
		endforeach;


		if ($this->isAPI())
			return $this->response->setJSON(["data" =>     $comparativo, "totales" => $total_general,  "code" => "200"]);
		if ($this->request->isAJAX())
			return view("movimientos/comparativos/ejercicios_ajax",  ['comparativo2' =>  $comparativo]);
	}





	public function mes_activo()
	{
		$cliente = $this->getClienteId();
		$db =  \Config\Database::connect();
		$ULT = "";
		try {
			$ULT = $db->query("
		select month(fecha) as mes from ventas where ventas.codcliente=$cliente and 
		if((select  mes  from estado_mes where codcliente=ventas.codcliente and mes=month(ventas.fecha) 
		and anio=year(ventas.fecha) limit 0,1 ) is null,0, 1)=0   order by ventas.created_at DESC ")->getRow();
		} catch (Exception $e) {
		}
		//FECHA INGRESADA POR USUARIO
		//FECHA REGISTRO EN EL SISTEMA
		if (is_null($ULT)) {

			$incremen = (new Estado_mes_model())
				->where("codcliente",  $cliente)
				->select("mes as activo, mes")
				->orderBy("mes", "DESC")
				->orderBy("anio", "DESC")
				->first();
			if (is_null($incremen))
				return  $this->response->setJSON(['data' => date("m"),  'code' => '200']);
			else
				return  $this->response->setJSON(['data' => $incremen->activo,  'code' => '200']);
		} else
			return  $this->response->setJSON(
				[
					'data' =>    $ULT->mes,   'code' => '200'
				]
			);
	}
}
