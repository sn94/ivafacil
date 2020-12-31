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
$routes->resource('usuario', ['controller'=>  'usuario']);
*/

// We get a performance increase by specifying the default
// route since we don't have to scan directories.


$routes->get('/home', 'Welcome::publico'); //pagina publica

$routes->get('/', 'Welcome::index'); //pagina principal del cliente




//api 
$routes->get('/api/ciudades', 'Auxiliar::ciudades'); 
$routes->get('/api/planes', 'Auxiliar::planes'); 
$routes->get('/api/monedas', 'Auxiliar::monedas'); 

$routes->get('/api/compras', 'Compra::index'); 
$routes->post('/api/compras/create', 'Compra::create'); 
$routes->put('/api/compras', 'Compra::update'); 
$routes->get('/api/compras/(:num)', 'Compra::show/$1'); 
$routes->delete('/api/compras/(:num)', 'Compra::delete/$1');
$routes->post('/api/compras/list', 'Compra::index'); 

$routes->get('/api/ventas', 'Venta::index'); 
$routes->post('/api/ventas/create', 'Venta::create'); 
$routes->put('/api/ventas', 'Venta::update'); 
$routes->get('/api/ventas/(:num)', 'Venta::show/$1'); 
$routes->delete('/api/ventas/(:num)', 'Venta::delete/$1');
$routes->post('/api/ventas/list', 'Venta::index'); 

$routes->get('/api/retencion', 'Retencion::index'); 
$routes->post('/api/retencion/create', 'Retencion::create'); 
$routes->put('/api/retencion', 'Retencion::update'); 
$routes->get('/api/retencion/(:num)', 'Retencion::show/$1'); 
$routes->delete('/api/retencion/(:num)', 'Retencion::delete/$1');
$routes->post('/api/retencion/list', 'Retencion::index'); 


$routes->get('/api/usuario', 'Usuario::index'); 
$routes->post('/api/usuario/create', 'Usuario::create'); 
$routes->put('/api/usuario', 'Usuario::update'); 
$routes->delete('/api/usuario/(:num)', 'Usuario::delete/$1'); 
$routes->get('/api/usuario/(:num)', 'Usuario::show/$1'); 
$routes->post('/api/usuario/sign-in', 'Usuario::sign_in'); 
$routes->post('/api/email-usuario-registered', 'Usuario::email_bienvenida'); 
$routes->get('/api/usuario/ruc/(:num)', 'Usuario::ruc/$1'); 


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
