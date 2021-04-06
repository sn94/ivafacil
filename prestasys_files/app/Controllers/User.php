<?php
 namespace App\Controllers;

use App\Models\Ciudades_model;
use App\Models\Planes_model;
use App\Models\Rubro_model;
use App\Models\Usuario_model;
use CodeIgniter\RESTful\ResourceController;
use Exception;

 

class User extends ResourceController {



	protected $modelName = "App\Models\Usuario_model";
	protected $format = "json";


	public function __construct()
	{

		date_default_timezone_set("America/Asuncion");
	}





	private function genericResponse($data, $msj, $code)
	{

		if ($code == 200) {
			return $this->respond(array(
				"data" => $data,
				"code" => $code
			)); //, 404, "No hay nada"
		} else {
			return $this->respond(array(
				"msj" => $msj,
				"code" => $code
			));
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
	

 

	public function create()
    {
 
        $usu = new Usuario_model();

		$data= $this->request->getRawInput();
		if ($this->validate('usuarios')) {

			$tipo_plan =  $data["tipoplan"];
			if (!$tipo_plan && !is_null((new Planes_model())->find($tipo_plan))) {
				return $this->genericResponse(null, array("error" => "Codigo $tipo_plan de Tipo de plan no existe"), 500);
			}

			$ciudad =  $data["ciudad"];
			if (!$ciudad && !is_null((new Ciudades_model())->find($ciudad))) {
				return $this->genericResponse(null, array("error" => "Codigo $ciudad de ciudad no existe"), 500);
			}

			$rubro =  $data["rubro"];
			if (!$rubro && !is_null((new Rubro_model())->find($rubro))) {
				return $this->genericResponse(null, array("error" => "Codigo $rubro de rubro, no existe"), 500);
			}

			$id = $usu->insert( $data);
			return $this->genericResponse($this->model->find($id), null, 200);
		}
 
        $validation = \Config\Services::validation();
 
       return $this->genericResponse(null, $validation->getErrors(), 500);
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
					return $this->genericResponse(null, array("error" => "Codigo $tipo_plan de Tipo de plan no existe"), 500);
				}

				$ciudad =  $data["ciudad"];
				if (!$ciudad && !is_null((new Ciudades_model())->find($ciudad))) {
					return $this->genericResponse(null, array("error" => "Codigo $ciudad de ciudad no existe"), 500);
				}

				$rubro =  $data["rubro"];
				if (!$rubro && !is_null((new Rubro_model())->find($rubro))) {
					return $this->genericResponse(null, array("error" => "Codigo $rubro de rubro, no existe"), 500);
				}
				$usu->update($id, $data);
				return $this->genericResponse($this->model->find($id), null, 200);
			}
		}
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

	

}
