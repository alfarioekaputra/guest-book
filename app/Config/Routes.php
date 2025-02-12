<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/users', 'UsersController::index');
$routes->post('/users/ajaxList', 'UsersController::ajaxList');

service('auth')->routes($routes);
