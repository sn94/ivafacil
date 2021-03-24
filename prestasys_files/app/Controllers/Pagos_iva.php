<?php
 namespace App\Controllers;
 
use App\Libraries\Correo;
use App\Libraries\pdf_gen\PDF;
use App\Models\Estado_mes_model;
use App\Models\Pago_model;
use App\Models\Pagos_iva_model;
use App\Models\Planes_model; 
use App\Models\Usuario_model;
use CodeIgniter\Controller; 
use Exception;

 
/**
 * 
 * 
 * Pagos del IVA por el proveedor de servicios
 */

class Pagos_iva extends Controller {
 
 

	private $API_MODE= true;



	public function __construct(  $api_mode= true)
	{

		$this->API_MODE=  $api_mode;
		date_default_timezone_set("America/Asuncion");
		helper("form");
	}

	 
	public function isAdminView()
	{
		$request = \Config\Services::request();
		$uri = $request->uri;
		return (sizeof($uri->getSegments()) > 0  && $uri->getSegment(1) == "admin");
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



	public function index_sess( $ESTADO,  $ANIO )
	{
		//pendientes de pago

		$Ejercicio= is_null(  $ANIO ) ? date("Y")  :  $ANIO ;
		$CLIENTE=   $this->getClienteId() ;
		$REGISTROS= [];
		if( $ESTADO  == "P"){//Pendientes de pagar , y que esten en condicion de pagar

			$REGISTROS = (new Estado_mes_model())->where("anio",  $Ejercicio)
			->where("estado", "C")
			->where("codcliente", $CLIENTE)->get()->getResult();
	
		}else{
			$REGISTROS = (new Pagos_iva_model())->where("year(fecha)",  $Ejercicio)
			->where("codcliente", $CLIENTE)->get()->getResult();
		}
	return $this->response->setJSON(  [ "data"=> $REGISTROS,  'code'=>'200'] );
	 
	}	



	public function index($id = NULL, $estado= "P", $ANIO= NULL )
	{
		//pendientes de pago

		$Ejercicio= is_null(  $ANIO ) ? date("Y")  :  $ANIO ;

		$CLIENTE= is_null( $id )?  $this->getClienteId() :   $id;
		 
		$pendientes = (new Estado_mes_model()); //->where("anio",  $Ejercicio) ;
		
		if( $estado == "P")
		$pendientes= $pendientes->where("estado", "C");//MES C-errado
		else 
		$pendientes= $pendientes->where("estado", "L");//MES L-iquidado

		$pendientes= $pendientes->where("codcliente", $CLIENTE)->get()->getResult();
 
		if ($this->request->isAJAX()) {
			if(  $this->isAdminView()) {
				if( $estado == "P")
				return view("admin/clientes/pago_iva/grill_pagos_pendientes",  ['pagos_pendientes' =>  $pendientes]);
				else
				return view("admin/clientes/pago_iva/grill_pagos_hechos",  ['pagos_pendientes' =>  $pendientes]);
				

			} else {
				if(  $estado == "P")
				return view("pago_iva/grill_pagos_pendientes",  ['pagos_pendientes' =>  $pendientes]);
				else
				return view("pago_iva/grill_pagos_hechos",  ['pagos_pendientes' =>  $pendientes]);
			}
		} else {
			if(  $this->isAdminView()) {
			 
				return view("admin/clientes/pago_iva/pagos",  ['pagos_pendientes' =>  $pendientes, 'CLIENTE' => $CLIENTE]);
			} else {

				if( $this->isAPI())
				return $this->response->setJSON(  ['data'=>  $pendientes,  'code'=> '200'] );
				else
				return view("pago_iva/pagos",  ['pagos_pendientes' =>  $pendientes, 'CLIENTE' => $CLIENTE]);
			}
		}
	}	
	
	
	public function create(  $id= null){//ID ESTADO MES

		if( $this->request->getMethod( true) == "GET")
		{	
			
			$estado_mes=  ( new Estado_mes_model())->find(  $id );
			if( $this->isAdminView())
			return view( "admin/clientes/pago_iva/form", ['ESTADO_MES'=>  $estado_mes ]);
			else
			return view( "pago_iva/form", ['ESTADO_MES'=>  $estado_mes ]);
		}
		else 

		{
			$datos = $this->request->getRawInput();
			$datos['importe']=  abs(  $datos['importe'] );
			//Desde la api
			if( $this->isAPI())
			{
				$ApiClientId= $this->getClienteId();
				$ApiClientData= (new Usuario_model())->find(  $ApiClientId);
				$datos['ruc']=  $ApiClientData->ruc;
				$datos['dv']=  $ApiClientData->dv;
				$datos['codcliente']=  $ApiClientId;
			}

			$pago = new Pagos_iva_model();
			//transaccion
			$db = \Config\Database::connect();

			$db->transStart();
			try {
				$pago->insert($datos);
				//actualizar estado mes a Liquidado
				(new Estado_mes_model())->where("codcliente", $datos['codcliente'])
				->where("ruc", $datos['ruc'])
				->where("dv", $datos['dv'])
				->where("mes", $datos['mes'])
				->where("anio", $datos['anio'])
				->set( ["estado"=> "L",  'pago'=>  $datos['importe'] ])->update();

				 
				//Comunicar al cliente su cierre
				if( $this->isAdminView() )
				$this->email_iva_pagado(  $datos['codcliente'],  ['fecha_pago'=> $datos['fecha'] , 'mes'=>$datos['mes'],   'anio'=>$datos['anio']  ]  );
				
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
	public function email_iva_pagado( $CODCLIENTE= NULL, $params){
		/*******Envio de correo */
		$Cliente= (new Usuario_model())->find( $CODCLIENTE);
		$destinatario=  $Cliente->email; 
		//parametro 
		$parametros=   array_merge( $params   	,['cliente'=> $Cliente->cliente]  );
		$correo= new Correo();
		$correo->setDestinatario( $destinatario);
		$correo->setAsunto("Aviso de Liquidación de IVA");
		$correo->setParametros( $parametros );
		$correo->setMensaje(   "admin/clientes/pago_iva/email_iva_pagado" );
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
