<?php
 namespace App\Controllers;

use App\Libraries\Mobile_Detect;
use App\Models\Planes_model;
use CodeIgniter\Controller; 
use Exception;

 

class Planes extends Controller {
  



	public function __construct()
	{ 
		date_default_timezone_set("America/Asuncion");
		helper("form");
		
		
	}



	public function index(){
		
		$planes= (new Planes_model());
		$lista_m= $planes->paginate(10);
		$pager=  $planes->pager;

		if(  $this->request->isAJAX()){
			return view("admin/planes/list",  ['planes'=>  $lista_m, "pager"=>$pager] );
		}else{
			return view("admin/planes/index",  ['planes'=>  $lista_m, "pager"=>$pager] );
		}
	
	}
 




	
	public function create()
	{

		//listar monedas
		$adaptativo= new Mobile_Detect();


		if ($this->request->getMethod(true) ==  "GET") {

			return view("admin/planes/create" );
			
			
		} else{
			$validation = \Config\Services::validation();
			$data= $this->request->getRawInput();

			if( $this->validate("planes")){
				 
				$id=(new Planes_model())
				->insert(  $data  );
				return $this->response->setJSON( array("data"=>   (new Planes_model())->find($id), "code"=> 200) );

			}else{
				return $this->response->setJSON( array("msj"=>   $validation->getErrors() , "code"=> 500) );
			}
		}
	}

	  
	



	
	public function update( $id= null)
	{
 
		if ($this->request->getMethod(true) ==  "GET") {

			$plan= (new Planes_model())->find( $id);
			return view("admin/planes/update", ["planes"=>  $plan, "OPERACION" => "M"] );
			
		} else{
			$validation = \Config\Services::validation();
			$data= $this->request->getRawInput();

			if( $this->validate("planes")){
				 
				$id=(new Planes_model())
				->where("regnro",  $data['regnro'])
				->set(  $data  )
				->update();
				return $this->response->setJSON( array("data"=>   (new Planes_model())->find($id), "code"=> 200) );

			}else{
				return $this->response->setJSON( array("msj"=>   $validation->getErrors() , "code"=> 500) );
			}
		}
	}

	

 

	


	public function show($id = null)
	{
		$re = (new Planes_model())->find($id);
		if (is_null($re))
		return $this->response->setJSON( array("msj"=>   "Divisa de cód. $id no existe", "code"=> 500) );
		else
		return $this->response->setJSON( array("data"=>  $re, "code"=> 200) );
	}






	public function delete(  $id= null){
		if( is_null( (new Planes_model())->find( $id ) )  ){
			return $this->response->setJSON( array("msj"=>  "Registro no existe" , "code"=> 500) );
		}else{
			(new Planes_model())->where( "regnro", $id )->delete();
			return $this->response->setJSON( array("data"=> "Borrado" , "code"=> 200) );
		}
			 
	}

}
