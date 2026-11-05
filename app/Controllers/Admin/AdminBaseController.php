<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Model;

/**
 * Controlador base del panel de administración: utilidades compartidas.
 */
abstract class AdminBaseController extends BaseController
{
    /** Vuelve al formulario conservando lo escrito (old()) y mostrando los errores. */
    protected function volverConErrores(array $errores): RedirectResponse
    {
        return redirect()->back()->withInput()->with('errores', $errores);
    }

    /** Busca un registro o responde 404 si no existe. */
    protected function buscarOFallar(Model $modelo, $id): array
    {
        $registro = $modelo->find($id);

        if (! $registro) {
            throw PageNotFoundException::forPageNotFound('El registro no existe.');
        }

        return $registro;
    }
}
