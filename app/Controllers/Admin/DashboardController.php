<?php

namespace App\Controllers\Admin;

class DashboardController extends AdminBaseController
{
    public function index()
    {
        $db = db_connect();

        $data = [
            'titulo'   => 'Panel de administración',
            'totales'  => [
                'Partes'              => $db->table('jojo_parts')->countAllResults(),
                'Personajes'          => $db->table('characters')->countAllResults(),
                'Géneros'             => $db->table('genres')->countAllResults(),
                'Anime'               => $db->table('animes')->countAllResults(),
                'Preguntas del test'  => $db->table('quiz_questions')->countAllResults(),
                'Preguntas activas'   => $db->table('quiz_questions')->where('is_active', 1)->countAllResults(),
            ],
        ];

        return view('admin/dashboard', $data);
    }
}
