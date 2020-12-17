<?php
 namespace App\Controllers;

 
use App\Models\Admin_model;
use App\Models\Parametros_model;
use App\Models\Usuario_model;
use CodeIgniter\Controller; 
use Exception;

 

class Parametros extends Controller {
  



	public function __construct()
	{ 
		date_default_timezone_set("America/Asuncion");
		helper("form");
		
		
	}



 




	
	public function create()
	{
		if ($this->request->getMethod(true) ==  "GET") {
			//Recoger si ya existe 
			$para= (new Parametros_model())->first();

			if( is_null( $para ) ){
				$param= new Parametros_model();
				$id= $param->insert( ['iva1'=>"10", 'iva2'=>"5", 'iva3'=>"0", 'redondeo'=>"0", 'email'=>"", 'diasgratis'=>"0" ]);
				return view("admin/parametros/create",  ['parametros'=>  (new Parametros_model())->find($id)  ]);
			}else{
				$param= (new Parametros_model())->first();
				return view("admin/parametros/create",  ['parametros'=>  $param ] );
			}
			
		} else{
			$validation = \Config\Services::validation();
			$data= $this->request->getRawInput();

			if( $this->validate("parametros")){
				$id=  $data['regnro'];
				$param= (new Parametros_model())->find(  $id);
				(new Parametros_model())->set(  $data )
				->where("regnro", $id)
				->update();
				return $this->response->setJSON( array("data"=>   (new Parametros_model())->find($id), "code"=> 200) );

			}else{
				return $this->response->setJSON( array("msj"=>   $validation->getErrors() , "code"=> 500) );
			}
		}
	}

	  
	

 

	

}
