<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->addRedirect('/', 'login');

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

/*
 * PANEL DE ADMINISTRACIÓN (Tarea 8)
 * Filtros: auth = sesión iniciada | admin = rol ADMIN | csrf = token en los formularios POST
 */
$routes->group('admin', [
    'namespace' => 'App\Controllers\Admin',
    'filter'    => ['auth', 'admin', 'csrf'],
], static function ($routes) {
    $routes->get('/', 'DashboardController::index');

    // Géneros
    $routes->get('generos', 'GeneroController::index');
    $routes->get('generos/nuevo', 'GeneroController::nuevo');
    $routes->post('generos', 'GeneroController::guardar');
    $routes->get('generos/(:num)/editar', 'GeneroController::editar/$1');
    $routes->post('generos/(:num)', 'GeneroController::actualizar/$1');
    $routes->post('generos/(:num)/eliminar', 'GeneroController::eliminar/$1');

    // Partes de la saga
    $routes->get('partes', 'ParteController::index');
    $routes->get('partes/nuevo', 'ParteController::nuevo');
    $routes->post('partes', 'ParteController::guardar');
    $routes->get('partes/(:num)/editar', 'ParteController::editar/$1');
    $routes->post('partes/(:num)', 'ParteController::actualizar/$1');
    $routes->post('partes/(:num)/eliminar', 'ParteController::eliminar/$1');

    // Personajes (+ endpoint JSON del dropdown dependiente Parte -> Personaje)
    $routes->get('personajes', 'PersonajeController::index');
    $routes->get('personajes/nuevo', 'PersonajeController::nuevo');
    $routes->post('personajes', 'PersonajeController::guardar');
    $routes->get('personajes/(:num)/editar', 'PersonajeController::editar/$1');
    $routes->post('personajes/(:num)', 'PersonajeController::actualizar/$1');
    $routes->post('personajes/(:num)/eliminar', 'PersonajeController::eliminar/$1');
    $routes->get('api/partes/(:num)/personajes', 'PersonajeController::porParte/$1');

    // Anime (con relación N:M a géneros)
    $routes->get('animes', 'AnimeController::index');
    $routes->get('animes/nuevo', 'AnimeController::nuevo');
    $routes->post('animes', 'AnimeController::guardar');
    $routes->get('animes/(:num)/editar', 'AnimeController::editar/$1');
    $routes->post('animes/(:num)', 'AnimeController::actualizar/$1');
    $routes->post('animes/(:num)/eliminar', 'AnimeController::eliminar/$1');

    // Preguntas del test
    $routes->get('preguntas', 'PreguntaController::index');
    $routes->get('preguntas/nuevo', 'PreguntaController::nuevo');
    $routes->post('preguntas', 'PreguntaController::guardar');
    $routes->get('preguntas/(:num)/editar', 'PreguntaController::editar/$1');
    $routes->post('preguntas/(:num)', 'PreguntaController::actualizar/$1');
    $routes->post('preguntas/(:num)/eliminar', 'PreguntaController::eliminar/$1');

    // Opciones de cada pregunta
    $routes->get('preguntas/(:num)/opciones', 'OpcionController::index/$1');
    $routes->post('preguntas/(:num)/opciones', 'OpcionController::guardar/$1');
    $routes->get('opciones/(:num)', 'OpcionController::detalle/$1');
    $routes->post('opciones/(:num)', 'OpcionController::actualizar/$1');
    $routes->post('opciones/(:num)/eliminar', 'OpcionController::eliminar/$1');

    // Pesos de cada opción
    $routes->post('opciones/(:num)/pesos-personaje', 'PesoController::guardarPersonaje/$1');
    $routes->post('opciones/(:num)/pesos-personaje/(:num)/eliminar', 'PesoController::eliminarPersonaje/$1/$2');
    $routes->post('opciones/(:num)/pesos-genero', 'PesoController::guardarGenero/$1');
    $routes->post('opciones/(:num)/pesos-genero/(:num)/eliminar', 'PesoController::eliminarGenero/$1/$2');
});
