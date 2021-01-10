<?php
 namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\Correo;
use App\Models\Compras_model;
use App\Models\Estado_anio_model;
use App\Models\Estado_mes_model; 
use App\Models\Parametros_model; 
use App\Models\Usuario_model;
use App\Models\Ventas_model;
use CodeIgniter\Controller; 
use Exception;

 

class Cierres extends Controller {
 
 

	private $API_MODE= true;



	public function __construct()
	{ 
		date_default_timezone_set("America/Asuncion");
		helper("form");
	}

	 

	private function isAdminView(){
		$request = \Config\Services::request();
		$uri = $request->uri; 
		return (   sizeof($uri->getSegments()) > 0  && $uri->getSegment(1) == "admin"  );
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
	 



public function totales_mes_session(  $MES= NULL, $ANIO= NULL){
	$Cliente= $this->getClienteId();
	return $this->totales(  $Cliente,  $MES, $ANIO );
}

	
	public function totales( $CODCLIENTE = NULL, $MES= NULL, $ANIO= NULL,  $RETORNO=  "JSON"){


		//$CODCLIENTE=  is_null($cod_cliente) ?  $this->getClienteId()  :   $cod_cliente;
		$cf=  (new Compra())->total_( $CODCLIENTE, $MES, $ANIO );
		$df= (new Venta())->total_(   $CODCLIENTE, $MES, $ANIO);
		$reten= (new Retencion())->total_( $CODCLIENTE, $MES, $ANIO );
	 
		 //A favor del contribuyente
		$s_contri=  intval(  $cf->iva1) +  intval($cf->iva2) + intval(  $reten->importe );
		 //A favor de hacienda
		 $s_fisco=  intval(  $df->iva1) +  intval($df->iva2 );
		 //Saldo
		 $saldo_a= $this->__saldo_anterior(   $CODCLIENTE, $MES, $ANIO); 
		 $saldo=  $s_contri -  $s_fisco;
		 $response=  \Config\Services::response();
		
		 $Montos= [
				 
			'compras_total_10'=> intval($cf->total10) ,
			'compras_total_5'=> intval($cf->total5) ,
			'compras_total_exe'=> intval($cf->totalexe) ,
			'compras_iva10'=> intval($cf->iva1) ,
			'compras_iva5'=> intval($cf->iva2) ,

			'ventas_total_10'=> intval($df->total10) ,
			'ventas_total_5'=> intval($df->total5) ,
			'ventas_total_exe'=> intval($df->totalexe) ,
			'ventas_iva10'=> intval($df->iva1) ,
			'ventas_iva5'=> intval($df->iva2) ,

			'retencion' => (intval($reten->importe)),
			'saldo' => $saldo,
			'saldo_anterior'=>  $saldo_a
		 ];
		  
		 if(  $RETORNO !=  "JSON")   return  $Montos;
		 else
		 return  $response->setJSON(  $Montos);
	}


	//Saldo del mes anterior (periodo anterior)

	private function saldo_mes_anterior($CODCLIENTE, $MES, $ANIO)
	{
		$cliente= (new Usuario_model())->find(  $CODCLIENTE );
		$meses_cerrados = (new Estado_mes_model())->where("codcliente",  $CODCLIENTE)
		->where("anio",  $ANIO)
		->where("mes", "<", $MES)
		->orderBy("mes", "DESC")
		->first();
		if (is_null($meses_cerrados)) {
			$saldo_del_anio = (new Estado_anio_model())->where("codcliente",  $CODCLIENTE)
			->where("anio",  $ANIO)->first();
			if( is_null( $saldo_del_anio))
			return   $cliente->saldo_IVA;
			else
			return $saldo_del_anio->saldo_inicial;
		} else {
			$saldo_liq = $meses_cerrados->saldo;
			$saldo_ant = $meses_cerrados->saldo_inicial;
			$saldo = intval($saldo_liq) + intval($saldo_ant);
			return $saldo;
		}
	}

	private function __saldo_anterior(  $CODCLIENTE, $MES,  $ANIO)
	{
		//$this->API_MODE = $this->isAPI();
	//	$CODCLIENTE =  $this->API_MODE ?  $this->getClienteId() :    session("id");


		$ANTERIOR_S = (new Estado_mes_model())
		->where("codcliente", $CODCLIENTE)
		->where("anio", $ANIO)
		->where("mes",  intval($MES) - 1 )
		->first();
		//Aun no hay cierres
		if (is_null($ANTERIOR_S)) {

		 
			$estadoanio= (new Estado_anio_model())->where("codcliente", $CODCLIENTE)
			->where("anio",  $ANIO)->first();

			return  $estadoanio->saldo_inicial;
			//$SALDO_INI = (new Usuario_model())->find($CODCLIENTE);
			//$inicial =  $SALDO_INI->saldo_IVA;//El saldo para comenzar el anio
			//return   $inicial;
		} else
		return   (intval($ANTERIOR_S->saldo) + intval( $ANTERIOR_S->saldo_inicial));
	}







	public function email_cierre_mes(  $idcierre){
		/*******Envio de correo */
		$PARAMs= (new Parametros_model())->first();

		$dest=  is_null( $PARAMs) ?  ""  :  $PARAMs->EMAIL;
		if(  $dest != ""){
			//Parametros
			$cierre_mes= (new Estado_mes_model())->find(  $idcierre);
			$CLIENTE= (new Usuario_model())->find( $cierre_mes->codcliente  );
		 
			//obtener registro del cierre de mes recientemente realizado
		 
			$parametros= [];
			if( ! is_null(  $cierre_mes) ){
				$parametros['cliente']=  ($CLIENTE->ruc)."-".($CLIENTE->dv);
				$parametros['compras']=  $cierre_mes->t_i_compras;
				$parametros['ventas']=  $cierre_mes->t_i_ventas;
				$parametros['retencion']=  $cierre_mes->t_retencion;
				$parametros['s_contri']=  (intval($cierre_mes->t_i_compras) + intval($cierre_mes->t_retencion)  );
				$parametros['s_fisco']=  intval( $cierre_mes->t_i_ventas);
				$parametros['saldo']=  $parametros['s_contri'] -  $parametros['s_fisco'];
				$parametros['saldo_anterior']= $this->__saldo_anterior( $CLIENTE->regnro,  $cierre_mes->mes,  $cierre_mes->anio);
			}
			//******** */
			$correo= new Correo();
			$correo->setDestinatario( $dest);
			$correo->setAsunto("Cierre de Mes");
			$correo->setParametros(  $parametros);
			$correo->setMensaje(   "movimientos/cierre_mes_email" );
			$correo->enviar();
		}
	
		/********* */
	}



	

	public function view_cierre_mes(  $CLIENTE=   NULL, $MES= NULL,  $ANIO=  NULL  ){
		if( ! $this->isAdminView())
		$this->crear_ejercicio();
		elseif(    !is_null($CLIENTE)      )
		{
			 
			$ultimo_cierre=(new Estado_mes_model())->where("codcliente", $CLIENTE)->where("estado", "C")->orderBy("mes", "DESC")->first();
			$MES_=   $ultimo_cierre->mes; 
			$ANIO_=   $ultimo_cierre->anio;
			$TOTALS= $this->totales( $CLIENTE, $MES_, $ANIO_, "ARRAY" );
			return view("movimientos/cierre_ajax", [ "mes"=> $MES_,  'anio'=>  $ANIO_,  'totales'=> $TOTALS ]);
		}
		
		$estado_anio= (new Estado_anio_model())
		->where("ruc", session("ruc"))
		->where("dv", session("dv"))
		->where("anio",  date("Y"))
		->first();
 
		$estado_meses= (new Estado_mes_model())
		->where("ruc", session("ruc"))
		->where("dv", session("dv"))
		->where("mes",  date("m"))
		->first(); 
	
		$habilitar_edicion=  	!$this->esta_cerrado();
	
		$codcliente=  $this->getClienteId();
		$anios=  (new Estado_anio_model())->select("anio")->where("codcliente",   $codcliente)->get()->getResult();
		return view("movimientos/cierre", ['edicion_saldo_inicial'=>  $habilitar_edicion, "ANIOS"=> $anios ]);
	}



	
	//recibir mes y anio como parametros get
	public function  cierre_mes(  $MES ,  $ANIO)
	{

		$this->API_MODE = $this->isAPI();
		$CODCLIENTE =  $this->API_MODE ?  $this->getClienteId() :    session("id");
		//no cerrar si no se esta al dia con el pago
		if (!  ((new Usuario())->servicio_habilitado($CODCLIENTE))  )
			return $this->response->setJSON(['msj' => "Operación no disponible. Revise su estado de pago",  'code' => "500"]);
		
		
		if( $this->mes_anio_fuera_de_tiempo( $MES, $ANIO) )
		return $this->response->setJSON(['msj' => "No permitido. Mes y año no válidos",  'code' => "500"]);

			//no cerrar un mes dos veces
		if( $this->esta_cerrado(  $MES,  $ANIO) )
		{
			if( $this->esta_cerrado_anio( $ANIO ))
			return $this->response->setJSON(['msj' => "No permitido. El Ejercicio ya cerró.",  'code' => "500"]);
			else {
				$nom_mes= Utilidades::monthDescr( date("m")  );
				return $this->response->setJSON(['msj' => "No permitido. El Mes de $nom_mes ya está cerrado.",  'code' => "500"]);
			}
		
		}
		

			$cierre =  new Estado_mes_model();
			//numeros
			$cf =  (new Compra())->total_( $CODCLIENTE);
			$df = (new Venta())->total_( $CODCLIENTE);
			$reten = (new Retencion())->total_( $CODCLIENTE);

			//A favor del contribuyente
			$s_contri =  intval($cf->iva1) +  intval($cf->iva2) + intval($reten->importe);
			//A favor de hacienda
			$s_fisco =  intval($df->iva1) +  intval($df->iva2);
			//Saldo
			$saldo_ante= $this->__saldo_anterior( $CODCLIENTE, $MES, $ANIO);
			$saldo = ( $s_contri    ) -  $s_fisco;
			$mes = date("m");
			$anio = date("Y");
			$data_cliente= (new Usuario_model())->find(  $CODCLIENTE );
			$DATOS = [
				'codcliente' => $CODCLIENTE,
				'ruc'=>  $data_cliente->ruc,
				'dv'=>   $data_cliente->dv,
				'mes' => $mes,
				'anio' => $anio,
				't_i_compras' => (intval($cf->iva1) +  intval($cf->iva2)),
				't_i_ventas' => $s_fisco,
				't_retencion' => (intval($reten->importe)),
				'saldo' => $saldo,
				'saldo_inicial'=>  $saldo_ante,
				'estado'=> 'C'
			];

			try {
				//var_dump(  $DATOS);
				$id_cierre= $cierre->insert($DATOS);
				//Notificar
				// ....
				$this->email_cierre_mes(  $id_cierre);
				return $this->response->setJSON(['data' =>  $id_cierre,  'code' => "200"]);
			} catch (Exception $ex) {
				return $this->response->setJSON(['msj' => "Comunique este error a su proveedor de servicios: $ex",  'code' => "500"]);
			}
		
	}




	//Lista detalles de los movimientos en el mes, hace inferencia sobre los valores de los meses y el anio,

	public function resumen_mes_session($Month = NULL,  $Year = NULL)
	{
		$cod_cliente = $this->getClienteId();
		return $this->resumen_mes($cod_cliente, $Month,  $Year);
	}
	public  function resumen_mes($cod_cliente,   $Month = NULL,  $Year = NULL)
	{

		//El saldo anterior
		$saldo_anterior=  $this->saldo_mes_anterior($cod_cliente, $Month, $Year);
		$MES =  is_null($Month)  ?  date("m")  :  $Month;
		$ANIO =  is_null($Year)  ?  date("Y")  :  $Year;
		$Cliente = (new Usuario_model())->find($cod_cliente);
		$Condicion = "WHERE codcliente=$cod_cliente AND YEAR(fecha)=$ANIO and MONTH(fecha)=$MES";
		$db = \Config\Database::connect();
		$query_Str = "select 'IVA COMPRA' as Descripcion, factura as comprobante, fecha, importe1 as IVA_10, importe2 as IVA_5, importe3 as EXENTA, total from compras 
		 $Condicion
		union 
		
		select  'IVA VENTA' as Descripcion, factura as  comprobante, fecha,importe1 as IVA_10, importe2 as IVA_5, importe3 as EXENTA,  total from ventas 
		  $Condicion 
		union
		
		select   'RETENCION' as Descripcion, retencion as 'comprobante', fecha, '0' as IVA_10, '0' as IVA_5, '0' as EXENTA, importe AS total  from  retencion 
		 $Condicion";

		$query = $db->query($query_Str);
		$results = $query->getResultArray();

		/**
		 * Totales 
		 */
		$TOTAL_COMPRA= (new Compra())->total_mes( $cod_cliente, $MES, $ANIO);
		$TOTAL_VENTA= (new Venta())->total_mes( $cod_cliente, $MES, $ANIO);
		$TOTAL_RETENCION= (new Retencion())->total_mes( $cod_cliente, $MES, $ANIO);
		$SALDO_ACTUAL= intval($TOTAL_COMPRA->iva1)+intval($TOTAL_COMPRA->iva2)+intval(  $TOTAL_RETENCION->importe) - (intval($TOTAL_VENTA->iva1) + intval($TOTAL_VENTA->iva2));
		$LosTotales= [
			'IVA_CF_10'=>  $TOTAL_COMPRA->iva1,
			'IVA_CF_5'=>  $TOTAL_COMPRA->iva2,
			'COMPRA_EXENTA'=>  $TOTAL_COMPRA->iva3,
			'IVA_DF_10'=>  $TOTAL_VENTA->iva1,
			'IVA_DF_5'=>  $TOTAL_VENTA->iva2,
			'VENTA_EXENTA'=>  $TOTAL_VENTA->iva3,
			'RETENCION' =>   $TOTAL_RETENCION->importe,
			'SALDO_ANTE'=> $saldo_anterior,
			'SALDO'=>  $SALDO_ACTUAL
		];
	 
		$response = \Config\Services::response();

		$TITULO = "Cierre " . (Utilidades::monthDescr($MES) . "/" . $ANIO) . " RUC: " . $Cliente->ruc . "-" . $Cliente->dv;
		$RESPUESTA =  ['data' => $results, 'totales'=> $LosTotales, 'title' => $TITULO];
		return $response->setJSON($RESPUESTA);
	}


	 
	



//Resumen del mes en base a estados de sesion actual
	public function info_mes_cierre(  $cod_cliente,   $Month= NULL,  $Year= NULL){

		$MES =  is_null($Month)  ?  date("m")  :  $Month;
		$ANIO =  is_null($Year)  ?  date("Y")  :  $Year; 
		$UltimoCierre= (new Estado_mes_model())->where("codcliente",$cod_cliente)->
		where("estado","C")->orderBy("created_at", "DESC")->first();
		$MES= is_null( $UltimoCierre) ? ( $MES   )  :   $UltimoCierre->mes;
		$ANIO= is_null( $UltimoCierre) ? ( $ANIO   )  :   $UltimoCierre->anio;
		$RESPUESTA=  $this->resumen_mes( $cod_cliente, $Month, $Year);
	//	(new Estado_mes_model())->where("regnro", $UltimoCierre->regnro )->set("estado", "R")->update();
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



	public function view_cierre_anio(){

		//listar ejercicios cerrados del cliente
		$codcliente=  $this->getClienteId();
		$anios=  (new Estado_anio_model())->select("anio")->where("codcliente",   $codcliente)->get()->getResult();
		 
		return view("movimientos/resumen_anio",   ['ANIOS'=>   $anios]);
	}




	private function __saldo_anterior_anio(  $CODCLIENTE= NULL,  $ANIO = NULL  ){
	//	$this->API_MODE = $this->isAPI();

	//	$CODCLIENTE =  $this->API_MODE ?  $this->getClienteId() :    session("id");

		$anio=  is_null(  $ANIO)  ?  date("Y")  :  $ANIO;

		$ANTERIOR_S=(new Estado_anio_model())->where("codcliente", $CODCLIENTE)
		->where("anio",  intval($anio)-1)
		->orderBy("created_at", "DESC")->first();
		//Aun no hay cierres de anio
		if( is_null(  $ANTERIOR_S) ){

			$PRESENTE_ANIO = (new Estado_anio_model())->where("codcliente", $CODCLIENTE)
				->where("anio",  $anio)
				->orderBy("created_at", "DESC")->first();
			if(  is_null(  $PRESENTE_ANIO) ){
				$SALDO_INI= (new Usuario_model())->find( $CODCLIENTE);
				$inicial=  $SALDO_INI->saldo_IVA;
				return   $inicial;
			}else 
			return  $PRESENTE_ANIO->saldo_inicial;
		
			
		}else 
		return   $ANTERIOR_S->saldo;
	}
	

	public function saldo_anterior_anio( $cliente, $anio ){

		$sa=  $this->__saldo_anterior_anio( $cliente, $anio);
		return $this->response->setJSON(['data' => $sa,  'code' => "200"]);
	}

	public function __totales_anio( $CLIENTE,  $ANIO=NULL){
	 
		$cf=  (new Compra())->total_anio( $CLIENTE , $ANIO);
		$df= (new Venta())->total_anio(   $CLIENTE, $ANIO);
		$reten= (new Retencion())->total_anio( $CLIENTE , $ANIO);
	 
		$YEAR= is_null(  $ANIO)  ? date("Y") :   $ANIO ;
		 //A favor del contribuyente
		// $saldo_ante_anio= $this->__saldo_anterior_anio();// NO
		$s_contri=  intval(  $cf->iva1) +  intval($cf->iva2) + intval(  $reten->importe );
		 //A favor de hacienda
		 $s_fisco=  intval(  $df->iva1) +  intval($df->iva2 );
		 //Saldo
		 $saldo=  $s_contri -  $s_fisco;
		//saldo inicial
		$saldo_ini=   $this->__saldo_anterior_anio(  $CLIENTE, $YEAR)   ;
		
		//Anulados En venta
		$fv_anuladas= (new Venta())->anuladas_( $CLIENTE, NULL,  $YEAR);
		$fv_cant=  $fv_anuladas->cantidad;
		$fv_tot=  $fv_anuladas->total ;
		$fv_iva=  $fv_anuladas->total_iva ;

		 return   
			[
				'compras' => (intval($cf->iva1) +  intval($cf->iva2)),
				'ventas' => $s_fisco,
				'ventas_anuladas_cant'=> $fv_cant,
				'ventas_anuladas_tot'=> $fv_tot,
				'ventas_anuladas_iva'=> $fv_iva,
				'retencion' => (intval($reten->importe)),
				'saldo' => $saldo,
				'saldo_inicial'=> $saldo_ini
			];
		  
	}

	public function totales_anio_session( $YEAR= NULL){ 
		$response=  \Config\Services::response();
		$CLIENTE=  $this->getClienteId();
		 return  $response->setJSON(  
			$this->__totales_anio(  $CLIENTE,  $YEAR)
		  );
	}






	
	public function email_cierre_anio(  $idcierre){
		/*******Envio de correo */
		$PARAMs= (new Parametros_model())->first();

		$dest=  is_null( $PARAMs) ?  ""  :  $PARAMs->EMAIL;
		if(  $dest != ""){
			//Parametros
			$cierre_mes= (new Estado_anio_model())->find(  $idcierre);
			$CLIENTE= (new Usuario_model())->find( $cierre_mes->codcliente  );
		 
			//obtener registro del cierre de mes recientemente realizado
		 
			$parametros= [];
			if( ! is_null(  $cierre_mes) ){
				$parametros['cliente']=  ($CLIENTE->ruc)."-".($CLIENTE->dv);
				$parametros['compras']=  $cierre_mes->t_i_compras;
				$parametros['ventas']=  $cierre_mes->t_i_ventas;
				$parametros['retencion']=  $cierre_mes->t_retencion;
				$parametros['s_contri']=  (intval($cierre_mes->t_i_compras) + intval($cierre_mes->t_retencion)  );
				$parametros['s_fisco']=  intval( $cierre_mes->t_i_ventas);
				$parametros['saldo']=  $parametros['s_contri'] -  $parametros['s_fisco'];
				$parametros['saldo_anterior']=  $cierre_mes->saldo_inicial;
			}
			//******** */
			$correo= new Correo();
			$correo->setDestinatario( $dest);
			$correo->setAsunto("Cierre de Año");
			$correo->setParametros(  $parametros);
			$correo->setMensaje(   "movimientos/cierre_anio_email" );
			$correo->enviar();
		}
	
		/********* */
	}







	public function  cierre_anio(  $ANIO )
	{

		$this->API_MODE = $this->isAPI();
		$CODCLIENTE =  $this->API_MODE ?  $this->getClienteId() :    session("id");
		$this->crear_ejercicio();
		//no cerrar si no se esta al dia con el pago
		if (! ((new Usuario())->servicio_habilitado($CODCLIENTE) ) )
			return $this->response->setJSON(['msj' => "Operación no disponible. Revise su estado de pago",  'code' => "500"]);
		//no cerrar un ejercicio dos veces
		if( $this->mes_anio_fuera_de_tiempo( NULL,  $ANIO) )
		return $this->response->setJSON(['msj' => "No permitido. Mes y año no válidos",  'code' => "500"]);
		if( $this->esta_cerrado_anio(  $ANIO))
		return $this->response->setJSON(['msj' => "No permitido. El Año $ANIO ya está cerrado.",  'code' => "500"]);

		 
		$cerrado = (new Estado_anio_model())->where("codcliente", $CODCLIENTE)->where("anio", $ANIO)->first();
	
			//numeros
			$totales_cierre= $this->__totales_anio( $CODCLIENTE, $ANIO );
			$cf =  $totales_cierre['compras'];
			$df = $totales_cierre['ventas'];
			$reten = $totales_cierre['retencion'];

			//A favor del contribuyente
			$s_contri =  intval($cf) + intval($reten);
			//A favor de hacienda
			$s_fisco =  intval($df) ;
			//Saldo
			$saldo_ante= $cerrado->saldo_inicial; // $this->__saldo_anterior();
			$saldo = ( $s_contri   ) -  $s_fisco; 
			$anio = date("Y");
			$data_cliente= (new Usuario_model())->find(  $CODCLIENTE );
			$DATOS = [
				'codcliente' => $CODCLIENTE, 
				'ruc'=>  $data_cliente->ruc,
				'dv'=>   $data_cliente->dv,
				'anio' => $anio,
				't_i_compras' => $cf,
				't_i_ventas' => $df,
				't_retencion' => $reten ,
				'saldo' => $saldo,
				'estado'=> "C"
			];
			$db= \Config\Database::connect();

			$db->transStart();
			$respuesta= [];
		try {
			(new Estado_anio_model())->where("regnro", $cerrado->regnro)->set($DATOS)->update();
			//actualizar en usuario mis datos
			$anio_a_Cerrar =  intval($saldo) + intval($saldo_ante);
			(new Usuario_model())->where("regnro", $this->getClienteId())->set(['saldo_IVA' =>  $anio_a_Cerrar])
				->update();
			$db->transCommit();
			//Notificar 
			$this->email_cierre_anio($cerrado->regnro);
			$respuesta = $this->response->setJSON(['data' =>  $cerrado->regnro,  'code' => "200"]);
		} catch (Exception $ex) {
			$db->transRollback();
			$respuesta = $this->response->setJSON(['msj' => "Comunique este error a su proveedor de servicios: $ex",  'code' => "500"]);
		}
		$db->transComplete();
		return $respuesta;
	}



	
	
public function resumen_anio_session(  $Year ){
	$cod_cliente= $this->getClienteId();
	return  $this->resumen_anio(  $cod_cliente,  $Year );
}


	public  function resumen_anio($cod_cliente,  $Year = NULL)
	{
 
		$ANIO =  is_null($Year)  ?  date("Y")  :  $Year;
		$Cliente = (new Usuario_model())->find($cod_cliente);
		
		//Obtener totales de cada mes
		$totales_meses= (new Estado_mes_model())
		->select("mes,anio, t_i_compras, t_i_ventas, t_retencion, saldo, saldo_inicial")
		->where("codcliente", $cod_cliente)->where("anio", $ANIO)->get()->getResultArray();

		//el mes actual ya se cerro? sino es asi, calcular su total, porque aun no se incluye en la tabla de estados_meses
		if(  ! $this->esta_cerrado( date("m"), $ANIO)){
			$TOTAL_COMPRA_m= (new Compra())->total_mes( $cod_cliente,  date("m"),  $ANIO);
			$TOTAL_VENTA_m= (new Venta())->total_anio( $cod_cliente,  date("m"), $ANIO);
			$TOTAL_RETENCION_m= (new Retencion())->total_anio( $cod_cliente,  date("m"), $ANIO);

			$total_ahora_compras= ( intval($TOTAL_COMPRA_m->iva1) +  intval($TOTAL_COMPRA_m->iva2) );
			$total_ahora_ventas=  ( intval($TOTAL_VENTA_m->iva1) +  intval($TOTAL_VENTA_m->iva2) );
			$total_retencion=  intval($TOTAL_RETENCION_m->importe );
			$saldo= ( $total_ahora_compras+$total_retencion)  -  $total_ahora_ventas;
			$saldo_anterior_a_este=  $this->saldo_mes_anterior( $cod_cliente,  date("m"),   $ANIO);
			$ESTADO_ESTE_MES= [
				'mes'=>  date("m"),
				'anio'=> $ANIO,
				't_i_compras' =>  $total_ahora_compras ,
				't_i_ventas' => $total_ahora_ventas  ,
				't_retencion' =>   $total_retencion,
				'saldo' =>  $saldo ,
				'saldo_inicial'=>  $saldo_anterior_a_este
			];
			array_push(  $totales_meses,   $ESTADO_ESTE_MES );
		}
		/**
		 * Totales 
		 */
		$TOTAL_COMPRA= (new Compra())->total_anio( $cod_cliente, $ANIO);
		$TOTAL_VENTA= (new Venta())->total_anio( $cod_cliente, $ANIO);
		$TOTAL_RETENCION= (new Retencion())->total_anio( $cod_cliente, $ANIO);

		$LosTotales= [
			'IVA_CF_10'=>  $TOTAL_COMPRA->iva1,
			'IVA_CF_5'=>  $TOTAL_COMPRA->iva2,
			'COMPRA_EXENTA'=>  $TOTAL_COMPRA->iva3,
			'IVA_DF_10'=>  $TOTAL_VENTA->iva1,
			'IVA_DF_5'=>  $TOTAL_VENTA->iva2,
			'VENTA_EXENTA'=>  $TOTAL_VENTA->iva3,
			'RETENCION' =>   $TOTAL_RETENCION->importe
		];
	 
		$response = \Config\Services::response();

		$TITULO = "Cierre " . ( $ANIO) . " RUC: " . $Cliente->ruc . "-" . $Cliente->dv;
		$RESPUESTA =  ['data' => $totales_meses, 'totales'=> $LosTotales, 'title' => $TITULO];
		return $response->setJSON($RESPUESTA);
	}





	public function info_anio_cierre(  $cod_cliente,    $Year= NULL){

		
	 
		$ANIO=  is_null(  $Year)  ?  date("Y")  :  $Year;

		$Cliente= (new Usuario_model())->find(  $cod_cliente );

		$UltimoCierre= (new Estado_anio_model())->where("codcliente",$cod_cliente)->
		where("estado", "C")->orderBy("created_at", "DESC")->first();
		 
		$ANIO= is_null( $UltimoCierre) ? ( $ANIO   )  :   $UltimoCierre->anio;

		$cierres_por_mes= (new Estado_mes_model())->where("anio", $ANIO)->where("codcliente", $cod_cliente)
		->select("  mes as Mes, t_i_compras as 'IVA Crédito fiscal' , t_i_ventas as 'IVA Débito fiscal',
		t_retencion as 'Retenciones', saldo as Saldo ")
		->orderBy("mes", "ASC")
		->get()->getResultArray();

		/**
		 * Totales 
		 */
		$t_compras=0; $t_ventas= 0; $t_retencion= 0; $_t_SALDO= 0;

		foreach ($cierres_por_mes as $row) {
			$t_compras+= intval( $row['IVA Crédito fiscal']);
			$t_ventas+= intval( $row['IVA Débito fiscal']);
			$t_retencion += intval( $row['Retenciones']);
			 
		}
		$Saldo__=  ($t_compras+$t_retencion) - $t_ventas;

		$Totales= [ 'Mes'=>'TOTALES', 'IVA Crédito fiscal' => $t_compras , 'IVA Débito fiscal' => $t_ventas,
		 'Retenciones'=> $t_retencion, 'Saldo:'=> $Saldo__  ];
		array_push(   $cierres_por_mes,  $Totales);
		$response= \Config\Services::response();

		$TITULO= "Cierre Año ".$ANIO." RUC: ". $Cliente->ruc."-".$Cliente->dv;
		$RESPUESTA=  ['data'=>$cierres_por_mes, 'title' => $TITULO ];
		(new Estado_anio_model())->where("regnro", $UltimoCierre->regnro )->set("estado", "R")->update();
		 return $response->setJSON(  $RESPUESTA );
	 
	}











	//Funciones de consistencia

	
	//Agrega un registro para nuevo ejercicio si aun no existe uno
	public function crear_ejercicio(){
		$cliente= $this->getClienteId();
		$cliente_obj= (new Usuario_model())->find(  $cliente );
		$ejercicio_listo= (new Estado_anio_model())->where( "codcliente", $cliente)->where("anio", date("Y"))->first();
		if( is_null( $ejercicio_listo )){
			$nuevo_ejercicio = [
				'codcliente' => $cliente, 
				'ruc'=> $cliente_obj->ruc,
				'dv'=> $cliente_obj->dv,
				'anio' => date("Y"),
				't_i_compras' => 0,
				't_i_ventas' => 0,
				't_retencion' => 0 ,
				'saldo'=> 0,
				'saldo_inicial' => 0
			];
			(new Estado_anio_model())->insert(   $nuevo_ejercicio); 
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
	public function fecha_operacion_invalida( $FECHA ){
		$response= \Config\Services::response();
		$fecha_comprobante =  explode("-",  $FECHA);
		$mes =  $fecha_comprobante[1];
		$anio =  $fecha_comprobante[0]; 
		$fuera_de_tiempo =  $this->mes_anio_fuera_de_tiempo($mes,  $anio);
		if (  !is_null($fuera_de_tiempo))  return $fuera_de_tiempo;

		$estaCerrado = $this->esta_cerrado($mes, $anio);
		if ($estaCerrado) return $response->setJSON(['msj' => "No puede registrar la transacción para un período ya cerrado", "code" => "500"]);
		else return  NULL;
	}


	public function  esta_cerrado_anio(   $ANIO= NULL ){
	 
		$_anio= is_null(  $ANIO) ?  date("Y") :  $ANIO;

		$cod_cliente= $this->getClienteId();
		$Anio= (new Estado_anio_model())->where("codcliente", $cod_cliente)->where("anio", $_anio)->first();
		if(  !is_null($Anio)  &&   $Anio->estado != "P")
		return true;
		else return false;
	}

	public function  esta_cerrado( $MES= NULL,  $ANIO= NULL ){
		$_mes= is_null(  $MES) ?  date("m") :  $MES;
		$_anio= is_null(  $ANIO) ?  date("Y") :  $ANIO;

		$cod_cliente= $this->getClienteId();
		$Anio= (new Estado_anio_model())->where("codcliente", $cod_cliente)->where("anio", $_anio)->first();
		$Mes= (new Estado_mes_model())->where("codcliente", $cod_cliente)
		->where("anio", $_anio)->where("mes", $_mes)->first();

	 
		if(  !is_null($Anio)  &&   $Anio->estado != "P")
		return true;
		//ejercicio abierto
		elseif(   !is_null($Anio)  &&   $Anio->estado == "P" ){
			if( is_null( $Mes))  return false;
			else return  $Mes->estado != "P" ;
		}
	}






	
}
