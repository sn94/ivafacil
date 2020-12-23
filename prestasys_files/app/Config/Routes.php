<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Welcome');
$routes->setDefaultMethod('publico');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
/*
$routes->presenter("usuario_presenter", ['controller'=> 'usuario']);
$routes->resource('usuario', ['controller'=>  'user']);
*/

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


$routes->get('/home', 'Welcome::publico'); //pagina publica

$routes->get('/', 'Welcome::index'); //pagina principal del cliente




//api 
$routes->get('/api/cities', 'Auxiliar::ciudades'); 
$routes->get('/api/plans', 'Auxiliar::planes'); 
$routes->get('/api/currencies', 'Auxiliar::monedas'); 

$routes->get('/api/purchase', 'Compra::index'); 
$routes->post('/api/purchase/create', 'Compra::create'); 
$routes->put('/api/purchase/(:num)', 'Compra::update/$1'); 
$routes->get('/api/purchase/(:num)', 'Compra::show/$1'); 
$routes->delete('/api/purchase/(:num)', 'Compra::delete/$1');

$routes->get('/api/sales', 'Venta::index'); 
$routes->post('/api/sales/create', 'Venta::create'); 
$routes->put('/api/sales/(:num)', 'Venta::update/$1'); 
$routes->get('/api/sales/(:num)', 'Venta::show/$1'); 
$routes->delete('/api/sales/(:num)', 'Venta::delete/$1');

$routes->get('/api/retention', 'Retencion::index'); 
$routes->post('/api/retention/create', 'Retencion::create'); 
$routes->put('/api/retention/(:num)', 'Retencion::update/$1'); 
$routes->get('/api/retention/(:num)', 'Retencion::show/$1'); 
$routes->delete('/api/retention/(:num)', 'Retencion::delete/$1');

$routes->get('/api/user', 'Usuario::index'); 
$routes->post('/api/user/create', 'Usuario::create'); 
$routes->put('/api/user/(:num)', 'Usuario::update/$1'); 
$routes->delete('/api/user/(:num)', 'Usuario::delete/$1'); 
$routes->get('/api/user/(:num)', 'Usuario::show/$1'); 
$routes->post('/api/user/sign-in', 'Usuario::sign_in'); 
$routes->post('/api/email-user-registered', 'Usuario::email_bienvenida'); 



//Administrativo 

$routes->get('/admin/parametros/create', 'Parametros::create'); 
$routes->post('/admin/parametros/create', 'Parametros::create'); 

$routes->get('/admin/monedas', 'Monedas::index'); 
$routes->get('/admin/monedas/create', 'Monedas::create'); 
$routes->post('/admin/monedas/create', 'Monedas::create'); 
$routes->get('/admin/monedas/update/(:num)', 'Monedas::update/$1'); 
$routes->post('/admin/monedas/update', 'Monedas::update'); 
$routes->get('/admin/monedas/delete/(:num)', 'Monedas::delete/$1'); 

$routes->get('/admin/planes', 'Planes::index'); 
$routes->get('/admin/planes/create', 'Planes::create'); 
$routes->post('/admin/planes/create', 'Planes::create'); 
$routes->get('/admin/planes/update/(:num)', 'Planes::update/$1'); 
$routes->post('/admin/planes/update', 'Planes::update'); 
$routes->get('/admin/planes/delete/(:num)', 'Planes::delete/$1'); 


$routes->get('/admin/clientes', 'Usuario::list'); 
$routes->post('/admin/clientes', 'Usuario::list'); 
$routes->get('/admin/clientes/create', 'Usuario::create'); 
$routes->post('/admin/clientes/create', 'Usuario::create'); 
$routes->get('/admin/clientes/update/(:num)', 'Usuario::update/$1');
$routes->put('/admin/clientes/update', 'Usuario::update');
$routes->get('/admin/clientes/delete/(:num)', 'Usuario::delete/$1'); 
$routes->get('/admin/clientes/pagos/(:num)', 'Usuario::pagar/$1'); 
$routes->post('/admin/clientes/pagos', 'Usuario::pagar'); 
$routes->get("/admin/clientes/list-pagos/(:num)",  'Usuario::list_pagos/$1');


$routes->get('/admin/cierre-mes/(:num)', 'Cierres::info_mes_cierre/$1'); 
$routes->get('/admin/cierre-mes/(:num)/(:num)/(:num)', 'Cierres::info_mes_cierre/$1/$2/$3'); 
$routes->get('/admin/cierre-anio/(:num)', 'Cierres::info_anio_cierre/$1'); 

$routes->get('/admin/recordar-pago/(:num)', 'Usuario::email_recordar_pago/$1'); 

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
