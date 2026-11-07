<?php

namespace App\Controllers;

/**
 * Página de inicio para usuarios con rol USER.
 * (Aquí irá más adelante el test de compatibilidad, las recomendaciones y los usuarios afines.)
 */
class BienvenidaController extends BaseController
{
    public function index()
    {
        return view('bienvenida', [
            'titulo'  => 'Bienvenido · JoJo Fandom Hub',
            'usuario' => session()->get('usuario'),
        ]);
    }
}
