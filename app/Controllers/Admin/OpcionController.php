<?php

namespace App\Controllers\Admin;

use App\Models\OpcionModel;
use App\Models\ParteModel;
use App\Models\PreguntaModel;

class OpcionController extends AdminBaseController
{
    private function reglas(): array
    {
        return [
            'text' => ['label' => 'Texto de la opción', 'rules' => 'required|min_length[2]|max_length[255]'],
        ];
    }

    /** Lista las opciones de una pregunta y permite agregar nuevas. */
    public function index(int $preguntaId)
    {
        $pregunta = $this->buscarOFallar(new PreguntaModel(), $preguntaId);

        $opciones = db_connect()->table('quiz_options o')
            ->select('o.*, '
                . '(SELECT COUNT(*) FROM option_character_weights w WHERE w.option_id = o.id) AS pesos_personajes, '
                . '(SELECT COUNT(*) FROM option_genre_weights w2 WHERE w2.option_id = o.id) AS pesos_generos', false)
            ->where('o.question_id', $preguntaId)
            ->orderBy('o.id')
            ->get()->getResultArray();

        return view('admin/opciones/index', [
            'titulo'   => 'Opciones de la pregunta',
            'pregunta' => $pregunta,
            'opciones' => $opciones,
        ]);
    }

    public function guardar(int $preguntaId)
    {
        $this->buscarOFallar(new PreguntaModel(), $preguntaId);

        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $id = (new OpcionModel())->insert([
            'question_id' => $preguntaId,
            'text'        => trim((string) $this->request->getPost('text')),
        ], true);

        // Pasamos directo a asignar los pesos de la opción recién creada
        return redirect()->to('/admin/opciones/' . $id)
            ->with('exito', 'Opción creada. Ahora asigna sus pesos.');
    }

    /** Detalle de una opción: editar texto y gestionar sus pesos (personajes y géneros). */
    public function detalle(int $id)
    {
        $db     = db_connect();
        $opcion = $this->buscarOFallar(new OpcionModel(), $id);

        $pesosPersonajes = $db->table('option_character_weights w')
            ->select('w.character_id, w.weight, c.name, p.number AS parte_numero')
            ->join('characters c', 'c.id = w.character_id')
            ->join('jojo_parts p', 'p.id = c.part_id')
            ->where('w.option_id', $id)
            ->orderBy('p.number')->orderBy('c.name')
            ->get()->getResultArray();

        $pesosGeneros = $db->table('option_genre_weights w')
            ->select('w.genre_id, w.weight, g.name')
            ->join('genres g', 'g.id = w.genre_id')
            ->where('w.option_id', $id)
            ->orderBy('g.name')
            ->get()->getResultArray();

        return view('admin/opciones/detalle', [
            'titulo'          => 'Pesos de la opción',
            'opcion'          => $opcion,
            'pregunta'        => (new PreguntaModel())->find($opcion['question_id']),
            'partes'          => (new ParteModel())->orderBy('number')->findAll(),
            'generos'         => $db->table('genres')->orderBy('name')->get()->getResultArray(),
            'pesosPersonajes' => $pesosPersonajes,
            'pesosGeneros'    => $pesosGeneros,
        ]);
    }

    public function actualizar(int $id)
    {
        $modelo = new OpcionModel();
        $this->buscarOFallar($modelo, $id);

        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $modelo->update($id, ['text' => trim((string) $this->request->getPost('text'))]);

        return redirect()->to('/admin/opciones/' . $id)->with('exito', 'Texto de la opción actualizado.');
    }

    public function eliminar(int $id)
    {
        $modelo = new OpcionModel();
        $opcion = $this->buscarOFallar($modelo, $id);

        $modelo->delete($id); // pesos en cascada

        $mensaje = 'Opción eliminada.';
        if ((new PreguntaModel())->desactivarSiInvalida((int) $opcion['question_id'])) {
            $mensaje .= ' La pregunta quedó incompleta y se desactivó automáticamente.';
        }

        return redirect()->to('/admin/preguntas/' . $opcion['question_id'] . '/opciones')->with('exito', $mensaje);
    }
}
