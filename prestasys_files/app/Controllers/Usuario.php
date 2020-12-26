<?php
 namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\Correo;
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
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, telefono, celular, domicilio, ciudad, rubro")->get()->getResult();
				return $this->genericResponse($usu_filter, null, 200);
			}
			else 
			return $this->genericResponse( null, "No está autenticado", 500);
		}
	
	}


	
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
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, telefono, celular, domicilio, ciudad, rubro")->
				where("regnro", $id  )->first();
		 
	 
		if( is_null(  $us))
		return $this->genericResponse(  null, "Usuario con $id no existe", 500);
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


			$tipo_plan =  $data["tipoplan"]; 
			if (is_null((new Planes_model())->find($tipo_plan))) {
				return  $this->genericResponse(null,  "Codigo $tipo_plan de Tipo de plan no existe", 500);
			}

			$ciudad =  $data["ciudad"];
			if (!$ciudad && is_null((new Ciudades_model())->find($ciudad))) {
				return $this->genericResponse(null,  "Codigo $ciudad de ciudad no existe", 500);
			}
			$rubro =  $data["rubro"];
			if (!$rubro && is_null((new Rubro_model())->find($rubro))) {
				return $this->genericResponse(null,  "Codigo $rubro de rubro, no existe", 500);
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
		$DIASPLAN= 30;
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
			if( $EXISTE['code'] == 200)
			{	
				
				return $this->response->setJSON(  [ "msj"=> "RUC ya registrado anteriormente", "code"=> 500]);
			}

			//Ya existe RUC y DV
			//Los campos referenciales son validos?
			$resultadoValidacion = $this->campos_referenciales_validos();
			if (!is_null($resultadoValidacion)) {
				 return  $resultadoValidacion;	 
			}
 
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
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, telefono, celular, domicilio, ciudad, rubro")->
				where("regnro", $id  )->first();
				$resu = $this->genericResponse($usuario_Response, null, 200);
				 

			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			$db->transComplete();
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) {
					return $this->response->setJSON( $resu );
				//	return redirect()->to(base_url("usuario/sign_in"));
				} else  return view("usuario/create", array("error" => $resu['msj']));
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
			try{
				$usu->where("regnro",  $data['regnro'])
				->set($data)
				->update();
				$usu_Response= (new Usuario_model())
				->select("regnro, ruc, dv, tipoplan, email, cliente, cedula, telefono, celular, domicilio, ciudad, rubro")->
				where("regnro",  $data['regnro'] )->first();
				$resu=  $this->genericResponse($usu_Response , null, 200);
			}catch( Exception $e){
				$resu=  $this->genericResponse( null, "Hubo un error: ($e)", 500);
			}
			
				//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) return	$this->response->setJSON( ['data'=>"ACTUALIZADO", "code"=>"200"] );
				else  return view("cliente/update", array("error" => $resu['msj']));
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
		
			//Verificar session id?
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
		//verificar cookies
		if (isset($_COOKIE['ivafacil_user_dv']) && isset($_COOKIE['ivafacil_user_ruc']) && isset($_COOKIE['ivafacil_user_pa'])) {

			//comparar passw hasheadas
			$usuarioCookie = new Usuario_model();
			$result_pass_comparison =
			$usuarioCookie->where("ruc", $_COOKIE['ivafacil_user_ruc'])
			->where("dv", $_COOKIE['ivafacil_user_dv'])
			->where("session_id", $_COOKIE['ivafacil_user_pa'])->first();

			if (is_null($result_pass_comparison)) {
				//MOSTRAR FORM

				return view("usuario/login");
			} else {

				//Se pidio recordar password?
				if ($result_pass_comparison->remember == "S") {
					//recuperar sesion si es valida
					$hoy = strtotime(date("Y-m-d H:i:s"));
					$expir =  strtotime($result_pass_comparison->session_expire);
					if ($hoy >  $expir) return view("usuario/login");


					//crear sesion
					$ruc =  $_COOKIE['ivafacil_user_ruc'];
					$dv = $_COOKIE['ivafacil_user_dv'];
					$alt_pa_sessionid = $_COOKIE['ivafacil_user_pa'];
					$newdata = [
						'ruc'  => $ruc,
						'dv'     => $dv,
						'pass_alt' => $alt_pa_sessionid,
						'remember'=>  "S"
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
	
		//cONDICION PARA PERMITIR RECORDAR PASS PARA CLIENTES DE WEB
		$USU_WEB_PIDE_RECORD_PASS= $remember == "S";
		
		if (   $this->API_MODE  ||  $USU_WEB_PIDE_RECORD_PASS ) {
			try {
				//Guardar sesion 
				 $fecha_expire_session=     date(  "Y-m-d H:i",   strtotime(date("Y-m-d H:i")." + 10 days")  );
				 //Para autenticar desde la API, y tambien para recordar sesiones para clientes web
				 $SESSIONID=  password_hash( $CODCLIENTE,  PASSWORD_BCRYPT);
				 $usu_ = new Usuario_model(); 
				 $usu_->where("regnro", $CODCLIENTE);
				$usu_->set(["session_id" => $SESSIONID, 'session_expire'=> $fecha_expire_session ,'remember'=>'S' ]);
				$usu_->update();
				//Solo crear cookies para clientes  autenticados deSDE la web
				if (!$this->API_MODE) {
					setcookie("ivafacil_user_ruc", $ruc,  time() + 365 * 24 * 60 * 60, "/ivafacil",  env("DOMINIO"));
					setcookie("ivafacil_user_dv", $dv,  time() + 365 * 24 * 60 * 60,  "/ivafacil",  env("DOMINIO"));
					//Crear cookie para password
					setcookie("ivafacil_user_pa", $SESSIONID,  time() + 365 * 24 * 60 * 60,  "/ivafacil",  env("DOMINIO"));
					return redirect()->to(base_url("/"));
				}else{
					//Enviar el session id para usuarios de api
					return $this->genericResponse( $SESSIONID, null,  200);
				}
			
			} catch (Exception $e) {
				if( $this->API_MODE)
				return $this->genericResponse( null, "Error al tratar de crear cookies",  500);
				else
				return view("usuario/login", array("error" => $e));
			}
		} else { //Camino accesible solo para web request
			// Olvidar sesion
			try {
				/*$usu_ = new Usuario_model();
				$usu_->where("ruc", $ruc)->where("dv", $dv);
				$usu_->set(["session_id" => ""]);
				$usu_->update();
				//borrar cookies
				unset($_COOKIE['ivafacil_user_dv']);
				unset($_COOKIE['ivafacil_user_ruc']);
				unset($_COOKIE['ivafacil_user_pa']);*/
				return redirect()->to(base_url("/"));
			} catch (Exception $e) {
				return view("usuario/login", array("error" => $e));
			}
		}
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
	 
		$pagos=  (new Pago_model())->where("cliente",  $usuarioObject->regnro )->orderBy("fecha", "DESC")->first();
		if(  is_null( $pagos ) ) 	return  array("msj"=>"Servicio no habilitado", "code"=> 500 );
		else{
			if( strtotime( date("Y-m-d H:i:s") )      <= strtotime( $pagos->validez) ) 
			return array("data"=>"Habilitado", "code"=> 200 );
			else{ 	return  array("msj"=>"Servicio no habilitado", "code"=> 500 );}
		} 
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
			/*$check_habilitado =  $this->servicio_habilitado($usuario_refere->regnro);
			if ($check_habilitado['code'] != 200) {
				if ($this->API_MODE)
				return $this->response->setJSON($check_habilitado);
				else
				return view("usuario/login", array("error" => $check_habilitado['msj']));
			}*/

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








	public function actualizar_saldo( $saldo){

		//obtener codigo de cliente
		$id= $this->getClienteId();
		$response = \Config\Services::response();

		try{
			(new Estado_anio_model())->where( "codcliente",  $id )
			->where("anio", date("Y"))
			->where("estado", "P")
			->set(['saldo_inicial'=>  $saldo] )->update();
			return	$response->setJSON( ['data'=> "Saldo inicial actualizado",  'code'=> "200" ]);
		}catch( Exception $x){
		 return	$response->setJSON( ['msj'=>  $x,  'code'=> "500" ]);
		}

	}

	public function list_pagos($id)
	{
		$pagos =	(new Pago_model())->where("cliente",  $id)
			->select(" pagos.regnro, pagos.comprobante, pagos.fecha, pagos.created_at, planes.descr as plan")
			->join(
				"planes",
				"planes.regnro=pagos.plan"
			);
		$lista_m = $pagos->paginate(10);
		$pager =  $pagos->pager;

		return view("admin/clientes/grill_pagos",  ['pagos' =>  $lista_m, "pager" =>  $pager]);
	}	

	public function pagar(  $id= null){

		if( $this->request->getMethod( true) == "GET")
		return view( "admin/clientes/pagos", ['CLIENTE'=>  $id]);
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





}
