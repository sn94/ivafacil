<?php

namespace App\Controllers;

use App\Models\Cargo_model;
use Exception;

class Welcome extends BaseController
{


	public function __construct()
	{
		date_default_timezone_set("America/Asuncion");
	}


	public function index()
	{

		//Si por alguna razon no se creo el registro de Ejercicio, crear un registro vacio
		//Siempre que este metodo no sea llamado a partir de una ruta de Usuario administrador
		if (!$this->isAdminView()) (new Cierres())->crear_ejercicio();
		return view("principal_panel");
	}



	public function publico()
	{
		return view("home");
	}
}
