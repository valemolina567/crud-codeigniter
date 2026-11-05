<?php

namespace App\Controllers\Admin;

use App\Models\GeneroModel;

class GeneroController extends AdminBaseController
{
    private function reglas(int $id = 0): array
    {
        return [
            'name' => [
                'label' => 'Nombre',
                // is_unique con "ignorar este id" para poder editar sin chocar consigo mismo
                'rules' => "required|min_length[2]|max_length[30]|is_unique[genres.name,id,{$id}]",
            ],
            'description' => ['label' => 'Descripción', 'rules' => 'permit_empty|max_length[500]'],
        ];
    }

    public function index()
    {
        $generos = db_connect()->table('genres g')
            ->select('g.*, COUNT(ag.anime_id) AS total_anime')
            ->join('anime_genres ag', 'ag.genre_id = g.id', 'left')
            ->groupBy('g.id')
            ->orderBy('g.name')
            ->get()->getResultArray();

        return view('admin/generos/index', ['titulo' => 'Géneros', 'generos' => $generos]);
    }

    public function nuevo()
    {
        return view('admin/generos/form', ['titulo' => 'Nuevo género', 'genero' => null]);
    }

    public function guardar()
    {
        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        (new GeneroModel())->insert([
            'name'        => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        return redirect()->to('/admin/generos')->with('exito', 'Género creado correctamente.');
    }

    public function editar(int $id)
    {
        $genero = $this->buscarOFallar(new GeneroModel(), $id);

        return view('admin/generos/form', ['titulo' => 'Editar género', 'genero' => $genero]);
    }

    public function actualizar(int $id)
    {
        $modelo = new GeneroModel();
        $this->buscarOFallar($modelo, $id);

        if (! $this->validate($this->reglas($id))) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $modelo->update($id, [
            'name'        => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')),
        ]);

        return redirect()->to('/admin/generos')->with('exito', 'Género actualizado.');
    }

    public function eliminar(int $id)
    {
        $modelo = new GeneroModel();
        $this->buscarOFallar($modelo, $id);

        $enUso = db_connect()->table('anime_genres')->where('genre_id', $id)->countAllResults();
        if ($enUso > 0) {
            return redirect()->to('/admin/generos')
                ->with('error', "No se puede eliminar: {$enUso} anime usan este género.");
        }

        $modelo->delete($id);

        return redirect()->to('/admin/generos')->with('exito', 'Género eliminado.');
    }
}
