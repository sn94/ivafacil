<?php namespace Config;

use App\Filters\LoggedUser;
use App\Filters\RightAccess;
use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     => \CodeIgniter\Filters\CSRF::class,
		'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,
		'logged_user'=> LoggedUser::class,
		//'right_access'=> RightAccess::class
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			//'honeypot'
			// 'csrf',
			'logged_user'
			//'right_access'
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	/*public $filters = [
		'logged_user'=> ['before'=> [ "prestamo/*", 'deudor/*', 'garante/*',  'caja/*','cargo/*',
						'categoria_monto/*', 'funcionario/*', 'usuario/create', 'usuario/edit','usuario/delete', 'usuario/index'] ]
	];*/
}
