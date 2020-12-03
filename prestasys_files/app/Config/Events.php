<?php namespace Config;
 
use CodeIgniter\Events\Events;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', function () {
	if (ENVIRONMENT !== 'testing')
	{
		while (\ob_get_level() > 0)
		{
			\ob_end_flush();
		}

		\ob_start(function ($buffer) {
			return $buffer;
		});
	}

	/*
	 * --------------------------------------------------------------------
	 * Debug Toolbar Listeners.
	 * --------------------------------------------------------------------
	 * If you delete, they will no longer be collected.
	 */
	if (ENVIRONMENT !== 'production')
	{
		Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
		Services::toolbar()->respond();
	}
});


//Restaurar datos  de permiso predeterminados
/*
Events::on('post_controller_constructor', function () {
	 

	$db = \Config\Database::connect();
	//YA ESTA CARGADA LA TABLA DE PERMISOS PREDETERMINADOS
	$NUMERO= $db->table('default_permisos')->countAll();

	if( $NUMERO > 0) return;
	$administrador= [
		["IDNRO"=> "ADDCLIE"],["IDNRO"=> "UPDCLIE"],["IDNRO"=> "DELCLIE"],["IDNRO"=> "REGPRES"],["IDNRO"=> "UPDPRES"],["IDNRO"=> "APRPRES"
		],["IDNRO"=> "RECPRES"],["IDNRO"=> "DELPRES"],["IDNRO"=> "APECIEC"],["IDNRO"=> "COBROS"],
		["IDNRO"=> "ADDUSU"],["IDNRO"=> "UPDUSU"],["IDNRO"=> "DELUSU"],["IDNRO"=> "USUARIO"],
		 [  "IDNRO"=>'ADDFUNC' ], [  "IDNRO"=>'UPDFUNC' ],[  "IDNRO"=>'DELFUNC' ], [  "IDNRO"=>'FUNCIONARIO' ],
		["IDNRO"=> "ADDCAJA"], ["IDNRO"=> "UPDCAJA"], ["IDNRO"=> "DELCAJA"], ["IDNRO"=> "CAJA"] ,
		["IDNRO"=> "ADDMONTO"], ["IDNRO"=> "UPDMONTO"], ["IDNRO"=> "DELMONTO"], ["IDNRO"=> "MONTO"] ,
		["IDNRO"=> "ADDCARGO"], ["IDNRO"=> "UPDCARGO"], ["IDNRO"=> "DELCARGO"], ["IDNRO"=> "CARGO"] 
	];
	$cobrador=  [
		["IDNRO"=> "ADDCLIE"],["IDNRO"=> "UPDCLIE"],["IDNRO"=> "REGPRES"],["IDNRO"=> "UPDPRES"]
		,["IDNRO"=> "APECIEC"],["IDNRO"=> "COBROS"]	];
	$db->transStart();

	foreach( $administrador as $clave=>$valor):
		$db->table('default_permisos')->insert(['IDPERMISO'    => $valor,'OWNER'   => 'A']);
	endforeach;
	foreach( $cobrador as $clave=>$valor):
		$db->table('default_permisos')->insert(['IDPERMISO'    => $valor,'OWNER'   => 'C']);
	endforeach;

	$db->transComplete();
	if(  $db->transStatus()){

	}else{
		echo "HUBO ERROR";
	}
	 
});*/

 