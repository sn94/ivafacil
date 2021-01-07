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
$routes->get('/api/compras/list/(:num)/(:num)', 'Compra::index/$1/$2');

$routes->get('/api/ventas', 'Venta::index'); 
$routes->post('/api/ventas/create', 'Venta::create'); 
$routes->put('/api/ventas', 'Venta::update'); 
$routes->get('/api/ventas/(:num)', 'Venta::show/$1'); 
$routes->delete('/api/ventas/(:num)', 'Venta::delete/$1');
$routes->get('/api/ventas/list/(:num)/(:num)/(:alpha)', 'Venta::index/$1/$2/$3'); 
$routes->post('/api/ventas/list', 'Venta::index'); 


$routes->get('/api/retencion', 'Retencion::index'); 
$routes->post('/api/retencion/create', 'Retencion::create'); 
$routes->put('/api/retencion', 'Retencion::update'); 
$routes->get('/api/retencion/(:num)', 'Retencion::show/$1'); 
$routes->delete('/api/retencion/(:num)', 'Retencion::delete/$1');
$routes->get('/api/retencion/list/(:num)/(:num)', 'Retencion::index/$1/$2'); 
$routes->post('/api/retencion/list', 'Retencion::index'); 

$routes->get('/api/estados-mes/(:num)/(:num)/(:num)', 'Cierres::resumen_mes/$1/$2/$3'); 
$routes->get('/api/estados-mes/(:num)/(:num)', 'Cierres::resumen_mes_session/$1/$2'); 
$routes->get('/api/estados-anio/(:num)/(:num)', 'Cierres::resumen_anio/$1/$2'); 
$routes->get('/api/estados-anio/(:num)', 'Cierres::resumen_anio_session/$1'); 
$routes->get('/api/cierre-mes', 'Cierres::cierre_mes'); 
$routes->get('/api/cierre-anio', 'Cierres::cierre_anio'); 
$routes->get('/api/totales-mes/(:num)/(:num)',   'Cierres::totales_mes_session/$1/$2');
$routes->get('/api/totales-anio/(:num)',   'Cierres::totales_anio_session/$1');

//cierre_mes
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
//admin clientes cruds
$routes->get('/admin/clientes', 'Usuario::list_priority'); 
$routes->post('/admin/clientes', 'Usuario::list_priority'); 
$routes->get('/admin/clientes/create', 'Usuario::create'); 
$routes->post('/admin/clientes/create', 'Usuario::create'); 
$routes->get('/admin/clientes/update/(:num)', 'Usuario::update/$1');
$routes->put('/admin/clientes/update', 'Usuario::update');
$routes->get('/admin/clientes/delete/(:num)', 'Usuario::delete/$1'); 
$routes->get('/admin/clientes/movimientos/(:num)', 'Movimiento::informe_mes_/$1'); //vista de mov. en compras, ventas de un cliente
//admin clientes operaciones
$routes->get('/admin/clientes/compras/(:num)', 'Compra::index_se/$1'); 
$routes->get('/admin/clientes/compras/(:num)/(:num)/(:num)', 'Compra::index_se/$1/$2/$3'); 
$routes->post('/admin/clientes/compras', 'Compra::index_se'); 
$routes->post('/admin/clientes/compras-informes/(:alpha)', 'Compra::informes/$1'); 

$routes->get('/admin/clientes/ventas/(:num)', 'Venta::index_se/$1'); 
$routes->get('/admin/clientes/ventas/(:num)/(:num)/(:num)', 'Venta::index_se/$1/$2/$3'); 
$routes->get('/admin/clientes/ventas/(:num)/(:num)/(:num)/(:alpha)', 'Venta::index_se/$1/$2/$3/$4'); 
$routes->post('/admin/clientes/ventas', 'Venta::index_se'); 
$routes->post('/admin/clientes/ventas-informes/(:alpha)', 'Venta::informes/$1'); 

$routes->get('/admin/clientes/retencion/(:num)/(:num)/(:num)', 'Retencion::index_se/$1/$2/$3'); 
$routes->post('/admin/clientes/retencion', 'Retencion::index_se'); 
$routes->post('/admin/clientes/retencion-informes/(:alpha)', 'Retencion::informes/$1'); 

//admin clientes pagos
$routes->get('/admin/clientes/pagos/(:num)', 'Pagos::create/$1'); 
$routes->post('/admin/clientes/pagos', 'Pagos::create'); 
$routes->get("/admin/clientes/list-pagos/(:num)",  'Pagos::index/$1');
$routes->get("/admin/clientes/list-pagos/(:num)/(:num)/(:num)",  'Pagos::index/$1/$2/$3');
$routes->post("/admin/clientes/informes/(:alpha)/(:num)",  'Pagos::informes/$1/$2');
//admin clientes novedades
$routes->get('/admin/clientes/novedades', 'Usuario::novedades'); 
 //admin cierres
$routes->get('/admin/cierre-mes/(:num)', 'Cierres::info_mes_cierre/$1'); 
$routes->get('/admin/cierre-mes/(:num)/(:num)/(:num)', 'Cierres::info_mes_cierre/$1/$2/$3'); 
$routes->get('/admin/estados-mes/(:num)/(:num)/(:num)', 'Cierres::resumen_mes/$1/$2/$3'); 
$routes->get('/admin/estados-mes/(:num)', 'Cierres::resumen_mes/$1'); 
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
