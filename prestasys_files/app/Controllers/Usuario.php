<?php
 namespace App\Controllers;

use App\Libraries\pdf_gen\PDF;
use App\Models\Usuario_model;
use Exception;

 

class Usuario extends BaseController {
 
	 public function __construct(){
	 
	 
		date_default_timezone_set("America/Asuncion");
	 
		helper("form");
	 }

	public function index()
	{
	
	 
	}

	public function  registro(){

		return view("cliente/registro");
	}


	

	/**
	 * inicio de sesion
	 */

	public function sign_in(){
	 
		 
		$session=  \Config\Services::session();

		 if(  is_null( $this->request->getPost("usua") ) ){//SI no hay parametros
			//MOSTRAR FORM
			return view("login/index");
		 }else{
				//DATOS DE SESIOn
				$usr= $this->request->getPost("usua");
				//OBTENER NRO REG DE USUARIO a partir de su NICK
				$usuarioObject= new Usuario_model();
				$d_u= $usuarioObject->getByName( $usr);
				//VERIFICAR EXISTENCIA DE USUARIO
				if( is_null( $d_u) ){//no existe
					return view("login/index", array("errorSesion"=> "El usuario ->$usr<- no existe") );
				}else{
					$id_usr=$d_u->cedula; 
					$nom= $d_u->nombres; 
					$pass= $this->request->getPost("pass");
					$tipo= $d_u->tipousuario; 
	
					// VERIFICACION DE contrasenha correcta
					if( $usuarioObject->correctPassword( $pass, $usr) ){
						$newdata = array( 	'id' => $id_usr, 'usuario'  => $usr, 'nombres' => $nom,'tipo'     => $tipo);
						//Creacion de sesion
						$session->set( $newdata);//CREACION DE LA SESION 
						return redirect()->to(  base_url("welcome")); 
					}else{
					//	echo json_encode(  array('error' => "Clave incorrecta" )); 
						return view("login/index", array("errorSesion"=> "Clave incorrecta") );
					}
				}//end else
				
		 }//END ANALISIS DE PARAMETROS
	}//END SIGN IN

	public function sign_out(){
		$session=  \Config\Services::session();
		 $session->destroy(); 
		return redirect()->to(    base_url("usuario/sign_in")   ); 
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

	 

	public function list_vendedores(  ){ 
		echo json_encode( (new Usuario_model())->list_vendedores() ); 
	}
	
	public function list( $opc= "0"){ 
		$usersList=	(new Usuario_model())->list($opc); 
		echo json_encode(  $usersList);
	}

	public function create(){ 
		if( session("tipo") == "S"){
			$d= $this->request->getPost();
			if( sizeof($d) ){
				(new Usuario_model())->add();
				return view("plantillas/success", array("mensaje"=>"Datos de Usuario agregado"));
			
			}else{ 
				return view("usuario/create"); 
			} 
		}else {
			return redirect()->to(  base_url("welcome") );
		}
	}

 


	public function edit($cedula, $permitir= 0 ){
		if( session("tipo") == "S" || $permitir ){
			$usuarioObject= new Usuario_model();

			if( $this->request->getMethod(false) == "get" )
			{
				//var_dump( $this->request->getPost()   );
				//POBLAR FORMULARIO 
				$cli= $usuarioObject->get( $cedula );
				return view("usuario/edit_express", array("datos"=> $cli) );
			}else{
				$d= NULL;// PAYLOAD DE FORMULARIO
				$d= $this->request->getPost();
				if( sizeof($d) ){ 

					$usuarioObject->edit();
					return view("plantillas/success", array("mensaje"=>"Datos de usuario Actualizado")); 
					 
				}else{  return view("usuario/edit" );  	} //end else
			}//end else
		}else {  return redirect()->to(  base_url("welcome") );	}
	}

	public function delete( $id= "" ){
		echo  (new Usuario_model())->del( $id);
	}
	 

	//busqueda por cedula
	public function view(  $ci){
		$dt= (new Usuario_model())->get( $ci ) ;
		return view("usuario/view", array("datos"=> $dt));
          		
   }
	//busqueda por cedula
	public function get(  $ci){
		$dt= (new Usuario_model() )->get( $ci ) ;
		if( is_null ($dt )){
            echo json_encode(  array('error' => "Este usuario no existe" )); 
         } else{ 
			echo json_encode($dt );
		}		
   }

   public function getByName(  $n, $permitir= 0){
	$session=  \Config\Services::session();
	if( !$session->has("usuario")  || $session->has("tipo") == "S" || $permitir){
		
			$dt= (new Usuario_model())->getByName( $n ) ;
			if( is_null ($dt )){
				echo json_encode(  array('error' => "Este usuario no existe" )); 
			} else{ 
				echo json_encode($dt );
			}	 
	}else {
		return redirect()->to(  base_url("welcome") );
	}	
}



	public function getForRead(  $ci){

		if( session("tipo") == "S"){
		
				$dt= (new Usuario_model())->get( $ci ) ;
				if( is_null ($dt )){
					echo json_encode(  array('error' => "Este usuario no existe" )); 
				} else{ 
					return view("usuario/view_data", array("datos"=> $dt));
					
				} 
		}else {
			return redirect()->to(  base_url("welcome") );
		}			
   }


   public function getForReadByNick(  $nick){

	if( session("tipo") == "S"){
	
			$dt= (new Usuario_model())->getByName( $nick ) ;
			if( is_null ($dt )){
				echo json_encode(  array('error' => "Este usuario no existe" )); 
			} else{ 
				return view("usuario/view_data", array("datos"=> $dt));
			} 
	}else {
		return redirect()->to(  base_url("welcome") );
	}			
}


   public function getForEditByNick(  $nick){

	if( session("tipo") == "S"){
	
		$dt=(new Usuario_model())->getByName( $nick ) ;
		if( is_null ($dt)){
		   echo json_encode(  array('error' => "Este usuario no existe" )); 
		}else{ 
		   return view("usuario/edit_data", array("datos"=> $dt));
			
		}	
	}else {
		return redirect()->to(  base_url("welcome") );
	}	
}

	public function getForEdit(  $ci){

		if( session("tipo") == "S"){
		
			$dt= (new Usuario_model())->get( $ci ) ;
			if( is_null ($dt)){
			   echo json_encode(  array('error' => "Este usuario no existe" )); 
			}else{ 
			   return view("usuario/edit_data", array("datos"=> $dt));
				
			}	
		}else {
			return redirect()->to(  base_url("welcome") );
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
