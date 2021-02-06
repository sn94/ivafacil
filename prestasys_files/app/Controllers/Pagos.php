<?php
 namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\Correo;
use App\Libraries\pdf_gen\PDF;
use App\Models\Ciudades_model;
use App\Models\Estado_anio_model; 
use App\Models\Pago_model; 
use App\Models\Planes_model;
use App\Models\Rubro_model;
use App\Models\Usuario_model;
use CodeIgniter\Controller; 
use Exception;

 
/**
 * 
 * 
 * Pagos de clientes por los servicios
 */

class Pagos extends Controller {
 
 

	private $API_MODE= true;



	public function __construct(  $api_mode= true)
	{

		$this->API_MODE=  $api_mode;
		date_default_timezone_set("America/Asuncion");
		helper("form");
	}

	 



	private function isAPI(){

        $request = \Config\Services::request();
       $uri = $request->uri;
        if (sizeof($uri->getSegments()) > 0 &&  $uri->getSegment(1) == "api") {
            return true;
        } return false; 
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
	 

	 
 

	 
 
	/**
	 * 
	 * PRIMER PAGO (VERSION TRIAL)
	 */

	 private function prueba_gratuita( $CODCLIENTE){
		$data = $this->request->getRawInput();
		$PLAN= (new Planes_model())->join("usuarios", "usuarios.tipoplan=planes.regnro")
		->where("usuarios.regnro", $CODCLIENTE)
		->first();

		$DIASPLAN=  $PLAN->dias;
		//CALCULO DE FECHA CADUCIDAD PRUEBA GRATIS
		$validez= date("Y-m-d H:i:s",  strtotime( date("Y-m-d H:i:s")." + $DIASPLAN days"  )  );
		$datos = [
			'ruc'=> $data['ruc'],
			'dv'=> $data['dv'],
			'fecha' => date("Y-m-d H:i:s"),
			"validez"=>  $validez,
			"plan" => $data['tipoplan'],
			"concepto" => "PRUEBA GRATUITA",
			"precio" => "0",
			"cliente" => $CODCLIENTE, 
			"estado" => "A"
		];
		$pago = new Pago_model();
		$pago->insert( $datos);
	 }


 

 
 





 

 
	
	public function index($id=NULL, $MES= NULL, $ANIO= NULL)
	{						//cliente id   mes  anio
		$mes= is_null(  $MES )?  date("m") :  $MES;
		$anio=  is_null(  $ANIO ) ?  date("Y") : $ANIO ;

		
		$pagos =	(new Pago_model())->where("cliente",  $id)
			->select(" pagos.regnro, pagos.comprobante, pagos.fecha, pagos.created_at, planes.descr as plan")
			->where("month(pagos.fecha)", $mes)
			->where("year(pagos.fecha)", $anio)
			->join(
				"planes",
				"planes.regnro=pagos.plan"
			);
		$lista_m = $pagos->paginate(10);
		$pager =  $pagos->pager;

		if(  $this->isAPI())
		return $this->response->setJSON(  ['data'=>  $lista_m  ,  'code'=>  '200'  ]);
		else
		return view("admin/clientes/pago_servicio/grill_pagos",  ['pagos' =>  $lista_m, "pager" =>  $pager,  'year'=>  $anio, 'month'=>  $mes]);

	}	
	
	
	public function create(  $id= null){

		if( $this->request->getMethod( true) == "GET")
		{	
			$mes=  date("m") ;
			$anio=   date("Y") ;
			return view( "admin/clientes/pago_servicio/pagos", ['CLIENTE'=>  $id,  'year'=>  $anio, 'month'=>  $mes]);}
		else 

		{
			$data_req = $this->request->getRawInput();
			$Cliente_cod = $data_req['cliente'];
			$Cliente_datos = (new Usuario_model())->find($Cliente_cod);
			$PlanDatos= (new Planes_model())->find(  $Cliente_datos->tipoplan);
			//CALCULO DE FECHA CADUCIDAD PRUEBA GRATIS
			$DIASPLAN = $PlanDatos->dias;
			$validez = date("Y-m-d H:i:s",  strtotime(date("Y-m-d H:i:s") . " + $DIASPLAN days"));
			$datos_plus = [
			 
				"ruc"=>  $Cliente_datos->ruc,
				"dv"=>  $Cliente_datos->dv,
				"validez" =>  $validez,
				"plan" => $Cliente_datos->tipoplan, 
				"precio" => $PlanDatos->precio,
			];
			$datos= array_merge( $data_req, $datos_plus );
			$pago = new Pago_model();
			//transaccion
			$db = \Config\Database::connect();

			$db->transStart();
			try {
				$pago->insert($datos);
				$db->transCommit();
				return $this->response->setJSON(['data' => "REGISTRADO",  'code' => "200"]);
			} catch (Exception $ex) {
				$db->transRollback();
				return $this->response->setJSON(['msj' => "$ex",  'code' => "500"]);
			}
			$db->transComplete();
		

		}
	}
 

	   
	 


	/**
	 * recordatorio de pago
	 */
	public function email_recordar_pago( $CODCLIENTE= NULL){
		/*******Envio de correo */
		$Cliente= (new Usuario_model())->find( $CODCLIENTE);
		
		$destinatario=  $Cliente->email;

		//obtener fecha de vencimiento del plan segun ultimo pago
		$ultimopago= (new Pago_model())->where("cliente", $CODCLIENTE)->orderBy("created_at", "DESC")->first();
		$vencimiento=  $ultimopago->validez;
		$dest=  $destinatario == "" ? $this->request->getRawInput("email") :  $destinatario;
		//parametro
		$parametros= ['vencimiento'=>  $vencimiento ];
		$correo= new Correo();
		$correo->setDestinatario( $dest);
		$correo->setAsunto("Recordatorio de pago");
		$correo->setParametros( $parametros );
		$correo->setMensaje(   "usuario/recordatorio_email" );
		$correo->enviar();
		/********* */
	}




 
	 


	public function informes( $tipo, $CLIENTE){

	 
		try{
			$params=  $this->request->getRawInput();
			$Mes= $params['month']; 
			$Anio=  $params['year'];

		//	var_dump(  $params);
			$Cliente= $CLIENTE;   
		$pagos =	(new Pago_model())->where("cliente",  $Cliente)
			->select(" pagos.regnro, pagos.comprobante, pagos.fecha, pagos.created_at, planes.descr as plan")
			->where("month(pagos.fecha)", $Mes)
			->where("year(pagos.fecha)", $Anio)
			->join(
				"planes",
				"planes.regnro=pagos.plan"
			);
			//parametros
		$lista=	$pagos->get()->getResult(); 

		
		if($tipo== "PDF") return  $this->pdf( $lista, $Cliente);
		if($tipo == "JSON") return $this->response->setJSON(   $lista ); 
		}catch( Exception $e)
		{return $this->response->setJSON(  [] ); }
}






public function pdf( $lista, $CLIENTE){ 
		 
		 
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
	<th style="text-align:right;">PLAN</th>
	<th style="text-align:right;">FECHA PAGO</th>
	<th style="text-align:right;">REGISTRADO EL</th>
	</tr>
	</thead>
	<tbody>
	EOF;

	 
	foreach( $lista as $row){
		$comprobante= $row->comprobante ;
		$plan=  $row->plan ;
		$fecha=  $row->fecha;
		$created_at= $row->created_at;

		 
		$html.="<tr> <td style=\"text-align:center;\">$comprobante</td> <td style=\"text-align:right;\" >$plan</td> <td style=\"text-align:right;\">$fecha</td><td style=\"text-align:right;\">$created_at</td> </tr>";
	}
	 
	$html.="</tbody> </table> ";
	/********* */

		$pdf = new PDF(); 
		$Cliente=   $CLIENTE;
		$RUCCLIENTE= (new Usuario_model())->where("regnro", $Cliente)->first();
		$tituloDocumento= "RUC:". $RUCCLIENTE->ruc."-".$RUCCLIENTE->dv." (Detalle de pagos)";
 
		$TITULO_DOCUMENTO=  "RUC:". $RUCCLIENTE->ruc."-".$RUCCLIENTE->dv." (Pagos)";
		$pdf->prepararPdf("$tituloDocumento.pdf",  $TITULO_DOCUMENTO , ""); 
		$pdf->generarHtml( $html);
		return $pdf->generar();
}




}
