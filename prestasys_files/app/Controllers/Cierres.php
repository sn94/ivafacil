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
	 





	
	public function totales( $cod_cliente = NULL){
		$CODCLIENTE=  is_null($cod_cliente) ?  $this->getClienteId()  :   $cod_cliente;
		$cf=  (new Compra())->total_( $CODCLIENTE );
		$df= (new Venta())->total_(   $CODCLIENTE);
		$reten= (new Retencion())->total_( $CODCLIENTE );
	 
		 //A favor del contribuyente
		$s_contri=  intval(  $cf->iva1) +  intval($cf->iva2) + intval(  $reten->importe );
		 //A favor de hacienda
		 $s_fisco=  intval(  $df->iva1) +  intval($df->iva2 );
		 //Saldo
		 $saldo_a= $this->__saldo_anterior();
		 $saldo=  $s_contri -  $s_fisco;
		 $response=  \Config\Services::response();
		
		 return  $response->setJSON(  
			[
				 
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
			]
		  );
	}


//Saldo del mes anterior (periodo anterior)
	private function __saldo_anterior()
	{
		$this->API_MODE = $this->isAPI();
		$CODCLIENTE =  $this->API_MODE ?  $this->getClienteId() :    session("id");
		$ANTERIOR_S = (new Estado_mes_model())
		->where("codcliente", $CODCLIENTE)
		->where("anio", date("Y"))
		->where("mes", date("m") - 1 )
		->first();
		//Aun no hay cierres
		if (is_null($ANTERIOR_S)) {

			$estadoanio= (new Estado_anio_model())->where("codcliente", $CODCLIENTE)
			->where("anio", date("Y"))->first();

			return  $estadoanio->saldo_inicial;
			//$SALDO_INI = (new Usuario_model())->find($CODCLIENTE);
			//$inicial =  $SALDO_INI->saldo_IVA;//El saldo para comenzar el anio
			//return   $inicial;
		} else
		return   (intval($ANTERIOR_S->saldo) + intval( $ANTERIOR_S->saldo_inicial));
	}



	public function saldo_anterior( ){
		$sa=  $this->__saldo_anterior();
		return $this->response->setJSON(['data' => $sa,  'code' => "200"]);
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
				$parametros['saldo_anterior']= $this->__saldo_anterior();
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



	public function view_cierre_mes(){

		$this->crear_ejercicio();
		
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
		if( ( is_null($estado_meses)  || 
		 $estado_anio->estado=="P" ) &&  
		   intval($estado_anio->saldo_inicial)   == 0  )
		return view("movimientos/solici_saldo_ini");
		else
		return view("movimientos/cierre");
	}

	public function v_cierre_mes(){
		return view("movimientos/cierre");
	}


	
	public function  cierre_mes()
	{

		$this->API_MODE = $this->isAPI();

		$CODCLIENTE =  $this->API_MODE ?  $this->getClienteId() :    session("id");


		//no cerrar si no se esta al dia con el pago
		if (!  ((new Usuario())->servicio_habilitado($CODCLIENTE))  )
			return $this->response->setJSON(['msj' => "Operación no disponible. Revise su estado de pago",  'code' => "500"]);
		//no cerrar un mes dos veces
		if( $this->esta_cerrado() )
		{
			$nom_mes= Utilidades::monthDescr( date("m")  );
			return $this->response->setJSON(['msj' => "No permitido. El Mes de $nom_mes ya está cerrado.",  'code' => "500"]);
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
			$saldo_ante= $this->__saldo_anterior();
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
//sino se proporciona mes y anio, se considera que es el mes y anio actual, pero se verifica tambien si existe un registro
//reciente sobre el cierre de mes
	public function info_mes_cierre(  $cod_cliente,   $Month= NULL,  $Year= NULL){

		
		$MES=  is_null(  $Month)  ?  date("m")  :  $Month;
		$ANIO=  is_null(  $Year)  ?  date("Y")  :  $Year;

		$Cliente= (new Usuario_model())->find(  $cod_cliente );

		$UltimoCierre= (new Estado_mes_model())->where("codcliente",$cod_cliente)->
		where("estado","C")->orderBy("created_at", "DESC")->first();

		$MES= is_null( $UltimoCierre) ? ( $MES   )  :   $UltimoCierre->mes;
		$ANIO= is_null( $UltimoCierre) ? ( $ANIO   )  :   $UltimoCierre->anio;

		$Condicion= "WHERE YEAR(fecha)=$ANIO and MONTH(fecha)=$MES";
		$db = \Config\Database::connect();
		$query_Str= "select 'IVA COMPRA' as Descripcion, factura as comprobante, fecha, importe1 as IVA_10, importe2 as IVA_5, importe3 as EXENTA, total from compras 
		 $Condicion
		union 
		
		select  'IVA VENTA' as Descripcion, factura as  comprobante, fecha,importe1 as IVA_10, importe2 as IVA_5, importe3 as EXENTA,  total from ventas 
		  $Condicion 
		union
		
		select   'RETENCION' as Descripcion, retencion as 'comprobante', fecha, '0' as IVA_10, '0' as IVA_5, '0' as EXENTA, importe AS total  from  retencion 
		 $Condicion";
		
		$query = $db->query(  $query_Str);
		$results = $query->getResultArray();

		/**
		 * Totales 
		 */
		$t_IVA10=0; $t_IVA5= 0; $t_EXE= 0; $_t_TOT= 0;

		foreach ($results as $row) {
			$t_IVA10 += intval( $row['IVA_10']);
			$t_IVA5 += intval( $row['IVA_5']);
			$t_EXE += intval( $row['EXENTA']);
			$_t_TOT += intval( $row['total']);
		}
		$Totales= [ 'Descripcion'=>'TOTALES', 'comprobante'=>'',  'fecha'=>'', 'IVA_10'=>  $t_IVA10,  'IVA_5'=> $t_IVA5,  'EXENTA'=> $t_EXE,   'total'=> $_t_TOT ];
		array_push(   $results,  $Totales);
		$response= \Config\Services::response();

		$TITULO= "Cierre ".(Utilidades::monthDescr($MES)."/".$ANIO)." RUC: ". $Cliente->ruc."-".$Cliente->dv;
		$RESPUESTA=  ['data'=>$results, 'title' => $TITULO ];

		(new Estado_mes_model())->where("regnro", $UltimoCierre->regnro )->set("estado", "R")->update();
		 return $response->setJSON(  $RESPUESTA );
	 
	}


	/*

	*RESUMEN DE ANIO
	*/



	public function view_cierre_anio(){
		return view("movimientos/resumen_anio");
	}




	private function __saldo_anterior_anio(){
		$this->API_MODE = $this->isAPI();

		$CODCLIENTE =  $this->API_MODE ?  $this->getClienteId() :    session("id");

		 
		$ANTERIOR_S=(new Estado_mes_model())->where("codcliente", $CODCLIENTE)
		->where("anio",  date("Y")-1)
		->orderBy("created_at", "DESC")->first();
		//Aun no hay cierres
		if( is_null(  $ANTERIOR_S) ){
			$SALDO_INI= (new Usuario_model())->find( $CODCLIENTE);
			$inicial=  $SALDO_INI->saldo_IVA;
			return   $inicial;
		}else 
		return   $ANTERIOR_S->saldo;
	}
	

	public function saldo_anterior_anio( ){

		$sa=  $this->__saldo_anterior_anio();
		return $this->response->setJSON(['data' => $sa,  'code' => "200"]);
	}

	public function __totales_anio(){
		$cf=  (new Compra())->total_anio( true );
		$df= (new Venta())->total_anio(   true);
		$reten= (new Retencion())->total_anio( true );
	 
		 //A favor del contribuyente
		// $saldo_ante_anio= $this->__saldo_anterior_anio();// NO
		$s_contri=  intval(  $cf->iva1) +  intval($cf->iva2) + intval(  $reten->importe );
		 //A favor de hacienda
		 $s_fisco=  intval(  $df->iva1) +  intval($df->iva2 );
		 //Saldo
		 $saldo=  $s_contri -  $s_fisco;
		//saldo inicial
		$saldo_ini=   (new Estado_anio_model())->where("codcliente", $this->getClienteId())
		->where("anio", date("Y"))
		->first();
		
		//Anulados En venta
		$fv_anuladas= (new Venta())->anuladas( true );
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
				'saldo_inicial'=> $saldo_ini->saldo_inicial
			];
		  
	}

	public function totales_anio(){ 
		$response=  \Config\Services::response();
		 return  $response->setJSON(  
			$this->__totales_anio()
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







	public function  cierre_anio()
	{

		$this->API_MODE = $this->isAPI();

		$CODCLIENTE =  $this->API_MODE ?  $this->getClienteId() :    session("id");

		$this->crear_ejercicio();
		
		//no cerrar si no se esta al dia con el pago
		if (! ((new Usuario())->servicio_habilitado($CODCLIENTE) ) )
			return $this->response->setJSON(['msj' => "Operación no disponible. Revise su estado de pago",  'code' => "500"]);
		//no cerrar un mes dos veces
		$cerrado = (new Estado_anio_model())->where("codcliente", $CODCLIENTE)
		->where("anio", date("Y"))->first();
		 
		if ( $cerrado->estado != "P")  {
			$year = date("Y");
			return $this->response->setJSON(['msj' => "No permitido. El Año $year ya está cerrado.",  'code' => "500"]);
		}
			//numeros
			$totales_cierre= $this->__totales_anio();
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

	public function  esta_cerrado( $MES= NULL,  $ANIO= NULL ){

 
		$_mes= is_null(  $MES) ?  date("m") :  $MES;
		$_anio= is_null(  $ANIO) ?  date("Y") :  $ANIO;

		$cod_cliente= $this->getClienteId();
		$Anio= (new Estado_anio_model())->where("codcliente", $cod_cliente)->where("anio", $_anio)->first();
		$Mes= (new Estado_mes_model())->where("codcliente", $cod_cliente)
		->where("anio", $ANIO)->where("mes", $_mes)->first();

		if(  !is_null($Anio)  &&   $Anio->estado != "P")
		return true;
		else {
			if( is_null( $Mes))  return false;
			else return  $Mes->estado != "P" ;
		}
	}






	
}
