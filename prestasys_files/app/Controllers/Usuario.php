<?php
 namespace App\Controllers;

use App\Helpers\Utilidades;
use App\Libraries\pdf_gen\PDF;
use App\Models\Ciudades_model;
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

	public function show($id = null)
	{
		return $this->genericResponse($this->model->find($id), null, 200);
	}




	public function create( $api_mode= "S" )
	{
		if( $this->request->getMethod( true) == "GET") return view("usuario/create");

		$this->API_MODE=  $api_mode=="S";
		$usu = new Usuario_model();

		$data = $this->request->getRawInput();
		if ($this->validate('usuarios')) {

			$tipo_plan =  $data["tipoplan"];
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
			}

			$resu = []; //Resultado de la operacion
			try {
				//Preparar passw 
				//hash pass
				$data['pass'] = password_hash($data['pass'],  PASSWORD_BCRYPT);
				if( $this->API_MODE )  $data['origen']= "A";//ORIGEN Aplicacion

				$id = $usu->insert($data);
				$resu = $this->genericResponse($this->model->find($id), null, 200);
			} catch (Exception $e) {
				$resu = $this->genericResponse(null, "Hubo un error al registrar ($e)", 500);
			}
			//Evaluar resultado
			if ($this->API_MODE) return  $resu;
			else {
				if ($resu['code'] == 200) return redirect()->to(base_url("usuario/sign_in"));
				else  return view("usuario/create", array("error" => $resu['msj']));
			}
		}
		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		$resultadoValidacion=  $this->genericResponse(null, $validation->getErrors(), 500);
		if(  $this->API_MODE)
		return $resultadoValidacion;
		else
		return view("usuario/create", array("error" => $resultadoValidacion['msj']));
	}



	public function update($id = null)
	{

		$usu = new Usuario_model();

		$data = $this->request->getRawInput();

		if ($this->validate('usuarios')) {


			if (!$usu->get($id)) {
				return $this->genericResponse(null, array("error" => "Usuario no existe"), 500);
			} else {


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
				$resu= [];//resultado de la operacion
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
				else  return view("cliente/update", array("error" => $resu['msj']));
			}
			}
		}
		//Hubo errores de validacion
		$validation = \Config\Services::validation();
		return $this->genericResponse(null, $validation->getErrors(), 500);
	}




	public function delete($id = null)
	{

		$movie = new Usuario_model();
		if (
			is_null($movie->find($id))
		)
		return $this->genericResponse(null, "Usuario $id no existe",  404);
		else {
			$movie->delete($id);
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
		$usu = new Usuario_model();
		$usuarioObject = $usu->where("ruc", $ruc)
		->where("dv", $dv)
		->first() ;
		///Usuario existe?
		if (is_null($usuarioObject)) {
			return $this->genericResponse(null, "Usuario con RUC: $ruc - $dv no existe",  500);
		} else {
			//existe
			$pass = $request->getPost("pass");
			// VERIFICACION DE contrasenha correcta
			if (password_verify($pass, $usuarioObject->pass)) {// Pass entered vs. Pass in BD
				return $this->genericResponse("Correcto", null,  200);
			} else {
				return $this->genericResponse(null, "Incorrecto",  500);
			}
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
			$result_pass_comparison = $usuarioCookie->where("ruc", $_COOKIE['ivafacil_user_ruc'])
			->where("dv", $_COOKIE['ivafacil_user_dv'])
			->where("session_id", $_COOKIE['ivafacil_user_pa'])->first();
			if (is_null($result_pass_comparison)) {
				//MOSTRAR FORM

				return view("usuario/login");
			} else {
				//recuperar sesion
				//crear sesion
				$ruc =  $_COOKIE['ivafacil_user_ruc'];
				$dv = $_COOKIE['ivafacil_user_dv'];
				$newdata = [
					'ruc'  => $ruc,
					'dv'     => $dv,
					'origen' => "W"
				];

				$session->set($newdata); 
				 return redirect()->to(base_url("/"));
			}
		}
		//MOSTRAR FORM
		return view("usuario/login");
	}


	private function crear_cookie_recordar_sesion()
	{
		$request = \Config\Services::request();
		//valores de sesion
		$ruc =  $request->getPost("ruc");
		$dv =  $request->getPost("dv");
		if ($request->getPost("remember") != ""  &&  $request->getPost("remember") != NULL) {
			try {
				//Encriptar la passw
				$hasheada = Utilidades::generar_password();
				$usu_ = new Usuario_model();
				$usu_->where("ruc", $ruc)->where("dv", $dv);
				$usu_->set(["session_id" => $hasheada]);
				$usu_->update();
				setcookie("ivafacil_user_ruc", $ruc,  time() + 365 * 24 * 60 * 60, "/ivafacil",  env("DOMINIO"));
				setcookie("ivafacil_user_dv", $dv,  time() + 365 * 24 * 60 * 60,  "/ivafacil",  env("DOMINIO"));
				//Crear cookie para password
				setcookie("ivafacil_user_pa", $hasheada,  time() + 365 * 24 * 60 * 60,  "/ivafacil",  env("DOMINIO"));
				return redirect()->to(base_url("/"));
			} catch (Exception $e) {
				return view("usuario/login", array("error" => $e));
			}
		} else {
			// Olvidar sesion
			try {
				$usu_ = new Usuario_model();
				$usu_->where("ruc", $ruc)->where("dv", $dv);
				$usu_->set(["session_id" => ""]);
				$usu_->update();
				//borrar cookies
				unset($_COOKIE['ivafacil_user_dv']);
				unset($_COOKIE['ivafacil_user_ruc']);
				unset($_COOKIE['ivafacil_user_pa']);
				return redirect()->to(base_url("/"));
			} catch (Exception $e) {
				return view("usuario/login", array("error" => $e));
			}
		}
	}


	public function sign_in()
	{

		$this->API_MODE =  false; //Opcion  disponible solo para Web

		$request = \Config\Services::request();
		$session =  \Config\Services::session();

		if ($request->getMethod(true) == "GET") {

			return $this->verificar_cookie_sesion();
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
					'origen' => "W"
				];

				$session->set($newdata);
				//Crear cookie
				//Se pidio recordar contrasenha?
				return $this->crear_cookie_recordar_sesion();
			
			} else  return view("usuario/login", array("error" => $resu['msj']));
		} //END ANALISIS DE PARAMETROS
	} //END SIGN IN




	public function sign_out()
	{
		$session =  \Config\Services::session();
		$session->destroy();
		return redirect()->to(base_url("usuario/sign_in"));
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
