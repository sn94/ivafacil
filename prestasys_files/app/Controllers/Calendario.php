<?php
 namespace App\Controllers;

use App\Libraries\Mobile_Detect;
use App\Models\Calendario_model;
use App\Models\Monedas_model;
use CodeIgniter\Controller; 
use Exception;

 

class Calendario extends Controller {
  



	public function __construct()
	{ 
		date_default_timezone_set("America/Asuncion");
		helper("form");
		
		
	}





	public function index(){
		$lst=  (new Calendario_model())->findAll();
		return $this->response->setJSON( 
			['data'=>  $lst ,   'code'=> '200'] 
		);
	}


	public function get(  $RUC ){

		$ultimo_digi=   substr(  $RUC,  -1, 1);
		$dia= (new Calendario_model())
		->where("ultimo_d_ruc", $ultimo_digi )->first();
		return $this->response->setJSON( 
			['data'=>  $dia ,   'code'=> '200'] 
		);

	}

	public function update()
	{ 
		if ($this->request->getMethod(true) ==  "GET") {
			$calendario = (new Calendario_model())->findAll();
			return view("admin/calendario/index",  ['calendario' =>  $calendario]);
		} else {

			$data = $this->request->getRawInput();
			$regnros =  $data['regnro'];
			$dias =  $data['dia_vencimiento'];
			$respuesta = "";
			$db = \Config\Database::connect();
			$db->transStart();
			try {
				for ($indice = 0; $indice < sizeof($regnros); $indice++) {
					(new Calendario_model())->where("regnro",  $regnros[$indice])
					->set(
						["dia_vencimiento" => $dias[$indice]]
					)
					->update();
				}
				$db->transCommit();
				$respuesta = array("data" => "Actualizado", "code" => 200);
			} catch (Exception $ex) {
				$db->transRollback();
				$respuesta = array("msj" => "$ex", "code" => 500);
			}
			$db->transComplete();

			return $this->response->setJSON($respuesta);
		}
	}

	  
	


 

	


	  
}
