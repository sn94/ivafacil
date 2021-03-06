<?php namespace Config;

use App\Filters\AdminUser;
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
		'admin_user'=> AdminUser::class
		//'right_access'=> RightAccess::class
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			//'honeypot'
			// 'csrf',
			'logged_user' =>
			['except' => [
				"/",  
				'usuario/create', 'usuario/sign-in', 'usuario/sign_in', 
				'api/usuario/sign-in', 'api/usuario/create' , 'api/usuario/digito-verificador/*',
				'api/usuario/recupera-passw',
				'auxiliar/*', 'api/ciudades', 'api/planes', 'api/monedas', 'api/mensajes',
				'admin',  'admin/*', 'home', 'welcome/publico',
				'usuario/olvido-password','usuario/recuperar-password', 'usuario/recuperar-password/*',
				'admin/olvido-password', 'admin/recuperar-password', 'admin/recuperar-password/*'
			]],
			'admin_user' =>
			['except' => [  
				'admin/sign-in', 'home', 'welcome/publico', 
				'usuario/*', 
				"compra/*", 'venta/*', 'retencion/*',  'movimiento/*' ,'welcome/*','cierres/*', 'pagos/*', 'pagos-iva/*',
				'api/*',
				'auxiliar/*',  
				'admin/olvido-password', 'admin/recuperar-password', 'admin/recuperar-password/*'
				
			]]
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
	public $filters = [
		'logged_user'=> 
		['before'=> [ 
			"/", "compra/*", 'venta/*', 'retencion/*',  'movimiento/*' , 'cierres/*', 'pagos/*', 'pagos-iva/*',
			'welcome/index'
			 ]
		],

		'admin_user'=> 
		['before'=> [ 
			  "admin/parametros/*",  "admin/calendario/*",  "admin/monedas/*",  "admin/planes/*",  "admin/clientes/*",
			   "admin/cierre-mes/*",  "admin/estados-mes/*",  "admin/cierre-anio/*",
			"admin/deshacer-cierre-mes/*",  "admin/recordar-pago/*", 

			 ]
		 ]


	];
}
