<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//pengguna
$routes->get('/user', 'User::index');
$routes->get('/user/create', 'User::create');
$routes->post('/user/store', 'User::store');
$routes->get('/user/(:num)/edit', 'User::edit/$1');
$routes->post('/user/(:num)/update', 'User::update/$1');
$routes->get('/user/(:num)/delete', 'User::delete/$1');
$routes->post('/user/ajaxList', 'User::ajaxList');

service('auth')->routes($routes);
