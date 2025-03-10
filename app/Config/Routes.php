<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('guest/photo/(:any)', 'Guest::photo/$1');
$routes->get('check-in', 'Guest::checkIn');
$routes->post('check-in/save', 'Guest::saveCheckIn');

$routes->group('admin', static function ($routes) {
  $routes->get('dashboard', 'Dashboard::index');

  $routes->resource('user');
  $routes->post('user/store', 'User::store');
  $routes->post('user/ajaxList', 'User::ajaxList');

  $routes->resource('guest');
  $routes->post('guest/ajaxList', 'Guest::ajaxList');

  $routes->resource('employee');
  $routes->post('employee/store', 'Employee::store');
  $routes->post('employee/ajaxList', 'Employee::ajaxList');

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
