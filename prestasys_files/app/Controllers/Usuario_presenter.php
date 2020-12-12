<?php
 namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\pdf_gen\PDF;
use App\Models\Ciudades_model;
use App\Models\Planes_model;
use App\Models\Rubro_model;
use App\Models\Usuario_model;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\RESTful\ResourcePresenter;
use CodeIgniter\Session\Session;
use Exception;

 

class Usuario extends ResourcePresenter {
 
 

	protected $modelName = "App\Models\Usuario_model";
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
		  return $this->genericResponse($this->model->findAll(), null, 200);
		 

	}

	public function show( $id = null)
	{ 
		$us=  $this->model->where("regnro", $id)->first();
	 
		if( is_null(  $us))
		return $this->genericResponse(  null, "Usuario con $id no existe", 404);
		else
		return $this->genericResponse(  $us, null, 200);
	}




	public function create(  )
	{
		if( $this->request->getMethod( true) == "GET") return view("usuario/create");

		$this->API_MODE=  $this->isAPI();
		$usu = new Usuario_model();

		$data = $this->request->getRawInput();
		if ($this->validate('usuarios')) {

		/*	$tipo_plan =  $data["tipoplan"];
			if (!$tipo_plan && !is_null((new Planes_model())->find($tipo_plan))) {
				return  $this->genericResponse(null,  "Codigo $tipo_plan de Tipo de plan no existe", 500);
			}

			$ciudad =  $data["ciudad"];
			if (!$ciudad && !is_null((new Ciudades_model())->find($ciudad))) {
				return $this->genericResponse(null,  "Codigo $ciudad de ciudad no existe", 500);
			}

			$rubro =  $data["rubro"];
			if (!$rubro && !is_null((new Rubro_model())->find($rubro))) {
				return $this->genericResponse(null,  "Codigo $rubro de rubro, no existe", 500);
			}*/

	
			try {
				//Preparar passw 
				//hash pass
				$data['pass'] = password_hash($data['pass'],  PASSWORD_BCRYPT); 
				$id = $usu->insert($data);
				return redirect()->to(base_url("usuario/sign_in")); 
			} catch (Exception $e) {
				return view("usuario/create", array("error" => $e)  ); 
			} 
			
		}
		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion= $validation->getErrors(); 
		return view("usuario/create", array("error" => $resultadoValidacion['msj']));
		 
	}



	public function update(   $id = null)
	{

		$this->API_MODE=  $this->isAPI();

		$usu = new Usuario_model();

		$data = $this->request->getRawInput();

		if ($this->validate('usuarios')) {


			if (!$usu->get($id)) {
				return $this->genericResponse(null, array("error" => "Usuario no existe"), 500);
			} else {

/*
				$tipo_plan =  $data["tipoplan"];
				if (!$tipo_plan && !is_null((new Planes_model())->find($tipo_plan))) {
					return $this->genericResponse(null,   "Codigo $tipo_plan de Tipo de plan no existe", 500);
				}

				$ciudad =  $data["ciudad"];
				if (!$ciudad && !is_null((new Ciudades_model())->find($ciudad))) {
					return $this->genericResponse(null,  "Codigo $ciudad de ciudad no existe", 500);
				}

				$rubro =  $data["rubro"];
				if (!$rubro && !is_null((new Rubro_model())->find($rubro))) {
					return $this->genericResponse(null,   "Codigo $rubro de rubro, no existe", 500);
				}
				$resu= [];//resultado de la operacion*/
			try{
				$usu->update($id, $data);
				$resu=  $this->genericResponse($this->model->find($id), null, 200);
			}catch( Exception $e){
				$resu=  $this->genericResponse( null, "Hubo un error: ($e)", 500);
			}
				//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) return redirect()->to(base_url("/"));
				else  return view("usuario/update", array("error" => $resu['msj']));
			}
			}
		}
		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		return $this->genericResponse(null, $validation->getErrors(), 500);
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
	public function verify_password()
	{
		//Content-Type: application/x-www-form-urlencoded

		$request= \Config\Services::request(); 

		$ruc = $request->getPost("ruc");
		$dv = $request->getPost("dv"); 
		$pass = $request->getPost("pass"); 
		$usu = new Usuario_model();
		$usuarioObject = $usu->where("ruc", $ruc)
		->where("dv", $dv)
		->first() ; 

	//Verificar session id?
			if(  isset( $_COOKIE["ivafacil_user_pa"] )  ){
				$cookie_session=  $_COOKIE["ivafacil_user_pa"];
				return $cookie_session ==  $usuarioObject->session_id;
			}
			 
			// VERIFICACION DE contrasenha correcta
			return password_verify($pass, $usuarioObject->pass);
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


	private function crear_cookie_recordar_sesion()
	{
		helper("cookie");
		$request = \Config\Services::request();
		//valores de sesion
		$ruc =  $request->getPost("ruc");
		$dv =  $request->getPost("dv");
		
		 

		//cONDICION PARA PERMITIR RECORDAR PASS PARA CLIENTES DE WEB
		$USU_WEB_PIDE_RECORD_PASS= $request->getPost("remember") == "S"  &&  $request->getPost("remember") != NULL;
		
		if (   $this->API_MODE  ||  $USU_WEB_PIDE_RECORD_PASS ) {
			try {
				//Guardar sesion 
				$usu_ = new Usuario_model();
				$usu_->where("ruc", $ruc)->where("dv", $dv);
				$ID= $usu_->first()->regnro;

				 $fecha_expire_session=     date(  "Y-m-d H:i",   strtotime(date("Y-m-d H:i")." + 10 days")  );
				 //Para autenticar desde la API, y tambien para recordar sesiones para clientes web
				 $SESSIONID=  password_hash( $ID,  PASSWORD_BCRYPT);

				 $usu_->where("ruc", $ruc)->where("dv", $dv);
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


	public function sign_in(  )
	{

		$this->API_MODE =  $this->isAPI(); //Opcion  disponible solo para Web

		$request = \Config\Services::request();
		$session =  \Config\Services::session();

		if ($request->getMethod(true) == "GET") {
 
			return $this->verificar_cookie_sesion();//Verifica sesiones guardadas
		} else {

			$resu = $this->verify_password();

			if ($resu['code'] == 200) {
				//crear sesion
				$ruc = $request->getPost("ruc");
				$dv = $request->getPost("dv");
				$usuarioId = (new Usuario_model())->where("ruc", $ruc)
				->where("dv", $dv)
				->first() ;
				$newdata = [
					'id'=>  $usuarioId->regnro,
					'ruc'  => $ruc,
					'dv'     => $dv,
					'origen' => $this->API_MODE ? "A": "W"
				];

				$session->set($newdata);
			
			
				//Crear cookie
				//Se pidio recordar contrasenha?
				 
				return $this->crear_cookie_recordar_sesion();
			
			} else { 
					return view("usuario/login", array("error" => $resu['msj']));
			}
		} //END ANALISIS DE PARAMETROS
	} //END SIGN IN




	public function sign_out(  )
	{ 
		$session =  \Config\Services::session();
		$session->destroy(); 
		return redirect()->to(base_url("usuario/sign_in/N"));
		
		
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
