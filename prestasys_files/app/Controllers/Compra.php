<?php 
namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\pdf_gen\PDF;
use App\Models\Compras_model;
use App\Models\Monedas_model; 
use App\Models\Usuario_model;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Exception;


class Compra extends ResourceController {
 

	use 	ResponseTrait;

	protected $modelName = "App\Models\Compras_model";
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
 


 //Subinformes
 public function index(){

	$this->API_MODE=  $this->isAPI();
	$response= \Config\Services::response();

  

	$compras= (new Compras_model());

	$lista_co=[];

		if ($this->API_MODE) {
			$request = \Config\Services::request();
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




	if ($this->API_MODE) {
		$lista_co = $lista_co->get()->getResult();
		return $this->respond(array("data" => $lista_co, "code" => 200));
	} else {
		$lista_pagi = $lista_co->paginate(10);
		return view("movimientos/informes/grill_compras",  ['compras' =>  $lista_pagi, 'compras_pager'=> $lista_co->pager]);
	}
	 
}


	public function create(){
		
		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET")
		return view("movimientos/comprobantes/f_compra");
		//Manejo POST

		$this->API_MODE =  $this->isAPI();
		$usu = new Compras_model();

		$data = $this->request->getRawInput();

		if( $this->API_MODE)  $data['origen']= "A";
		
		if ($this->validate('compras')) { //Validacion OK

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
				$resu = $this->genericResponse( (new Compras_model())->find($id), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) 
				return $this->response->setJSON( ['data'=>"Guardado", "code"=>"200"]);
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
		return $this->response->setJSON( ['msj'=>$resultadoValidacion['msj'], "code"=>"500"]);
		// return view("movimientos/comprobantes/f_compra", array("error" => $resultadoValidacion['msj']));
	}





	//ruc=14455&dv=23&codcliente=9&fecha=2020-12-11&moneda=1&factura=0020037892222&total=140000000
	public function update( $cod_compra="" ){
		
		$request = \Config\Services::request();
		if ($request->getMethod(true) == "GET")
		return view("movimientos/comprobantes/f_compra");
		//Manejo POST

		$this->API_MODE =  $this->isAPI();
		$usu = new Compras_model();

		$data = $this->request->getRawInput();

		if( $this->API_MODE)  $data['origen']= "A";
		
		if ($this->validate('compras')) { //Validacion OK

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
				->update( $cod_compra);

				 
				$resu = $this->genericResponse( (new Compras_model())->find($cod_compra), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) return redirect()->to(base_url("movimiento/index"));
				else  return view("movimientos/comprobantes/f_compra", array("error" => $resu['msj']));
			}
		}

		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion =  $this->genericResponse(null, $validation->getErrors(), 500);
		if ($this->API_MODE)
		return $resultadoValidacion;
		else  return view("movimientos/comprobantes/f_compra", array("error" => $resultadoValidacion['msj']));
	}




	public function show($id = null)
	{
		$re = (new Compras_model())->find($id);
		if (is_null($re))
		return $this->genericResponse(null, "Este registro de Compra no existe", 404);
		else
		return $this->genericResponse($re, null, 200);
	}




	
	public function delete( $id = null)
	{
		 

		$this->API_MODE= $this->isAPI();
	 
		$us= (new Compras_model())->find(  $id);
 
		if (is_null( $us))
		return $this->genericResponse(null, "Compra  no existe",  404);
		else { 
			(new Compras_model())->where("regnro", $id)->delete( $id );
			return $this->genericResponse("Compra eliminada", null,  200);
		}
	}


	 

 
	
 
	 



	public function informes( $tipo){
			try{
				//parametros
			$params=  $this->request->getRawInput();
			$Mes= $params['mes']; 
			$Anio=  $params['anio'];
			$Cliente= session("id");
	
			$lista=	(new Compras_model())
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
			color: #404040;
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
		<th style="text-align:right;">EXENTA</th>
		<th style="text-align:right;">5%</th>
		<th style="text-align:right;">10%</th>
		</tr>
		</thead>
		<tbody>
		EOF;

		$t_exenta=0; $t_iva5= 0;  $t_iva10= 0;

		foreach( $lista as $row){
			$comprobante= Utilidades::formato_factura( $row->factura );
			$exenta= Utilidades::number_f( $row->importe3 );
			$iva5= Utilidades::number_f( $row->importe2 );
			$iva10= Utilidades::number_f( $row->importe1 );

			$t_exenta+= intval(  $row->importe3);
			$t_iva5+= intval(  $row->importe2);
			$t_iva10+= intval(  $row->importe1);

			$html.="<tr> <td style=\"text-align:center;\">$comprobante</td> <td style=\"text-align:right;\" >$exenta</td> <td style=\"text-align:right;\">$iva5</td><td style=\"text-align:right;\">$iva10</td> </tr>";
		}
		$t_exenta= Utilidades::number_f( $t_exenta);
		$t_iva5= Utilidades::number_f( $t_iva5);
		$t_iva10= Utilidades::number_f( $t_iva10);

		//totales
		$html.="<tr class=\"footer\"> <td style=\"text-align:center;\">Totales</td> <td style=\"text-align:right;\" >$t_exenta</td> <td style=\"text-align:right;\">$t_iva5</td><td style=\"text-align:right;\">$t_iva10</td> </tr>";
	
		$html.="</tbody> </table> ";
		/********* */

		$tituloDocumento= "IVA_Compra-".date("d")."-".date("m")."-".date("yy");
 	
			$pdf = new PDF(); 
			$Cliente= session("id");
			$RUCCLIENTE= (new Usuario_model())->where("regnro", $Cliente)->first();
			$TITULO_DOCUMENTO=  "RUC:". $RUCCLIENTE->ruc."-".$RUCCLIENTE->dv." (COMPRAS)";
			$pdf->prepararPdf("$tituloDocumento.pdf",  $TITULO_DOCUMENTO , ""); 
			$pdf->generarHtml( $html);
			return $pdf->generar();
	}



	 
	  
}
