<?php namespace Config;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var array
	 */
	public $ruleSets = [
		\CodeIgniter\Validation\Rules::class,
		\CodeIgniter\Validation\FormatRules::class,
		\CodeIgniter\Validation\FileRules::class,
		\CodeIgniter\Validation\CreditCardRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------


	//Usuarios
	public $usuarios= [
		'ruc'     => 
		[
            'rules'  => 'required|max_length[15]|integer',
            'errors' => [
				'required' => 'Proporciona un número de RUC',
				'max_length'=>'La longitud máxima permitida para el RUC es de 15 caracteres',
				'integer'=>'El Número de RUC solo admite valores numéricos'

            ]
		],
		'dv'     => 
		[
            'rules'  => 'required|max_length[2]|integer',
            'errors' => [
				'required' => 'El digito verificador es obligatorio',
				'max_length'=>'La longitud máxima permitida para el digito verificador es de 2 caracteres',
				'integer'=>'El dígito verificador (DV) solo admite valores numéricos'
            ]
		],
		'pass'     => 
		[
            'rules'  => 'if_exist|required|max_length[80]',
            'errors' => [
				'required' => 'La contraseña de usuario es requerida',
				'max_length'=>'La longitud máxima permitida de la contraseña es de 80 caracteres'
            ]
		],
		/*'tipo'     => 
		[
            'rules'  => 'required|max_length[1]',
            'errors' => [
				'required' => 'Por favor indique el tipo de usuario',
				'max_length'=>'La longitud máxima permitida para el campo tipo es de 1 caracter'
            ]
        ],
		
	 	'fechainicio'     => 
		[
            'rules'  => 'if_exist|valid_date',
            'errors' => [
				'valid_date'=>'El valor para el campo "Fecha de inicio" no es valida'
            ]
		],*/
		'tipoplan'     => 
		[
            'rules'  => 'required|integer',
            'errors' => [
				'required'=>'Proporcione el tipo de plan',
				'integer'=>'El campo "Tipo plan" debe ser numerico'
            ]
		],
	/*	'estado'     => 
		[
            'rules'  => 'max_length[1]',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "estado" es de 1 caracter'
            ]
		],*/
		'email'     => 
		[
            'rules'  => 'required|max_length[120]|valid_email',
            'errors' => [
				'required'=>'Proporcione su email',
				'max_length'=>'La longitud maxima del valor para campo "email" es de 120 caracteres',
				'valid_email'=>'El email proporcionado no es valido'
            ]
		],
		'cliente'     => 
		[
            'rules'  => 'required|max_length[80]',
            'errors' => [
				'required'=>'Proporcione su nombre o razón social',
				'max_length'=>'La longitud maxima para nombre de cliente es de 80 caracteres' 
            ]
		],
		'cedula'     => 
		[
            'rules'  => 'if_exist|max_length[10]|integer',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "cedula" es de 10 caracteres' ,
				'integer'=>'El campo "cedula" solo permite caracteres numericos'
            ]
		],

		'telefono'     => 
		[
            'rules'  => 'if_exist|max_length[20]',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "telefono" es de 20 caracteres' ,	 
            ]
		],

		'celular'     => 
		[
            'rules'  => 'if_exist|max_length[20]',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "celular" es de 20 caracteres' ,	 
            ]
		],
		'domicilio'     => 
		[
            'rules'  => 'if_exist|max_length[100]',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "domicilio" es de 100 caracteres' ,	  
            ]
		],
		'ciudad'     => 
		[
            'rules'  => 'required|integer',
            'errors' => [
				'required'=>'Proporcione ciudad',
				'integer'=>'El campo "Ciudad" debe ser numerico'
            ]
		],
		'rubro'     => 
		[
            'rules'  => 'required|integer',
            'errors' => [
				'required'=>'Proporcione rubro de actividad',
				'integer'=>'El campo "Rubro" debe ser numerico'
            ]
		]
		/*'saldo_IVA'     => 
		[
            'rules'  => 'max_length[10]|integer',
            'errors' => [
			 
				'max_length'=>'La longitud maxima del valor para campo "saldo IVA" es de 10 caracteres' ,	
				'integer'=> 'El campo "saldo IVA" solo permite caracteres numericos'

            ]
		]*/
         
		 
	];  


	public $usuarios_update= [
		 
		 
		'pass'     => 
		[
            'rules'  => 'if_exist|required|max_length[80]',
            'errors' => [
				'required' => 'La contraseña de usuario es requerida',
				'max_length'=>'La longitud máxima permitida de la contraseña es de 80 caracteres'
            ]
		],
		 
		'tipoplan'     => 
		[
            'rules'  => 'if_exist|integer',
            'errors' => [
				'integer'=>'El campo "Tipo plan" debe ser numerico'
            ]
		],
	 
		'email'     => 
		[
            'rules'  => 'if_exist|required|max_length[120]|valid_email',
            'errors' => [
				'required'=>'Proporcione su email',
				'max_length'=>'La longitud maxima del valor para campo "email" es de 120 caracteres',
				'valid_email'=>'El email proporcionado no es valido'
            ]
		],
		'cliente'     => 
		[
            'rules'  => 'if_exist|max_length[80]',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "cliente" es de 80 caracteres' 
            ]
		],
		'cedula'     => 
		[
            'rules'  => 'if_exist|max_length[10]|integer',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "cedula" es de 10 caracteres' ,
				'integer'=>'El campo "cedula" solo permite caracteres numericos'
            ]
		],

		'telefono'     => 
		[
            'rules'  => 'if_exist|max_length[20]',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "telefono" es de 20 caracteres' ,	 
            ]
		],

		'celular'     => 
		[
            'rules'  => 'if_exist|max_length[20]',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "celular" es de 20 caracteres' ,	 
            ]
		],
		'domicilio'     => 
		[
            'rules'  => 'if_exist|max_length[100]',
            'errors' => [
				'max_length'=>'La longitud maxima del valor para campo "domicilio" es de 100 caracteres' ,	  
            ]
		],
		'ciudad'     => 
		[
            'rules'  => 'if_exist|integer',
            'errors' => [
				'integer'=>'El campo "Ciudad" debe ser numerico'
            ]
		],
		'rubro'     => 
		[
            'rules'  => 'if_exist|integer',
            'errors' => [
				'integer'=>'El campo "Rubro" debe ser numerico'
            ]
		]
         
		 
	]; //$validation->run($data, 'usuarios');


	public $compras = [
		 
		'iva_incluido' =>
		[
			'rules'  => 'required|alpha|max_length[1]',
			'errors' => [
				'required' => 'Indica si se trata de IVA INCLUIDO',
				'max_length' => 'El campo iva_incluido solo permite un caracter',
				'alpha'=> 'El campo iva_incluido es de tipo alfabético'
			]
		],
		'fecha' =>
		[
			'rules'  => 'required|valid_date',
			'errors' => [
				'required' => 'Proporciona la fecha de factura',
				'valid_date' => 'Proporcione una fecha válida de factura'
			]
		],

	/*	'factura' =>
		[
			'rules'  => 'min_length[0]|max_length[13]|integer',
			'errors' => [
				//'required' => 'Indique el número de factura',
				'max_length' => 'El número de factura no debe sobrepasar los 13 dígitos',
				'integer' => 'El número de factura solo admite valores numéricos'

			]
		],*/
		'moneda'  =>
		[
			'rules'  => 'required|max_length[2]|integer',
			'errors' => [
				'required' => 'Indique código de moneda',
				'max_length' => 'El código de moneda no debe sobrepasar los 2 dígitos',
				'integer' => 'El código de moneda solo admite valores numéricos'

			]
		], 

		'importe1' => /* Monto aplicable a 10 */
		[
			'rules'  => 'if_exist|max_length[15]|numeric',
			'errors' => [
				 
				'max_length' => 'El total IVA 10% no debe sobrepasar los 10 dígitos',
				'numeric' => 'El total IVA 10%  solo admite valores numéricos'

			]
		],
		'importe2' => /* monto aplicable 5*/
		[
			'rules'  => 'if_exist|max_length[15]|numeric',
			'errors' => [
				 
				'max_length' => 'El total IVA 5% no debe sobrepasar los 10 dígitos',
				'numeric' => 'El total IVA 5% solo admite valores numéricos'

			]
		],
		'importe3' => /* monto exento */
		[
			'rules'  => 'if_exist|max_length[15]|numeric',
			'errors' => [
				 
				'max_length' => 'El campo "Exenta" no debe sobrepasar los 10 dígitos',
				'numeric' => 'El campo "Exenta" solo admite valores numéricos'

			]
		]
		 
	];
	public $ventas = [
		 
		'iva_incluido' =>
		[
			'rules'  => 'required|alpha|max_length[1]',
			'errors' => [
				'required' => 'Indica si se trata de IVA INCLUIDO',
				'max_length' => 'El campo iva_incluido solo permite un caracter',
				'alpha'=> 'El campo iva_incluido es de tipo alfabético'
			]
		],
		'fecha' =>
		[
			'rules'  => 'required|valid_date',
			'errors' => [
				'required' => 'Proporciona la fecha de factura',
				'valid_date' => 'Proporcione una fecha válida de factura'
			]
		],

		'factura' =>
		[
			'rules'  => 'max_length[15]',
			'errors' => [
				//'required' => 'Indique el número de factura',
				'max_length' => 'El número de factura no debe sobrepasar los 15 dígitos',
				'integer' => 'El número de factura solo admite valores numéricos'

			]
		],
		'moneda'  =>
		[
			'rules'  => 'required|max_length[2]|integer',
			'errors' => [
				'required' => 'Indique código de moneda',
				'max_length' => 'El código de moneda no debe sobrepasar los 2 dígitos',
				'integer' => 'El código de moneda solo admite valores numéricos'

			]
		], 

		'importe1' => /* Monto aplicable a 10 */
		[
			'rules'  => 'if_exist|max_length[10]|numeric',
			'errors' => [
				 
				'max_length' => 'El total IVA 10% no debe sobrepasar los 10 dígitos',
				'numeric' => 'El total IVA 10%  solo admite valores numéricos'

			]
		],
		'importe2' => /* monto aplicable 5*/
		[
			'rules'  => 'if_exist|max_length[10]|numeric',
			'errors' => [
				 
				'max_length' => 'El total IVA 5% no debe sobrepasar los 10 dígitos',
				'numeric' => 'El total IVA 5% solo admite valores numéricos'

			]
		],
		'importe3' => /* monto exento */
		[
			'rules'  => 'if_exist|max_length[10]|numeric',
			'errors' => [
				 
				'max_length' => 'El campo "Exenta" no debe sobrepasar los 10 dígitos',
				'numeric' => 'El campo "Exenta" solo admite valores numéricos'

			]
		] 
		 
	];


	public $ventas_update = [
		'iva_incluido' =>
		[
			'rules'  => 'required|alpha|max_length[1]',
			'errors' => [
				'required' => 'Indica si se trata de IVA INCLUIDO',
				'max_length' => 'El campo iva_incluido solo permite un caracter',
				'alpha'=> 'El campo iva_incluido es de tipo alfabético'
			]
		],
		'fecha' =>
		[
			'rules'  => 'if_exist|valid_date',
			'errors' => [
				'required' => 'Proporciona la fecha de factura',
				'valid_date' => 'Proporcione una fecha válida de factura'
			]
		],

		'factura' =>
		[
			'rules'  => 'if_exist|max_length[15]',
			'errors' => [
				//'required' => 'Indique el número de factura',
				'max_length' => 'El número de factura no debe sobrepasar los 15 dígitos',
				'integer' => 'El número de factura solo admite valores numéricos'

			]
		],
		'moneda'  =>
		[
			'rules'  => 'if_exist|max_length[2]|integer',
			'errors' => [
				'required' => 'Indique código de moneda',
				'max_length' => 'El código de moneda no debe sobrepasar los 2 dígitos',
				'integer' => 'El código de moneda solo admite valores numéricos'

			]
		], 

		'importe1' => /* Monto aplicable a 10 */
		[
			'rules'  => 'if_exist|max_length[10]|numeric',
			'errors' => [
				 
				'max_length' => 'El total IVA 10% no debe sobrepasar los 10 dígitos',
				'numeric' => 'El total IVA 10%  solo admite valores numéricos'

			]
		],
		'importe2' => /* monto aplicable 5*/
		[
			'rules'  => 'if_exist|max_length[10]|numeric',
			'errors' => [
				 
				'max_length' => 'El total IVA 5% no debe sobrepasar los 10 dígitos',
				'numeric' => 'El total IVA 5% solo admite valores numéricos'

			]
		],
		'importe3' => /* monto exento */
		[
			'rules'  => 'if_exist|max_length[10]|numeric',
			'errors' => [
				 
				'max_length' => 'El campo "Exenta" no debe sobrepasar los 10 dígitos',
				'numeric' => 'El campo "Exenta" solo admite valores numéricos'

			]
		] 
		 
	];



	public $ventas_anuladas = [
		 
		 
		'fecha' =>
		[
			'rules'  => 'required|valid_date',
			'errors' => [
				'required' => 'Proporciona la fecha de factura',
				'valid_date' => 'Proporcione una fecha válida de factura'
			]
		]
	];

	public $retencion = [
		 
		 
		 
		'fecha' =>
		[
			'rules'  => 'required|valid_date',
			'errors' => [
				'required' => 'Proporciona la fecha de factura',
				'valid_date' => 'Proporcione una fecha válida de factura'
			]
		],

		/*'retencion' =>
		[
			'rules'  => 'required|max_length[20]|integer',
			'errors' => [
				'required' => 'Indique el número de retención',
				'max_length' => 'El número de retención debe sobrepasar los 20 dígitos',
				'integer' => 'El número de retención solo admite valores numéricos'

			]
		],*/
		'moneda'  =>
		[
			'rules'  => 'required|max_length[2]|integer',
			'errors' => [
				'required' => 'Indique código de moneda',
				'max_length' => 'El código de moneda no debe sobrepasar los 2 dígitos',
				'integer' => 'El código de moneda solo admite valores numéricos'

			]
		], 

		'importe' => 
		[
			'rules'  => 'if_exist|max_length[15]|numeric',
			'errors' => [
				 
				'max_length' => 'El importe retenido no debe sobrepasar los 15 dígitos',
				'integer' => 'El importe retenido solo admite valores numéricos'

			]
		]  
		 
	];


	public $admins= [
		'nick'     => 
		[
            'rules'  => 'required|max_length[20]',
            'errors' => [
				'required' => 'Proporciona un nick',
				'max_length'=>'La longitud máxima permitida para el nick es de 20 caracteres'
            ]
		],
		'email'     => 
		[
            'rules'  => 'required|max_length[120]|valid_email',
            'errors' => [
				'required'=>'Proporcione su email',
				'max_length'=>'La longitud maxima del valor para campo "email" es de 120 caracteres',
				'valid_email'=>'El email proporcionado no es valido'
            ]
		],
		'pass'     => 
		[
            'rules'  => 'required|max_length[80]',
            'errors' => [
				'required' => 'La contraseña de usuario es requerida',
				'max_length'=>'La longitud máxima permitida de la contraseña es de 80 caracteres'
            ]
		]
			];

	public $admins_update = [
		'nick'     =>
		[
			'rules'  => 'required|max_length[20]',
			'errors' => [
				'required' => 'Proporciona un nick',
				'max_length' => 'La longitud máxima permitida para el nick es de 20 caracteres'
			]
		],
		'email'     =>
		[
			'rules'  => 'required|max_length[120]|valid_email',
			'errors' => [
				'required' => 'Proporcione su email',
				'max_length' => 'La longitud maxima del valor para campo "email" es de 120 caracteres',
				'valid_email' => 'El email proporcionado no es valido'
			]
		],
		'pass'     =>
		[
			'rules'  => 'if_exist|required|max_length[80]',
			'errors' => [
				'required' => 'La contraseña de usuario es requerida',
				'max_length' => 'La longitud máxima permitida de la contraseña es de 80 caracteres'
			]
		]
	];
		
		

	public $parametros = [
		'EMAIL'     =>
		[
			'rules'  => 'if_exist|max_length[120]|valid_email',
			'errors' => [
				'max_length' => 'La longitud máxima permitida para el email es de 120 caracteres',
				'valid_email' => 'El email proporcionado no es válido'
			]
		],
		'REDONDEO'     =>
		[
			'rules'  => 'if_exist|max_length[1]',
			'errors' => [ 
				'max_length' => 'La longitud maxima del valor para campo "Redondeo" es de 1 dígito' 
			]
		],
		'DIASGRATIS'     =>
		[
			'rules'  => 'if_exist|max_length[3]',
			'errors' => [ 
				'max_length' => 'La longitud máxima permitida para "Dias de prueba" es de 3 caracteres'
			]
		]
	];


	public $monedas = [
		'moneda'     =>
		[
			'rules'  => 'required|max_length[50]',
			'errors' => [
				'required' => 'El nombre de moneda es requerido',
				'max_length' => 'El nombre de moneda es hasta 50 caracteres'
				
			]
		],
		'prefijo'     =>
		[
			'rules'  => 'if_exist|max_length[3]',
			'errors' => [ 
				'max_length' => 'La longitud maxima del código de moneda es de 3 caracteres' 
			]
		],
		'tcambio'     =>
		[
			'rules'  => 'if_exist|max_length[10]|integer',
			'errors' => [ 
				'max_length' => 'La longitud máxima permitida para el cambio de moneda es de 10 dígitos',
				'integer'=> "El cambio de moneda solo admite valores numéricos"
			]
			] 	
		 
	];



	

	public $planes = [
		'descr'     =>
		[
			'rules'  => 'required|max_length[100]',
			'errors' => [
				'required' => 'Favor detallar descripción del servicio',
				'max_length' => 'Descripción: Hasta 100 caracteres'
				
			]
		],
		'precio'     =>
		[
			'rules'  => 'if_exist|max_length[10]',
			'errors' => [ 
				'max_length' => 'Precio: La longitud maxima de dígitos es 10' 
			]
		] 	
		 
	];


}
