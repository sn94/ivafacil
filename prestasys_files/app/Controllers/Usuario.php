<?php
 namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\Correo;
use App\Libraries\pdf_gen\PDF;
use App\Models\Ciudades_model;
use App\Models\Pago_model;
use App\Models\Planes_model;
use App\Models\Rubro_model;
use App\Models\Usuario_model;
use CodeIgniter\HTTP\Request;
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


	public function t(){
		return view("home");
	}

	private function isAPI(){

        $request = \Config\Services::request();
       $uri = $request->uri;
        if (sizeof($uri->getSegments()) > 0 &&  $uri->getSegment(1) == "api") {
            return true;
        } return false; 
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




	
	public function index()
	{
		$this->API_MODE= $this->isAPI();
		if(  $this->API_MODE ){
			$sesion= $this->request->getHeader('Ivasession');
			if(  $sesion != "")
			return $this->genericResponse($this->model->findAll(), null, 200);
			else 
			return $this->genericResponse( null, "No está autenticado", 500);
		}
	
	}

	public function show( $id = null)
	{
		$this->API_MODE=  $this->isAPI();
		$us=  $this->model->where("regnro", $id)->first();
	 
		if( is_null(  $us))
		return $this->genericResponse(  null, "Usuario con $id no existe", 404);
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
		if( $this->request->getMethod( true) == "GET") return view("usuario/create");



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

				$id = $usu->insert($data);
				$this->prueba_gratuita(   $id );
				//Email bienvenida
				if(! $this->API_MODE ) 
				$this->email_bienvenida( $data['email']);
				$resu = $this->genericResponse($this->model->find($id), null, 200);
				 

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
			return view("usuario/update",  ['usuario' =>   $usua, "OPERACION"=> "M"]);
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
				$resu=  $this->genericResponse($this->model->find($id), null, 200);
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
		return $this->genericResponse(null, "Usuario $id no existe",  404);
		else {
			$this->model->where( "regnro", $id)->delete();
			return $this->genericResponse("Usuario eliminado", null,  200);
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

	private function servicio_habilitado(  $CODCLIENTE){
	 
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

	 
 

 

 
	  
  

	 
	/**
	 * Reportes
	 */



	public function informes(){
		$dts= (new Usuario_model())->list(); 
		return view( "usuario/informes", array("list"=> $dts)  );
	}


	public function generarPdf( $opc= "0"){ 
		$usersList=	(new Usuario_model())->list($opc); 

		$html=<<<EOF
		<style>
		table.tabla{
			color: #003300;
			font-family: helvetica;
			font-size: 8pt;
			border-left: 3px solid #777777;
			border-right: 3px solid #777777;
			border-top: 3px solid #777777;
			border-bottom: 3px solid #777777;
			background-color: #ddddff;
		}
		
		tr.header{
			background-color: #ccccff; 
			font-weight: bold;
		} 
		tr{
			background-color: #ddeeff;
			border-bottom: 1px solid #000000; 
		}
		</style>

		<table class="tabla">
		<thead >
		<tr class="header">
		<td>Cedula</td>
		<td>Nombres</td>
		<td>Usuario</td>
		</tr>
		</thead>
		<tbody>
		EOF;
		foreach( $usersList as $row){
			$html.="<tr> <td>{$row->cedula}</td> <td>{$row->nombres}</td> <td>{$row->usuario}</td> </tr>";
		}
		$html.="</tbody> </table> ";
		/********* */

		$tituloDocumento= "Usuarios-".date("d")."-".date("m")."-".date("yy");

			$this->load->library("PDF"); 	
			$pdf = new PDF(); 
			$pdf->prepararPdf("$tituloDocumento.pdf", $tituloDocumento, ""); 
			$pdf->generarHtml( $html);
			$pdf->generar();
	}
	

	 


	

}
