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
$routes->setDefaultMethod('index');
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
$routes->get('/', 'Welcome::index'); 
 

//api 
$routes->get('/api/purchase', 'Compra::index'); 
$routes->post('/api/purchase/create', 'Compra::create'); 
$routes->put('/api/purchase/(:num)', 'Compra::update/$1'); 
$routes->get('/api/purchase/(:num)', 'Compra::show/$1'); 
$routes->delete('/api/purchase/(:num)', 'Compra::delete/$1');

$routes->get('/api/sales/index', 'Venta::index'); 
$routes->post('/api/sales/create', 'Venta::create'); 

$routes->get('/api/retencion/index', 'Retencion::index'); 
$routes->post('/api/retencion/create', 'Retencion::create'); 

$routes->get('/api/user', 'Usuario::index'); 
$routes->post('/api/user/create', 'Usuario::create'); 
$routes->put('/api/user/(:num)', 'Usuario::update/$1'); 
$routes->delete('/api/user/(:num)', 'Usuario::delete/$1'); 
$routes->get('/api/user/(:num)', 'Usuario::show/$1'); 
$routes->post('/api/user/sign-in', 'Usuario::sign_in'); 

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