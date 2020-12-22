<?php
 namespace App\Controllers;

use App\Libraries\Correo;
use App\Models\Admin_model; 
use App\Models\Usuario_model;
use CodeIgniter\Controller; 
use Exception;

 

class Admin extends Controller {
  



	public function __construct()
	{ 
		date_default_timezone_set("America/Asuncion");
		helper("form");
		
		
	}



 




	
	public function index()
	{
		return view("admin/home");
	}

	  
	public function list(){
		
		$adminis= (new Admin_model());
		$lista_m= $adminis->paginate(10);
		$pager=  $adminis->pager;
 
		if(  $this->request->isAJAX()){
			return view("admin/admin/list",  ['adminis'=>  $lista_m, "pager"=>$pager] );
		}else{
			return view("admin/admin/index",  ['adminis'=>  $lista_m, "pager"=>$pager] );
		}
	
	}





	/********************************
	 * inicio de sesion PARA WEB
	 */

	private function verificar_cookie_sesion()
	{
		$session =  \Config\Services::session();
		//verificar cookies
		if (isset($_COOKIE['ivafacil_admin_nick']) && isset($_COOKIE['ivafacil_admin_pa']) ) {

			$nick= $_COOKIE['ivafacil_admin_nick'];
			$pass=$_COOKIE['ivafacil_admin_pa'];
			//comparar passw hasheadas
			$usuarioCookie = new Admin_model();
			$result_pass_comparison =
			$usuarioCookie->where("nick",  $nick) 
			->where("session_id", $pass)->first();

			if (is_null($result_pass_comparison)) {
				//MOSTRAR FORM

				return view("admin/login");
			} else {

				//Se pidio recordar password?
				if ($result_pass_comparison->remember == "S") {
					//recuperar sesion si es valida
					$hoy = strtotime(date("Y-m-d H:i:s"));
					$expir =  strtotime($result_pass_comparison->session_expire);
					if ($hoy >  $expir) return view("admin/login");

					//crear sesion 
					$newdata = [
						'nick'  => $nick, 
						'pass_alt' => $pass,
						'remember'=>  "S"
					];
					return view("admin/login", $newdata);
				} else {
					return view("admin/login");
				}
			}
		}
		//MOSTRAR FORM
		return view("admin/login");
	}


	private function crear_cookie_recordar_sesion()
	{
		helper("cookie");
		$request = \Config\Services::request();
		//valores de sesion
		$data= $this->request->getRawInput();
		$nick =  $data['nick'];
		$pass =  $data['pass'];
		
		 

		//cONDICION PARA PERMITIR RECORDAR PASS PARA CLIENTES DE WEB
		$USU_WEB_PIDE_RECORD_PASS= $request->getPost("remember") == "S"  &&  $request->getPost("remember") != NULL;
		
		if (    $USU_WEB_PIDE_RECORD_PASS ) {
			try {
				//Guardar sesion 
				$usu_ = new Admin_model();
				$usu_->where("nick", $nick);
				$ID= $usu_->first()->regnro;

				 $fecha_expire_session=     date(  "Y-m-d H:i",   strtotime(date("Y-m-d H:i")." + 10 days")  );
				 //Para autenticar desde la API, y tambien para recordar sesiones para clientes web
				 $SESSIONID=  password_hash( $ID,  PASSWORD_BCRYPT);

				 $usu_->where("regnro", $ID);
				$usu_->set(["session_id" => $SESSIONID, 'session_expire'=> $fecha_expire_session ,'remember'=>'S' ]);
				$usu_->update();
				// crear cookies  
					setcookie("ivafacil_admin_nick", $nick,  time() + 365 * 24 * 60 * 60, "/ivafacil",  env("DOMINIO"));
					//Crear cookie para password
					setcookie("ivafacil_admin_pa", $SESSIONID,  time() + 365 * 24 * 60 * 60,  "/ivafacil",  env("DOMINIO"));
					return redirect()->to(base_url("admin/index"));
				 
			
			} catch (Exception $e) {
				 
				return view("admin/login", array("error" => $e));
			}
		} else { //Camino accesible solo para web request
			// Olvidar sesion
			try { 
				//Actualizar campo rememebr
			 
				return redirect()->to(base_url("admin/index"));
			} catch (Exception $e) {
				return view("admin/login", array("error" => $e));
			}
		}
	}




	
	public function verify_password()
	{
		//Content-Type: application/x-www-form-urlencoded

		$request= \Config\Services::request(); 

		$data= $this->request->getRawInput();
		$nick = $data['nick']; 
		$pass = $data["pass"]; 
		$recordar= $request->getPost("remember"); 
		$usu = new Admin_model();
		$usuarioObject = $usu->where("nick", $nick) 
		->first() ;
		///Usuario existe?
		if (is_null($usuarioObject)) {
			if( $nick == "")  return array( "msj"=> "Proporcione el nick",  "code"=>500);
			else{
				if( $pass =="") return array( "msj"=> "Ingrese la contraseña",  "code"=>500);
				else{
					
					return array( "msj"=> "Usuario '$nick' no existe",  "code"=>500);
				}
			} 
		} else {

			//Verificar session id?
			if(    $recordar=="S" && isset( $_COOKIE["ivafacil_admin_pa"] )  ){

				$cookie_session=  $_COOKIE["ivafacil_admin_pa"];
				if(  $cookie_session ==  $usuarioObject->session_id){
					return array( "data"=>"Contraseña Correcta", "code"=>  200);
				}else{
					return array( "msj"=>"Contraseña incorrecta",  "code"=> 500);
				}
			}
			 
			// VERIFICACION DE contrasenha correcta
			if (password_verify($pass, $usuarioObject->pass)) {// Pass entered vs. Pass in BD
				return array( "data"=>"Contraseña Correcta", "code"=>  200);
			} else {
				
				return array( "msj"=>"Contraseña incorrecta",  "code"=> 500);
			}
		}

		
	}




	
	public function sign_in(  )
	{

		 
		$request = \Config\Services::request();
		$session =  \Config\Services::session();

		$data = $this->request->getRawInput();


		if ($request->getMethod(true) == "GET") {

			return $this->verificar_cookie_sesion();//Verifica sesiones guardadas
		} else {
		 
			$resu = $this->verify_password();
		 
			if ($resu['code'] == 200) {
				//crear sesion
				
				$nick = $data["nick"]; 
				$usuarioId = (new Admin_model())->where("nick", $nick) 
				->first() ;
				$newdata = [
					'id'=>  $usuarioId->regnro,
					'nick'  => $nick, 
					'origen' =>  "W"
				];

				$session->set($newdata);
			
			
				//Crear cookie
				//Se pidio recordar contrasenha?
				 
				return $this->crear_cookie_recordar_sesion();
			
			} else { 
					return view("admin/login", array("error" => $resu['msj']));
			}
		} //END ANALISIS DE PARAMETROS
	} //END SIGN IN





	public function sign_out(  )
	{ 
		$session =  \Config\Services::session();
		$session->destroy();
		return redirect()->to(base_url("admin/sign-in"));
		
		
	}














	/**Validaciones antes de cargar */
	private function existe_usuario()
	{
		$data = $this->request->getRawInput();
		$ruc = $data['nick']; 
		$usu = new Admin_model();
		$usuarioObject = $usu->where("nick", $ruc) 
		->first();
		return !is_null($usuarioObject);
		 
	}

	public function create()
	{
		if( $this->request->getMethod( true) == "GET") return view("admin/admin/create");
		$usu = new Admin_model();
		$data = $this->request->getPost();
 
		$validation =  \Config\Services::validation();
	 

		if ( $this->validate("admins") ) {

			//Existe 
			if( $this->existe_usuario())
			{	
				return $this->response->setJSON( array("msj"=> "Ya existe un usuario con ese nick" , "code"=> 500) );
		  
			}
  
			//transaccion
			$db= \Config\Database::connect();

			$db->transStart();
			try {
				//Preparar passw 
				//hash pass
				$data['pass'] = password_hash($data['pass'],  PASSWORD_BCRYPT); 
				$id = $usu->insert($data);  
				$db->transCommit(); 
				return $this->response->setJSON( array("data"=>  (new Admin_model())->find( $id) , "code"=> 200) );
			} catch (Exception $e) {
				$db->transRollback();
				return $this->response->setJSON( array("msj"=> "Error al guardar" , "code"=> 500) );
			}
			$db->transComplete();
			 
			
		}
		//Hubo errores de validacion
	//	$validation = \Config\Services::validation();
		return $this->response->setJSON( array("msj"=>   $validation->getErrors() , "code"=> 500) );
	  
	}




	
	public function update( $id= null)
	{
		if( $this->request->getMethod( true) == "GET"){ 
			$adm= (new Admin_model())->find( $id );
			if( is_null($adm))
			return redirect()->to("admin/index");
			else{
				if( $this->request->isAJAX())
				return view("admin/admin/update_ajax", ['administrador'=> $adm, 'OPERACION'=>'M']);
				else
				return view("admin/admin/update", ['administrador'=> $adm, 'OPERACION'=>'M']);
			}
			
		}

		$usu = new Admin_model();
		$data = $this->request->getPost();
		$usuarioo= (new Admin_model())->find( $data['regnro']);
	
 
		$validation =  \Config\Services::validation();
	 

		if ( $this->validate("admins_update") ) {

			//Existe 
			if( $this->existe_usuario())
			{	
				if( $data['nick'] !=  $usuarioo->nick ) 
				return $this->response->setJSON( array("msj"=> "Ya existe un usuario con ese nick" , "code"=> 500) );
		  
			}
  
			//transaccion
			$db= \Config\Database::connect();

			$db->transStart();
			try {
				//Preparar passw 
				//hash pass
				if( array_key_exists( "pass",  $data ))
				$data['pass'] = password_hash($data['pass'],  PASSWORD_BCRYPT);
				 
				$usu->set($data)
				->where("regnro", $data['regnro' ]  )
				->update();  

				$db->transCommit(); 
				$modeloActual= (new Admin_model())->find( $data['regnro' ] );
				return $this->response->setJSON( array("data"=>  $modeloActual , "code"=> 200) );
			} catch (Exception $e) {
				$db->transRollback();
				return $this->response->setJSON( array("msj"=> "Error al guardar" , "code"=> 500) );
			}
			$db->transComplete();
			 
			
		}
		//Hubo errores de validacion
	//	$validation = \Config\Services::validation();
		return $this->response->setJSON( array("msj"=>   $validation->getErrors() , "code"=> 500) );
	  
	}




	public function delete(  $id= null){
		if( is_null( (new Admin_model())->find( $id ) )  ){
			return $this->response->setJSON( array("msj"=>  "Registro no existe" , "code"=> 500) );
		}else{
			(new Admin_model())->where( "regnro", $id )->delete();
			return $this->response->setJSON( array("data"=> "Borrado" , "code"=> 200) );
		}
			 
	}
	

}