<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Autorización por rol: solo deja pasar a usuarios con rol ADMIN.
 * Se ejecuta DESPUÉS del filtro "auth" (que comprueba que haya sesión).
 */
class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('rol') !== 'ADMIN') {
            return service('response')
                ->setStatusCode(403)
                ->setBody('<h1>403 - Acceso denegado</h1><p>Esta sección es solo para administradores.</p><p><a href="/personas">Volver</a></p>');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nada que hacer después de la petición.
    }
}
