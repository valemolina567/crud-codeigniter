<?php

namespace App\Controllers\Admin;

use App\Models\ParteModel;

class ParteController extends AdminBaseController
{
    private function reglas(int $id = 0): array
    {
        $maxAnio = (int) date('Y') + 1;

        return [
            'number' => [
                'label' => 'Número de parte',
                'rules' => "required|is_natural_no_zero|less_than_equal_to[20]|is_unique[jojo_parts.number,id,{$id}]",
            ],
            'title'        => ['label' => 'Título', 'rules' => 'required|min_length[2]|max_length[100]'],
            'protagonist'  => ['label' => 'Protagonista', 'rules' => 'required|min_length[2]|max_length[100]'],
            'release_year' => [
                'label' => 'Año de publicación',
                'rules' => "required|integer|greater_than_equal_to[1980]|less_than_equal_to[{$maxAnio}]",
            ],
        ];
    }

    private function datos(): array
    {
        return [
            'number'       => (int) $this->request->getPost('number'),
            'title'        => trim((string) $this->request->getPost('title')),
            'protagonist'  => trim((string) $this->request->getPost('protagonist')),
            'release_year' => (int) $this->request->getPost('release_year'),
        ];
    }

    public function index()
    {
        $partes = db_connect()->table('jojo_parts p')
            ->select('p.*, COUNT(c.id) AS total_personajes')
            ->join('characters c', 'c.part_id = p.id', 'left')
            ->groupBy('p.id')
            ->orderBy('p.number')
            ->get()->getResultArray();

        return view('admin/partes/index', ['titulo' => 'Partes de la saga', 'partes' => $partes]);
    }

    public function nuevo()
    {
        return view('admin/partes/form', ['titulo' => 'Nueva parte', 'parte' => null]);
    }

    public function guardar()
    {
        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        (new ParteModel())->insert($this->datos());

        return redirect()->to('/admin/partes')->with('exito', 'Parte creada correctamente.');
    }

    public function editar(int $id)
    {
        $parte = $this->buscarOFallar(new ParteModel(), $id);

        return view('admin/partes/form', ['titulo' => 'Editar parte', 'parte' => $parte]);
    }

    public function actualizar(int $id)
    {
        $modelo = new ParteModel();
        $this->buscarOFallar($modelo, $id);

        if (! $this->validate($this->reglas($id))) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $modelo->update($id, $this->datos());

        return redirect()->to('/admin/partes')->with('exito', 'Parte actualizada.');
    }

    public function eliminar(int $id)
    {
        $modelo = new ParteModel();
        $this->buscarOFallar($modelo, $id);

        $personajes = db_connect()->table('characters')->where('part_id', $id)->countAllResults();
        if ($personajes > 0) {
            return redirect()->to('/admin/partes')
                ->with('error', "No se puede eliminar: la parte tiene {$personajes} personaje(s) asociados.");
        }

        $modelo->delete($id);

        return redirect()->to('/admin/partes')->with('exito', 'Parte eliminada.');
    }
}
