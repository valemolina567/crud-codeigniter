<?php

namespace App\Controllers\Admin;

use App\Models\OpcionModel;
use App\Models\PreguntaModel;

/**
 * Pesos (0 a 3) que una opción de respuesta aporta a un personaje o a un género.
 * Son el DATO SENSIBLE del Core: alimentan las fórmulas de compatibilidad (sección 2 del documento),
 * por eso se validan SIEMPRE en el servidor, sin confiar en lo que envíe el navegador.
 */
class PesoController extends AdminBaseController
{
    public function guardarPersonaje(int $opcionId)
    {
        $this->buscarOFallar(new OpcionModel(), $opcionId);

        $reglas = [
            'part_id'      => ['label' => 'Parte', 'rules' => 'required|is_natural_no_zero|is_not_unique[jojo_parts.id]'],
            'character_id' => ['label' => 'Personaje', 'rules' => 'required|is_natural_no_zero|is_not_unique[characters.id]'],
            'weight'       => ['label' => 'Peso', 'rules' => 'required|in_list[0,1,2,3]'],
        ];

        if (! $this->validate($reglas)) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $parteId     = (int) $this->request->getPost('part_id');
        $personajeId = (int) $this->request->getPost('character_id');
        $peso        = (int) $this->request->getPost('weight');
        $db          = db_connect();

        // El dropdown dependiente es solo comodidad visual: aquí se re-comprueba que
        // el personaje realmente pertenezca a la parte elegida.
        $personaje = $db->table('characters')->where(['id' => $personajeId, 'part_id' => $parteId])->get()->getRowArray();
        if (! $personaje) {
            return $this->volverConErrores(['El personaje seleccionado no pertenece a la parte elegida.']);
        }

        $filtro = ['option_id' => $opcionId, 'character_id' => $personajeId];
        if ($db->table('option_character_weights')->where($filtro)->countAllResults() > 0) {
            $db->table('option_character_weights')->where($filtro)->update(['weight' => $peso]);
        } else {
            $db->table('option_character_weights')->insert($filtro + ['weight' => $peso]);
        }

        return redirect()->to('/admin/opciones/' . $opcionId)->with('exito', 'Peso de personaje guardado.');
    }

    public function eliminarPersonaje(int $opcionId, int $personajeId)
    {
        $opcion = $this->buscarOFallar(new OpcionModel(), $opcionId);

        db_connect()->table('option_character_weights')
            ->where(['option_id' => $opcionId, 'character_id' => $personajeId])
            ->delete();

        return $this->despuesDeEliminar($opcion);
    }

    public function guardarGenero(int $opcionId)
    {
        $this->buscarOFallar(new OpcionModel(), $opcionId);

        $reglas = [
            'genre_id' => ['label' => 'Género', 'rules' => 'required|is_natural_no_zero|is_not_unique[genres.id]'],
            'weight'   => ['label' => 'Peso', 'rules' => 'required|in_list[0,1,2,3]'],
        ];

        if (! $this->validate($reglas)) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $db     = db_connect();
        $filtro = ['option_id' => $opcionId, 'genre_id' => (int) $this->request->getPost('genre_id')];
        $peso   = (int) $this->request->getPost('weight');

        if ($db->table('option_genre_weights')->where($filtro)->countAllResults() > 0) {
            $db->table('option_genre_weights')->where($filtro)->update(['weight' => $peso]);
        } else {
            $db->table('option_genre_weights')->insert($filtro + ['weight' => $peso]);
        }

        return redirect()->to('/admin/opciones/' . $opcionId)->with('exito', 'Peso de género guardado.');
    }

    public function eliminarGenero(int $opcionId, int $generoId)
    {
        $opcion = $this->buscarOFallar(new OpcionModel(), $opcionId);

        db_connect()->table('option_genre_weights')
            ->where(['option_id' => $opcionId, 'genre_id' => $generoId])
            ->delete();

        return $this->despuesDeEliminar($opcion);
    }

    /** Si la pregunta activa queda incompleta tras borrar un peso, se desactiva sola. */
    private function despuesDeEliminar(array $opcion)
    {
        $mensaje = 'Peso eliminado.';

        if ((new PreguntaModel())->desactivarSiInvalida((int) $opcion['question_id'])) {
            $mensaje .= ' La pregunta quedó incompleta y se desactivó automáticamente.';
        }

        return redirect()->to('/admin/opciones/' . $opcion['id'])->with('exito', $mensaje);
    }
}
