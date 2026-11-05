<?php

namespace App\Models;

use CodeIgniter\Model;

class PreguntaModel extends Model
{
    protected $table      = 'quiz_questions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['text', 'position', 'is_active'];

    /**
     * Regla de negocio: una pregunta solo puede estar ACTIVA si el test funciona con ella:
     *  - tiene al menos 2 opciones, y
     *  - cada opción tiene al menos un peso hacia un personaje.
     * Devuelve la lista de problemas (vacía = puede activarse).
     */
    public function erroresActivacion(int $preguntaId): array
    {
        $db       = db_connect();
        $opciones = $db->table('quiz_options')->where('question_id', $preguntaId)->get()->getResultArray();
        $errores  = [];

        if (count($opciones) < 2) {
            $errores[] = 'Para activar la pregunta necesita al menos 2 opciones.';
        }

        foreach ($opciones as $opcion) {
            $pesos = $db->table('option_character_weights')
                ->where('option_id', $opcion['id'])
                ->countAllResults();

            if ($pesos === 0) {
                $errores[] = 'La opción «' . $opcion['text'] . '» no tiene pesos de personajes asignados.';
            }
        }

        return $errores;
    }

    /**
     * Si tras borrar una opción o un peso la pregunta activa deja de ser válida,
     * se desactiva automáticamente. Devuelve true si se desactivó.
     */
    public function desactivarSiInvalida(int $preguntaId): bool
    {
        $pregunta = $this->find($preguntaId);

        if ($pregunta && (int) $pregunta['is_active'] === 1 && $this->erroresActivacion($preguntaId) !== []) {
            $this->update($preguntaId, ['is_active' => 0]);

            return true;
        }

        return false;
    }
}
