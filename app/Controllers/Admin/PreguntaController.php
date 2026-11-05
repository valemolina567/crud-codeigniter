<?php

namespace App\Controllers\Admin;

use App\Models\PreguntaModel;

class PreguntaController extends AdminBaseController
{
    private function reglas(): array
    {
        return [
            'text'      => ['label' => 'Pregunta', 'rules' => 'required|min_length[5]|max_length[255]'],
            'position'  => ['label' => 'Posición', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[100]'],
            'is_active' => ['label' => 'Activa', 'rules' => 'permit_empty|in_list[1]'],
        ];
    }

    public function index()
    {
        $preguntas = db_connect()->table('quiz_questions q')
            ->select('q.*, COUNT(o.id) AS total_opciones')
            ->join('quiz_options o', 'o.question_id = q.id', 'left')
            ->groupBy('q.id')
            ->orderBy('q.position')
            ->get()->getResultArray();

        return view('admin/preguntas/index', ['titulo' => 'Preguntas del test', 'preguntas' => $preguntas]);
    }

    public function nuevo()
    {
        return view('admin/preguntas/form', ['titulo' => 'Nueva pregunta', 'pregunta' => null]);
    }

    public function guardar()
    {
        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        // Una pregunta nueva no tiene opciones todavía, por eso siempre nace INACTIVA.
        $id = (new PreguntaModel())->insert([
            'text'      => trim((string) $this->request->getPost('text')),
            'position'  => (int) $this->request->getPost('position'),
            'is_active' => 0,
        ], true);

        return redirect()->to('/admin/preguntas/' . $id . '/opciones')
            ->with('exito', 'Pregunta creada. Agrega sus opciones y pesos para poder activarla.');
    }

    public function editar(int $id)
    {
        $pregunta = $this->buscarOFallar(new PreguntaModel(), $id);

        return view('admin/preguntas/form', ['titulo' => 'Editar pregunta', 'pregunta' => $pregunta]);
    }

    public function actualizar(int $id)
    {
        $modelo = new PreguntaModel();
        $this->buscarOFallar($modelo, $id);

        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $activa = (int) $this->request->getPost('is_active') === 1;

        // VALIDACIÓN DE NEGOCIO EN BACK-END: no se puede activar una pregunta incompleta,
        // porque el test calcularía mal la compatibilidad.
        if ($activa) {
            $problemas = $modelo->erroresActivacion($id);
            if ($problemas !== []) {
                return $this->volverConErrores($problemas);
            }
        }

        $modelo->update($id, [
            'text'      => trim((string) $this->request->getPost('text')),
            'position'  => (int) $this->request->getPost('position'),
            'is_active' => $activa ? 1 : 0,
        ]);

        return redirect()->to('/admin/preguntas')->with('exito', 'Pregunta actualizada.');
    }

    public function eliminar(int $id)
    {
        $modelo = new PreguntaModel();
        $this->buscarOFallar($modelo, $id);

        $modelo->delete($id); // opciones y pesos se borran en cascada

        return redirect()->to('/admin/preguntas')->with('exito', 'Pregunta eliminada.');
    }
}
