<?php
 namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\Correo;
use App\Models\Calendario_model;
use App\Models\Cierre_anio_model;
use App\Models\Cierre_mes_model;
use App\Models\Ciudades_model;
use App\Models\Estado_anio_model;
use App\Models\Estado_mes_model;
use App\Models\Pago_model;
use App\Models\Parametros_model;
use App\Models\Planes_model;
use App\Models\Rubro_model;
use App\Models\Usuario_model;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Session\Session;
use Exception;

 

class Usuario extends ResourceController {
 

	protected $modelName = "App\Models\Usuario_model";
	protected $format = "json";

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


	private function isAdminView(){
		$request = \Config\Services::request();
		$uri = $request->uri; 
		return (   sizeof($uri->getSegments()) > 0  && $uri->getSegment(1) == "admin"  );
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




	
	public function index()
	{
		$this->API_MODE= $this->isAPI();
		if(  $this->API_MODE ){
			$sesion= $this->request->getHeader('Ivasession');
			if(  $sesion != "") {
				$usu_filter= (new Usuario_model())
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, telefono, celular, domicilio, ciudad, rubro")
				->get()
				->getResult();
				return $this->genericResponse($usu_filter, null, 200);
			}
			else 
			return $this->genericResponse( null, "No está autenticado", 500);
		}
	
	}


	
	//Busqueda por patrones
	//Nombre, RUC
	public function list(){

		/**Parametros POST */
		$argumento= "";
		if(  $this->request->getMethod( true )  ==  "POST"){
			$data=  $this->request->getRawInput();
			$argumento=  $data['argumento'];
		}
	
		/******* */
		$usu= (new Usuario_model())
		->select(
			"
			usuarios.*, 
			if(
			(select DATEDIFF( CURRENT_TIMESTAMP  , pagos.validez) from pagos where 
			 pagos.ruc = usuarios.ruc and pagos.dv=usuarios.dv order by pagos.fecha DESC limit 1) >=0,
			 1, 0) as vencido,
	
			IF(  (select estado_mes.regnro  from estado_mes where estado_mes.codcliente= usuarios.regnro and estado_mes.estado='C' 
			order by created_at desc limit 1) IS NULL, 0 , 1)  AS novedad_c_mes,

			IF(  (select estado_anio.regnro  from estado_anio where estado_anio.codcliente= usuarios.regnro and estado_anio.estado='C' 
			order by created_at desc limit 1) IS NULL, 0 , 1)  AS novedad_c_anio

			 
			"
		);
		//fILTRAR
		if( $argumento !=  "" ){
			$usu= 	$usu->like('ruc', $argumento)
		->orLike( 'cedula', $argumento )
		->orLike( 'cliente', $argumento); 
		} 
	
 

		$lista_m = $usu->paginate(10);
		$pager =  $usu->pager;
		if( $this->request->isAJAX())
		return view("admin/clientes/list", ['clientes'=>  $lista_m, 'pager'=> $pager ]);
		else 
		return view("admin/clientes/index", ['clientes'=> $lista_m, 'pager'=> $pager   ]);
	 }








	public function show( $id = null)
	{
		$this->API_MODE=  $this->isAPI();
		$us= (new Usuario_model())
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, 
				telefono, celular, domicilio, ciudad, rubro,
				saldo_IVA, ultimo_nro, clave_marangatu")->
				where("regnro", $id  )->first();
		 
	 
		if( is_null(  $us))
		return $this->genericResponse(  null, "Usuario con $id no existe", 500);
		else
		return $this->genericResponse(  $us, null, 200);
	}



	public function ruc( $ruc = null)
	{
		$this->API_MODE=  $this->isAPI();
		$us= (new Usuario_model())
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, telefono, celular, domicilio, ciudad, rubro")->
				where("concat(ruc,dv)", $ruc  )->first();
		 
	 
		if( is_null(  $us))
		return $this->genericResponse(  null, "Usuario con el RUC: $ruc no existe", 500);
		else
		return $this->genericResponse(  $us, null, 200);
	}






	/**
	 * 
	 * 
	 * Registro de usuario
	 * 
	 */
	/*

		Va li da ci o nes
	*/
	private function campos_referenciales_validos(  $update= false){
	 
		$data = $this->request->getRawInput();

		if (isset($data["tipoplan"])) {
			$tipo_plan =  $data["tipoplan"];
			if (is_null((new Planes_model())->find($tipo_plan))) {
				return  $this->genericResponse(null,  "Codigo $tipo_plan de Tipo de plan no existe", 500);
			}
		}
		if (isset($data["ciudad"])) {
			$ciudad =  $data["ciudad"];
			if (!$ciudad && is_null((new Ciudades_model())->find($ciudad))) {
				return $this->genericResponse(null,  "Codigo $ciudad de ciudad no existe", 500);
			}
		}
		if( isset(  $data["rubro"]) ) {
			$rubro =  $data["rubro"];
			if (!$rubro && is_null((new Rubro_model())->find($rubro))) {
				return $this->genericResponse(null,  "Codigo $rubro de rubro, no existe", 500);
			}
		}
		return null;
	}



	/**
	 * 
	 * EMAIL NOTIFI
	 */
	public function email_bienvenida( $destinatario=""){
		/*******Envio de correo */
		$dest=  $destinatario == "" ? $this->request->getRawInput("email") :  $destinatario;
		$correo= new Correo();
		$correo->setDestinatario( $dest);
		$correo->setAsunto("Bienvenido");
		$correo->setMensaje(   "usuario/welcome_email" );
		$correo->enviar();
		return $this->response->setJSON( ["data"=> "Enviado!", "code"=> "200"]   );
		/********* */
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



	public function create()
	{
		if ($this->request->getMethod(true) == "GET") {
 
			if( $this->isAdminView()  )
			return view("admin/clientes/create");
			else
			return view("usuario/create");
		}


		$this->API_MODE=  $this->isAPI();
		$usu = new Usuario_model();

		$data = $this->request->getRawInput();
		 
		if ($this->validate('usuarios')) {

			//Existe
			$EXISTE=  $this->existe_usuario();
			if( $EXISTE['code'] == 200) return $this->response->setJSON(  [ "msj"=> "RUC ya registrado anteriormente", "code"=> 500]);
			
			//Los campos referenciales son validos?
			$resultadoValidacion = $this->campos_referenciales_validos();
			if (!is_null($resultadoValidacion))  return  $resultadoValidacion;	 
 
			$resu = []; //Resultado de la operacion

			//transaccion
			$db= \Config\Database::connect();

			$db->transStart();
			try {
				//Preparar passw 
				//hash pass
				$data['pass'] = password_hash($data['pass'],  PASSWORD_BCRYPT);
				if( $this->API_MODE )  $data['origen']= "A";//ORIGEN Aplicacion

				$id = $usu->insert($data);//id usuario
				//Registrar ejercicio
				$saldoinicial= array_key_exists( "saldo_IVA",  $data) ?   $data['saldo_IVA'] : 0;
				$nuevo_ejercicio = [
					'codcliente' => $id, 
					'ruc'=> $data['ruc'],
					'dv'=> $data['dv'],
					'anio' => date("Y"),
					't_i_compras' => 0,
					't_i_ventas' => 0,
					't_retencion' => 0 ,
					'saldo'=> 0,
					'saldo_inicial' => $saldoinicial
				];
				(new Estado_anio_model())->insert(   $nuevo_ejercicio);
			/************/
				$this->prueba_gratuita(   $id );
				//Email bienvenida
				if(! $this->API_MODE ) 
				$this->email_bienvenida( $data['email']);
				$usuario_Response= (new Usuario_model())
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, telefono, celular, domicilio, ciudad, rubro, saldo_IVA")->
				where("regnro", $id  )->first();
				$resu = $this->genericResponse($usuario_Response, null, 200);

			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			$db->transComplete();
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				return $this->response->setJSON( $resu );
				//if ($resu['code'] == 200) {
				//	return $this->response->setJSON( $resu );
				//	return redirect()->to(base_url("usuario/sign_in"));
				//} else  return view("usuario/create", array("error" => $resu['msj']));
			}
		}
		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion=  $this->genericResponse(null, $validation->getErrors(), 500);
		if(  $this->API_MODE)
		return $resultadoValidacion;
		else
		return $this->response->setJSON( $resultadoValidacion );
		//return view("usuario/create", array("error" => $resultadoValidacion['msj']));
	}





	/**
	 * 
	 * Validar cambio RUC y DV */ 

	 private function cambio_valido_ruc_dv(){
		$data = $this->request->getRawInput();
		$regnro = $data['regnro'];
		$ruc = $data['ruc'];
		$dv = $data['dv']; 
		$usu= (new Usuario_model())->find( $regnro);
		return ( $usu->ruc ==  $ruc &&  $usu->dv ==  $dv);
	 }

	//ruc=14455&dv=23&cedula=4898&pass=123&tipoplan=1&email=sonia@gg.com&cedula=456666&rubro=1&ciudad=1&saldo_IVA=78000
	public function update(   $id = null)
	{
		if ($this->request->getMethod(true) == "GET") {
			$us = new Usuario_model();
			$usua = $us->find($id);

			if ($this->isAdminView()) { 
				return view("admin/clientes/update",  ['usuario' =>   $usua, "OPERACION" => "M"]);
			} else
			return view("usuario/update",  ['usuario' =>   $usua, "OPERACION" => "M"]);
		}

		$this->API_MODE=  $this->isAPI();

		$usu = new Usuario_model();

		$data = $this->request->getRawInput();

		if ($this->validate('usuarios_update')) {


			if (is_null(  $usu->find($id) )) {
				return $this->genericResponse(null, array("error" => "Usuario no existe"), 500);
			} else {
 
				if( $this->isAPI())
				$data['regnro']= $this->getClienteId();

				//QUITAR QUITAR
				unset(  $data['ruc']);
				unset(  $data['dv']);
			 
				if( array_key_exists("pass",  $data))
				$data['pass'] = password_hash($data['pass'],  PASSWORD_BCRYPT);

				$resu_v=  $this->campos_referenciales_validos( true);
				if( !is_null( $resu_v)){
					return $resu_v;
				}
				$resu= [];//resultado de la operacion

				$db= \Config\Database::connect();

				$db->transStart();
			try{
				$usu->where("regnro",  $data['regnro'])
				->set($data)
				->update();

				//actualizar saldo inicial
				$update_estado_anio= (new Estado_anio_model())
				->where( "codcliente",   $data['regnro'] )
				->where("anio", date("Y"));
				if( array_key_exists("saldo_IVA",  $data) )
				$update_estado_anio= $update_estado_anio->set(['saldo_inicial'=>   $data['saldo_IVA']  ]  )->update();
 
				$db->transCommit();

				$usu_Response= (new Usuario_model())
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, telefono, celular, domicilio, ciudad, rubro, saldo_IVA")->
				where("regnro",  $data['regnro'] )->first();
				$resu=  $this->genericResponse($usu_Response , null, 200);
			}catch( Exception $e){
				$db->transRollback();
				$resu=  $this->genericResponse( null, "Hubo un error: ($e)", 500);
			}
			$db->transComplete();
				//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) return	$this->response->setJSON( ['data'=>"ACTUALIZADO", "code"=>"200"] );
				else  return view("usuario/update", array("error" => $resu['msj']));
			}
			}
		}
		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		
		 
		$res_err_val=  $this->genericResponse(null, $validation->getErrors(), 500);
		if( $this->API_MODE)  return   $res_err_val;
		else{
			return $this->response->setJSON( $res_err_val );
		}
	}




	public function delete( $id = null)
	{
 
		$this->API_MODE= $this->isAPI();
 
		$us= $this->model->where( "regnro", $id)->first();
 
		if (is_null( $us))
		return $this->genericResponse(null, "Usuario de ID $id no existe",  500);
		else {
			$this->model->where( "regnro", $id)->delete();
			if( $this->API_MODE)
			return $this->genericResponse("Usuario eliminado", null,  200);
			else 
			return $this->response->setJSON( ['data'=>"USUARIO ELIMINADO", "code"=> "200"] );
		}
	}




/*
Params HTTP POST
application/x-www-form-urlencoded
ruc
dv
*/
	public function verify_password( $CODCLIENTE)
	{
		//Content-Type: application/x-www-form-urlencoded

		$data= $this->request->getRawInput();  
		$pass = $data["pass"]; 
		$recordar= isset(  $data["remember"])  ?  $data["remember"] : "N" ;
		$usu = (new Usuario_model())->find( $CODCLIENTE); 
		
		if(  $recordar == "N"){
			//BORRAR COOKIES
			//unset( $_COOKIE["ivafacil_user_ruc"] );
			setcookie( "ivafacil_user_ruc", NULL , -1,  "/ivafacil", env("DOMINIO"));
			//unset( $_COOKIE["ivafacil_user_dv"] );
			setcookie( "ivafacil_user_dv", NULL , -1,  "/ivafacil", env("DOMINIO"));
			//unset( $_COOKIE["ivafacil_user_pa"] );
			setcookie( "ivafacil_user_pa", NULL , -1,  "/ivafacil", env("DOMINIO"));
			 
			//Verificar session id?
		}else{
			if (isset($_COOKIE['ivafacil_user_ruc']))
			 {
				$usuarioCookie = new Usuario_model();
				$result_pass_comparison =
					$usuarioCookie->where("ruc", $_COOKIE['ivafacil_user_ruc'])
					->where("dv", $_COOKIE['ivafacil_user_dv'])
					->where("remember_pass", $_COOKIE['ivafacil_user_pa'])->first();
				if (!is_null($result_pass_comparison))
					return array("data" => "Contraseña Correcta", "code" =>  200);
			}

		}

			if( !$this->API_MODE  &&  $recordar=="S" && isset( $_COOKIE["ivafacil_user_pa"] )  ){

				$cookie_session=  $_COOKIE["ivafacil_user_pa"];
				if(  $cookie_session ==  $usu->session_id){
					return array( "data"=>"Contraseña Correcta", "code"=>  200);
				}else{
					return array( "msj"=>"Contraseña incorrecta",  "code"=> 500);
				}
			}
			 
			// VERIFICACION DE contrasenha correcta
			if (password_verify($pass, $usu->pass)) {// Pass entered vs. Pass in BD
				return array( "data"=>"Contraseña Correcta", "code"=>  200);
			} else {
				return array( "msj"=>"Contraseña Incorrecta", "code"=>  500); 
			}
		

		
	}










 


	

	/********************************
	 * inicio de sesion PARA WEB
	 */

	private function verificar_cookie_sesion()
	{
		$session =  \Config\Services::session();
		//se pidio recordar sesion?
		$request= \Config\Services::request();
		$recordar_=  ($request->getRawInput());
		 
		
		//verificar cookies
		if (isset($_COOKIE['ivafacil_user_dv']) && isset($_COOKIE['ivafacil_user_ruc']) && isset($_COOKIE['ivafacil_user_pa'])) {

			//comparar passw hasheadas
			$usuarioCookie = new Usuario_model();
			$result_pass_comparison =
				$usuarioCookie->where("ruc", $_COOKIE['ivafacil_user_ruc'])
				->where("dv", $_COOKIE['ivafacil_user_dv'])
				->where("remember_pass", $_COOKIE['ivafacil_user_pa'])->first();

			if (is_null($result_pass_comparison)) {
				//MOSTRAR FORM

				return view("usuario/login");
			} else {

				//Se pidio recordar password?
				if ($result_pass_comparison->remember == "S") {
					//recuperar sesion si es valida
					$hoy = strtotime(date("Y-m-d H:i:s"));
					$expir =  strtotime($result_pass_comparison->remember_expire);
					if ($hoy >  $expir) return view("usuario/login");


					//crear sesion
					$ruc =  $_COOKIE['ivafacil_user_ruc'];
					$dv = $_COOKIE['ivafacil_user_dv'];
					$alt_pa_sessionid = $_COOKIE['ivafacil_user_pa'];
					$newdata = [
						'ruc'  => $ruc,
						'dv'     => $dv,
						'pass_alt' => $alt_pa_sessionid,
						'remember' =>  "S"
					];
					return view("usuario/login", $newdata);
				} else {
					return view("usuario/login");
				}
			}
		}
		//MOSTRAR FORM
		return view("usuario/login");
	}


	private function crear_cookie_recordar_sesion( $CODCLIENTE)
	{
		helper("cookie");
		$request = \Config\Services::request();
		//valores de sesion
		$data= $this->request->getRawInput();
		$ruc =  $data['ruc'];
		$dv =  $data['dv'];
		$remember= isset(  $data["remember"])  ?  $data["remember"] : "N" ;
	
		/**cREDENCIALES */
		//Guardar sesion 
		$fecha_expire_session=     date(  "Y-m-d H:i",   strtotime(date("Y-m-d H:i")." + 10 days")  );
		//Para autenticar desde la API, y tambien para recordar sesiones para clientes web
		$remember_password=  $CODCLIENTE.(  strtotime(date("Y-m-d H:i:s")  ) );
		$SESSIONID=  password_hash( $remember_password,  PASSWORD_BCRYPT);
		//cONDICION PARA PERMITIR RECORDAR PASS PARA CLIENTES DE WEB
		$USU_WEB_PIDE_RECORD_PASS= $remember == "S";

		
		if ($this->isAPI()) {
			 
			$usu_ = new Usuario_model();
			$usu_->where("regnro", $CODCLIENTE);
			$usu_->set(["session_id" => $SESSIONID, 'session_expire' => $fecha_expire_session]);
			$usu_->update($CODCLIENTE);
			//Enviar el session id para usuarios de api
			return $this->genericResponse( $SESSIONID, null,  200);
		}
		if ($USU_WEB_PIDE_RECORD_PASS) {
			$usu_ = new Usuario_model();
			$usu_->where("regnro", $CODCLIENTE);
			$usu_->set(["remember_pass" => $SESSIONID, 'remember_expire' => $fecha_expire_session, 'remember' => 'S']);
			$usu_->update();
			setcookie("ivafacil_user_ruc", $ruc,  time() + 365 * 24 * 60 * 60, "/ivafacil",  env("DOMINIO"));
			setcookie("ivafacil_user_dv", $dv,  time() + 365 * 24 * 60 * 60,  "/ivafacil",  env("DOMINIO"));
			//Crear cookie para password
			setcookie("ivafacil_user_pa", $SESSIONID,  time() + 365 * 24 * 60 * 60,  "/ivafacil",  env("DOMINIO"));
			return redirect()->to(base_url("welcome/index"));
		} else { 
			$usu_ = new Usuario_model();
			$usu_->where("regnro", $CODCLIENTE);
			$usu_->set([  'remember' => 'N']);
			$usu_->update();
			
			return redirect()->to(base_url("welcome/index")); }

	}





	/**
	 * 
	 * 
	 * Control antes de autenticar
	 * 
	 * 
	 */



	private function existe_usuario()
	{
		$data = $this->request->getRawInput();
	 
		$ruc = $data['ruc'];
		$dv = $data["dv"]; 
		$pass = $data["pass"]; 
		$usu = new Usuario_model();
		$usuarioObject = $usu->where("ruc", $ruc)
		->where("dv", $dv)
		->first();
		if (is_null($usuarioObject)) {
			 
			$mensaje_error="";
			if( $ruc == "") $mensaje_error=  "Proporcione el RUC";
			else{
				if( $dv =="") $mensaje_error= "Proporcione el DV (digito verificador)";
				else{
					if( $pass == "") $mensaje_error=  "Ingrese la contraseña";
					else  $mensaje_error= "Usuario con RUC: $ruc - $dv no existe";
				}
			}

			
			$noexiste= array( "msj"=> $mensaje_error, "code"=>  500);
			return $noexiste;
		} else {
			return array( "data"=> $usuarioObject, "code"=>  200); 
		}
	}

	public function servicio_habilitado(  $CODCLIENTE){
	 
		$usuarioObject = (new Usuario_model())->find( $CODCLIENTE);
	 
		$pagos=  (new Pago_model())->where("cliente",  $usuarioObject->regnro )->orderBy("created_at", "DESC")->first();
		 
		if(  is_null( $pagos ) ) 
			return  array("msj"=>"Servicio no habilitado. Debe estar al día con el pago de los servicios", "code"=> 500 );
		else{
			if( strtotime( date("Y-m-d H:i:s") )      <= strtotime( $pagos->validez) ) 
			return array("data"=>"Habilitado", "code"=> 200 );
			else{ 	return  array("msj"=>"Servicio no habilitado. Debe estar al día con el pago de los servicios", "code"=> 500 );}
		} 
	}

	public function clave_marangatu_definida( $CODCLIENTE){
		$usuarioObject = (new Usuario_model())->find( $CODCLIENTE);
		 return  !(  $usuarioObject->clave_marangatu == ""   ||    is_null(  $usuarioObject->clave_marangatu  )   );
		
	}

	public function servicio_habilitado_sess(){
		$cli=  $this->getClienteId();
		$mensa= $this->servicio_habilitado(   $cli );
		return $this->response->setJSON(   $mensa );

	}


	public function sign_in(  )
	{

		$this->API_MODE =  $this->isAPI();  

		$request = \Config\Services::request();
		$session =  \Config\Services::session();

		$data = $this->request->getRawInput();


		if ($request->getMethod(true) == "GET") {

			if( $this->API_MODE)
			return $this->genericResponse(null, "Método no permitido (Solo POST)",  500);
			else
			return $this->verificar_cookie_sesion();//Verifica sesiones guardadas
		} else {

			$usuario__check = $this->existe_usuario(); 
			if ($usuario__check['code'] != 200) {
				if ($this->API_MODE)
				return $this->response->setJSON($usuario__check);
				else
				return view("usuario/login", array("error" => $usuario__check['msj']));
			}

			//oBJETO DE USUARIO
			$usuario_refere= $usuario__check['data'];

			//Servicio habilitado , al dia?
		$check_habilitado =  $this->servicio_habilitado($usuario_refere->regnro);
			if ($check_habilitado['code'] != 200) {
				if ($this->API_MODE)
				return $this->response->setJSON($check_habilitado);
				else
				return view("usuario/login", array("error" => $check_habilitado['msj']));
			}

			//Password correcta
			$check_pass= $this->verify_password(  $usuario_refere->regnro);
			if( $check_pass['code'] !=200){
				if( $this->API_MODE) 
				return  $this->response->setJSON(  $check_pass );
				else
				return view("usuario/login", array("error" => $check_pass['msj'])); 
			}

			//Crear sesion
			$newdata = [
				'id'=>  $usuario_refere->regnro,
				'ruc'  => $usuario_refere->ruc,
				'dv'     => $usuario_refere->dv,
				'origen' => $this->API_MODE ? "A": "W"
			];
			$session->set($newdata);

			//crear cookies de sesion si es necesario 
			return $this->crear_cookie_recordar_sesion(  $usuario_refere->regnro);

			  
		} //END ANALISIS DE PARAMETROS
	} //END SIGN IN




	public function sign_out(  )
	{
		$this->API_MODE=  $this->isAPI();
		$session =  \Config\Services::session();
		$session->destroy();
		if( $this->API_MODE){
			return $this->genericResponse(null, "Sesion terminada",  500);
		}else{
			return redirect()->to(base_url("usuario/sign-in"));
		}
		
	}

	 
	public function passChange(){ 
		if( sizeof($this->request->getPost()) )
		{ 
			//verificar si contrasenha actual es correcta
			$ced= $this->request->getPost("cedula");
			$usuarioObject= new Usuario_model();
			$obj_usr= $usuarioObject->get( $ced);
			$pass=  $obj_usr->pass;
			if( $pass == $this->request->getPost("clave-a") ){
				//cambiar pass
				if($usuarioObject->passwordUpdate()){
					echo json_encode( array("OK"=>"Clave cambiada!") );
				}else{
					echo json_encode( array("error"=>"Errores tecnicos!") );
				} 
			}else{
				echo json_encode( array("error"=>"La clave ingresada es incorrecta") );
			}

		}
		else{ 
			return view("usuario/passwordChange");
		} 

	}








	public function actualizar_saldo($saldo= NULL)
	{
		(new Cierres())->crear_ejercicio();
		//obtener codigo de cliente
		if( is_null(  $saldo ) ){
			$estado_anio=(new Estado_anio_model())->where("codcliente", $this->getClienteId())->where("anio", date("Y"))->first();
			return view("usuario/solici_saldo_ini", ['saldo'=>   $estado_anio->saldo_inicial]);
		}
		$id = $this->getClienteId();
		$response = \Config\Services::response();
		$db = \Config\Database::connect();
		$db->transStart();
		try {
			(new Estado_anio_model())->where("codcliente",  $id)
			->where("anio", date("Y"))
			->where("estado", "P")
			->set(['saldo_inicial' =>  $saldo])->update();
			(new Usuario_model())->where("regnro",  $id)->set(['saldo_IVA' => $saldo])->update();
			$db->transCommit();
			return	$response->setJSON(['data' => "Saldo inicial actualizado",  'code' => "200"]);
		} catch (Exception $x) {
			$db->transRollback();
			return	$response->setJSON(['msj' =>  $x,  'code' => "500"]);
		}
	}

 	
 
	   
	 

 




/**
 * 
 * RECUPERACION DE PASSWORD
 */


	public function olvido_password()
	{
		/*******Envio de correo */
		if ($this->request->getMethod(true)  == "GET")
		return view("usuario/olvido_password");
		else {
			$Params =  $this->request->getRawInput();
			$email =  $Params['email'];
			//Obtenr usuario
			$Cliente = (new Usuario_model())->where("email", $email)->first();
			if (!is_null($Cliente)) {

				//Generar token de recuperacion
				$token_recu_raw =  $Cliente->regnro.strtotime(date("Y-m-d H:i:s"));
				$token_recu_hash=  bin2hex(  $token_recu_raw  );
				 
				//Fecha de validez del token (1 dia)
				$validez=  date("Y-m-d H:i",   strtotime(  date("Y-m-d H:i")." + 1 day"  )  );
				//gUARDAR el token en registro de usuario
				(new Usuario_model())->where( "regnro",  $Cliente->regnro)
				->set(["token_recu"=>  $token_recu_hash, "token_validez"=> $validez ])->update();

				$correo = new Correo();
				$correo->setDestinatario($email);
				$correo->setAsunto("Restauración de contraseña");
				$parametros=  [ "enlace_recu"=> base_url("usuario/recuperar-password/".$token_recu_hash) ]  ;
				$correo->setParametros($parametros);
				$correo->setMensaje("usuario/recupero_password_email");
				$correo->enviar();
				/********* */
				return $this->response->setJSON( 
					 ['data'=>
					  "Revise su bandeja de correo. Le hemos enviado un link de recuperación (Válido hasta el $validez)", 
					  'code'=> '200'] );
			} else{
				return $this->response->setJSON(  ['msj'=> 'Email no registrado en el sistema', 'code'=> '500'] );
			}
		}
	}

	public function recuperar_password( $token_recu_hash= NULL){
		
		if ($this->request->getMethod(true) == "GET") {
			$Cliente = (new Usuario_model())->where("token_recu", $token_recu_hash)->first();
		 
			if (!is_null($Cliente)) {
				//verificar validez de token
				if( strtotime(  $Cliente->token_validez)  <  strtotime( date("Y-m-d") ))
				return view("usuario/recupero_password",
				 ['usuario' =>  $Cliente, 
				 'error'=> 
				 "Este link de recuperación ya caducó. Ingrese a <a style='color: black;' href='".base_url("usuario/olvido-password") ."' >Solicitar nuevo link</a>" ]);
				else
				return view("usuario/recupero_password", ['usuario' =>  $Cliente]);
			} else {

				return $this->response->setJSON(['msj' => 'Token de recuperación no válido o inexistente', 'code' => '500']);
			}
		} else {
			$nuevopass =  $this->request->getRawInput();
			$Cliente = (new Usuario_model())->where("token_recu", $nuevopass['token_recu'])->first();
			if( !is_null($Cliente) ){
				$PASS =  $nuevopass['pass'];
				$PASSHASH= password_hash(   $PASS,  PASSWORD_BCRYPT );
				
				(new Usuario_model())->where("regnro", $Cliente->regnro)
				->set( ['pass'=> $PASSHASH,  'token_recu'=>'', 'token_validez'=>'NULL' ] )->update();
				if( $this->isAPI())
				return $this->response->setJSON(['data' => 'Contraseña cambiada', 'code' => '200']);
				else 	return redirect()->to(base_url("usuario/sign-in"));
			}else{
				return $this->response->setJSON(['msj' => 'Token de recuperación no válido o inexistente', 'code' => '500']);
			}
			

		}
	}





	public function set_marangatu_key(){

		$cli= $this->getClienteId();
		$clave=  $this->request->getRawInput("clave_marangatu");
		try{
			(new Usuario_model())->where(  "regnro",  $cli )
			->set(  ["clave_marangatu"=>  $clave]  )
			->update();
			return $this->response->setJSON(  ['data'=> "Clave Marangatu Actualizada", "code"=>"200"  ]  );
		}catch( Exception $e){	
			return $this->response->setJSON(  ['msj'=> "Error al actualizar", "code"=>"500"  ]  );

		}
	
	}

	public function actualizar_ultimo_nro_fv($numero)
	{
		$codcli = $this->getClienteId();

		(new Usuario_model())->where("regnro",  $codcli)
		->set("ultimo_nro",  $numero)
		->update();
	}













//Respecto al cierre de mes
	public function  novedades()
	{
		$res = (new Estado_mes_model())->where("estado", "C")->get()->getResult();

		if (sizeof($res)  >  0)
		return $this->response->setJSON(['data' => "Hay novedades",  'code' => '200']);
		else
		return $this->response->setJSON(['msj' => "Nada",  'code' => '500']);
	}

//Respecto a la aproximacion del dia de vencimiento para pago de IVA
	public function verificar_vencimiento_iva(   $ultimo_d){

		$ahora= date("d");

		$dia=(new Calendario_model())->where(  "ultimo_d_ruc",  $ultimo_d )->first();
		$dia_v= $dia->dia_vencimiento ;

		$diferencia=  $ahora -  $dia_v;
		$mensaje= "";
		//si es positivo ya vencio en el mes
		if(  $diferencia < 0)
		{
			$diferencia= abs( $diferencia);
			$mensaje= "Vto. de IVA: Faltan $diferencia dia(s)" ;
			return $this->response->setJSON(  ['data'=>   $mensaje ,  'code'=>'200' ]   );
		}	return $this->response->setJSON(  ['msj'=>  "Nada" ,  'code'=>'500' ]   );
	}


	 private function list_priority_( $argumento){
		$sql_str=
		"
		SELECT `usuarios`.*,
if( (select DATEDIFF( CURRENT_TIMESTAMP, pagos.validez) from pagos where pagos.ruc = usuarios.ruc and pagos.dv=usuarios.dv order by pagos.fecha DESC limit 1) >=0, 1, 0) as vencido,
(select DATEDIFF( CURRENT_TIMESTAMP, pagos.validez) from pagos where pagos.ruc = usuarios.ruc and pagos.dv=usuarios.dv order by pagos.fecha DESC limit 1 ) as diasvenci, 
IF( (select estado_mes.regnro from estado_mes where estado_mes.codcliente= usuarios.regnro and estado_mes.estado<>'L' order by created_at desc limit 1) IS NULL, 0, 1) AS novedad_c_mes,
IF( (select estado_anio.regnro from estado_anio where estado_anio.codcliente= usuarios.regnro and estado_anio.estado='C' order by created_at desc limit 1) IS NULL, 0, 1) AS novedad_c_anio, 
(select estado_mes.mes from estado_mes where estado_mes.codcliente= usuarios.regnro and estado_mes.estado<>'L' order by created_at desc limit 1) AS c_mes,
(select estado_mes.anio from estado_mes where estado_mes.codcliente= usuarios.regnro and estado_mes.estado<>'L' order by created_at desc limit 1) AS c_anio 
FROM `usuarios` 
			"
	;

	//fILTRAR
	if ($argumento !=  "") {
		$sql_str = 	$sql_str . "   where  usuarios.ruc like '%$argumento%'  or usuarios.cedula like '%$argumento%'  or usuarios.cliente like  '%$argumento%'  ";
	}
		//order by
		$sql_str = $sql_str . "
ORDER BY
 
 
 IF( (select estado_mes.regnro from estado_mes where estado_mes.codcliente= usuarios.regnro and estado_mes.estado='C' order by created_at desc limit 1) IS NULL, 0 , 1) DESC,

	IF( (select estado_anio.regnro from estado_anio where estado_anio.codcliente= usuarios.regnro and estado_anio.estado='C' order by created_at desc limit 1) IS NULL , 0 , 1) DESC,

	(select IF(DATEDIFF( CURRENT_TIMESTAMP, pagos.validez) >= 0, 0, 1 ) from pagos 
 where pagos.ruc = usuarios.ruc and pagos.dv=usuarios.dv order by pagos.fecha DESC limit 1) DESC 

	limit 0, 15
	";
	 

	$db = \Config\Database::connect();
	$query = $db->query( $sql_str);
	$lista_m=  $query->getResult();
	return  $lista_m;
	 }


	 public function list_priority(){

		/**Parametros POST */
		$argumento= "";
		if(  $this->request->getMethod( true )  ==  "POST"){
			$data=  $this->request->getRawInput();
			$argumento=  $data['argumento'];
		}
		$lista_m=  $this->list_priority_( $argumento);
//$lista_m = $usu->paginate(10);
//		$pager =  $usu->pager;
		if( $this->request->isAJAX())
		return view("admin/clientes/list", ['clientes'=>  $lista_m ]);
		else 
		return view("admin/clientes/index", ['clientes'=> $lista_m  ]);
		
	 }






	public  function calcular_digito_verificador( $tcNumero, $tnBaseMax= 11) {
		$lcCaracter="";  ; $lnDigito="";
		$lcNumeroAl = "";

		for ($i = 0; $i < strlen($tcNumero)  ; $i++) {
			$lcCaracter = strtoupper(substr(  $tcNumero, $i,1));
			if (  ord($lcCaracter)  < 48 ||  ord($lcCaracter) > 57)
				$lcNumeroAl = $lcNumeroAl . $lcCaracter;
			else
				$lcNumeroAl = $lcNumeroAl .$lcCaracter;
		}
		 
		$k = 2;
		$lnTotal = 0;
		for ( $i = strlen($lcNumeroAl ) - 1; $i >= 0; $i--) {
			if ( $k > $tnBaseMax)
				$k = 2;

			$lnNumeroAux = intval(  substr( $lcNumeroAl, $i, 1)); //VAL
			$lnTotal = $lnTotal + ( $lnNumeroAux * $k);
			$k = $k + 1;
		}
		$lnResto = $lnTotal % 11;
		if ( $lnResto > 1)
			$lnDigito = 11 - $lnResto;
		else
			$lnDigito = 0;
		return $this->response->setJSON(  ['data'=>  $lnDigito,  'code'=>'200' ] );

	}



}
