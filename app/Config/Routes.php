<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//pengguna
$routes->get('/users', 'UsersController::index');
$routes->get('/user/new', 'UsersController::new');
$routes->post('/user/store', 'UsersController::store');
$routes->get('/user/(:num)/edit', 'UsersController::edit/$1');
$routes->post('/user/(:num)/update', 'UsersController::update/$1');
$routes->get('/user/(:num)/delete', 'UsersController::delete/$1');
$routes->post('/users/ajaxList', 'UsersController::ajaxList');

service('auth')->routes($routes);
