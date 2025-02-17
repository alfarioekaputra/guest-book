<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('admin', static function ($routes) {
  $routes->resource('user');
  $routes->post('/user/store', 'User::store');
  $routes->post('user/ajaxList', 'User::ajaxList');

  $routes->group('master', static function ($routes) {
    $routes->resource('position');
    $routes->post('position/store', 'Position::store');
    $routes->post('position/ajaxList', 'Position::ajaxList');

    $routes->resource('identity');
    $routes->post('identity/store', 'Identity::store');
    $routes->post('identity/ajaxList', 'Identity::ajaxList');
  });
});


service('auth')->routes($routes);
