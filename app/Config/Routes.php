<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('admin', static function ($routes) {
  $routes->resource('user');
  $routes->post('user/ajaxList', 'User::ajaxList');

  $routes->group('master', static function ($routes) {
    $routes->resource('position');
    $routes->post('position/ajaxList', 'Position::ajaxList');

    $routes->resource('identity');
    $routes->post('identity/ajaxList', 'Identity::ajaxList');
  });
});
//pengguna
// $routes->get('/user', 'User::index');
// $routes->get('/user/create', 'User::create');
// $routes->post('/user/store', 'User::store');
// $routes->get('/user/(:num)/edit', 'User::edit/$1');
// $routes->post('/user/(:num)/update', 'User::update/$1');
// $routes->get('/user/(:num)/delete', 'User::delete/$1');


//reference
// $routes->get('/reference', 'Reference::index');
// $routes->post('/reference/ajaxList', 'Reference::ajaxList');
// $routes->post('/reference/create', 'Reference::create');

service('auth')->routes($routes);
