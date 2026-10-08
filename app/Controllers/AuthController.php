<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    // Mostrar formulario de login
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/personas');
        }

        return view('auth/login');
    }

    // Comprobar las credenciales
    public function autenticar()
    {
        $usuario = trim((string) $this->request->getPost('usuario'));
        $password = (string) $this->request->getPost('password');

        $model = new UsuarioModel();

        $usuarioEncontrado = $model
            ->where('usuario', $usuario)
            ->first();

        if (
            !$usuarioEncontrado ||
            !password_verify($password, $usuarioEncontrado['password'])
            ) {
                session()->setFlashdata(
                    'error',
                    'Usuario o contraseña incorrectos.'
                );
                return redirect()->to('/login');
            }

        // Evitar reutilizar el identificador anterior de sesión
        session()->regenerate(true);

        session()->set([
            'isLoggedIn' => true,
            'userId' => $usuarioEncontrado['id'],
            'usuario' => $usuarioEncontrado['usuario']
        ]);

        return redirect()->to('/personas');
    }

    // Cerrar sesión
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}