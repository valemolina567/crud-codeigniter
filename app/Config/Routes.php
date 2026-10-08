<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Login: rutas públicas
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::autenticar');

// Cerrar sesión: requiere autenticación
$routes->get('/logout', 'AuthController::logout', ['filter' => 'auth']);

// CRUD: todas estas rutas están protegidas
$routes->get('/personas', 'PersonaController::index', ['filter' => 'auth']);

$routes->get('/personas/crear', 'PersonaController::crear', ['filter' => 'auth']);

$routes->post('/personas/guardar', 'PersonaController::guardar', ['filter' => 'auth']);

$routes->get('/personas/editar/(:num)', 'PersonaController::editar/$1', ['filter' => 'auth']);

$routes->post('/personas/actualizar/(:num)', 'PersonaController::actualizar/$1', ['filter' => 'auth']);

$routes->get('/personas/eliminar/(:num)', 'PersonaController::eliminar/$1', ['filter' => 'auth']);
