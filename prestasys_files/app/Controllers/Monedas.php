<?php
 namespace App\Controllers;

use App\Libraries\Mobile_Detect;
use App\Models\Monedas_model;
use CodeIgniter\Controller; 
use Exception;

 

class Monedas extends Controller {
  



	public function __construct()
	{ 
		date_default_timezone_set("America/Asuncion");
		helper("form");
		
		
	}



	public function index(){
		
		$monedas= (new Monedas_model());
		$lista_m= $monedas->paginate(10);
		$pager=  $monedas->pager;

		if(  $this->request->isAJAX()){
			return view("admin/monedas/list",  ['monedas'=>  $lista_m, "pager"=>$pager] );
		}else{
			return view("admin/monedas/index",  ['monedas'=>  $lista_m, "pager"=>$pager] );
		}
	
	}
 




	
	public function create()
	{

		//listar monedas
		$adaptativo= new Mobile_Detect();


		if ($this->request->getMethod(true) ==  "GET") {

			return view("admin/monedas/create" );
			
			
		} else{
			$validation = \Config\Services::validation();
			$data= $this->request->getRawInput();

			if( $this->validate("monedas")){
				 
				$id=(new Monedas_model())
				->insert(  $data  );
				return $this->response->setJSON( array("data"=>   (new Monedas_model())->find($id), "code"=> 200) );

			}else{
				return $this->response->setJSON( array("msj"=>   $validation->getErrors() , "code"=> 500) );
			}
		}
	}

	  
	



	
	public function update( $id= null)
	{
 
		if ($this->request->getMethod(true) ==  "GET") {

			$moneda= (new Monedas_model())->find( $id);
			return view("admin/monedas/update", ["moneda"=>  $moneda, "OPERACION" => "M"] );
			
		} else{
			$validation = \Config\Services::validation();
			$data= $this->request->getRawInput();

			if( $this->validate("monedas")){
				 
				$id=(new Monedas_model())
				->where("regnro",  $data['regnro'])
				->set(  $data  )
				->update();
				return $this->response->setJSON( array("data"=>   (new Monedas_model())->find($id), "code"=> 200) );

			}else{
				return $this->response->setJSON( array("msj"=>   $validation->getErrors() , "code"=> 500) );
			}
		}
	}

	

 

	

}
