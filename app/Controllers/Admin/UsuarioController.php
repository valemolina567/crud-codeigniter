<?php

namespace App\Controllers\Admin;

use App\Models\UsuarioModel;

/**
 * Gestión de usuarios del sistema (solo ADMIN): listar, crear, cambiar rol / contraseña y eliminar.
 */
class UsuarioController extends AdminBaseController
{
    private const ROLES = ['ADMIN', 'USER'];

    // ---------- Validaciones ----------

    private function reglas(bool $esCreacion, int $id = 0): array
    {
        $reglas = [
            'rol'              => ['label' => 'Rol', 'rules' => 'required|in_list[' . implode(',', self::ROLES) . ']'],
            'password'         => ['label' => 'Contraseña', 'rules' => ($esCreacion ? 'required' : 'permit_empty') . '|min_length[8]|max_length[72]'],
            'password_confirm' => ['label' => 'Confirmación de contraseña', 'rules' => ($esCreacion ? 'required' : 'permit_empty') . '|matches[password]'],
        ];

        if ($esCreacion) {
            // El nombre de usuario solo se define al crear (no se edita después)
            $reglas['usuario'] = [
                'label' => 'Usuario',
                'rules' => "required|min_length[3]|max_length[50]|alpha_dash|is_unique[usuarios.usuario,id,{$id}]",
            ];
        }

        return $reglas;
    }

    /** DATO SENSIBLE: complejidad de la contraseña, validada en el servidor. */
    private function errorPassword(string $password): ?string
    {
        if ($password === '') {
            return null;
        }

        if (! preg_match('/[a-z]/', $password) || ! preg_match('/[A-Z]/', $password) || ! preg_match('/\d/', $password)) {
            return 'La contraseña debe tener al menos una mayúscula, una minúscula y un número.';
        }

        return null;
    }

    private function totalAdmins(): int
    {
        return (new UsuarioModel())->where('rol', 'ADMIN')->countAllResults();
    }

    private function esUsuarioActual(array $usuario): bool
    {
        return $usuario['usuario'] === session()->get('usuario');
    }

    // ---------- Acciones ----------

    public function index()
    {
        $usuarios = (new UsuarioModel())->orderBy('rol')->orderBy('usuario')->findAll();

        return view('admin/usuarios/index', [
            'titulo'   => 'Usuarios',
            'usuarios' => $usuarios,
            'actual'   => session()->get('usuario'),
        ]);
    }

    public function nuevo()
    {
        return view('admin/usuarios/form', ['titulo' => 'Nuevo usuario', 'usuario' => null, 'roles' => self::ROLES]);
    }

    public function guardar()
    {
        $errores = [];

        if (! $this->validate($this->reglas(true))) {
            $errores = $this->validator->getErrors();
        }

        $password = (string) $this->request->getPost('password');
        if (($mensaje = $this->errorPassword($password)) && ! isset($errores['password'])) {
            $errores['password'] = $mensaje;
        }

        if ($errores !== []) {
            return $this->volverConErrores($errores);
        }

        (new UsuarioModel())->insert([
            'usuario'  => trim((string) $this->request->getPost('usuario')),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol'      => (string) $this->request->getPost('rol'),
        ]);

        return redirect()->to('/admin/usuarios')->with('exito', 'Usuario creado correctamente.');
    }

    public function editar(int $id)
    {
        $usuario = $this->buscarOFallar(new UsuarioModel(), $id);

        return view('admin/usuarios/form', [
            'titulo'  => 'Editar usuario',
            'usuario' => $usuario,
            'roles'   => self::ROLES,
            'esYo'    => $this->esUsuarioActual($usuario),
        ]);
    }

    public function actualizar(int $id)
    {
        $modelo  = new UsuarioModel();
        $usuario = $this->buscarOFallar($modelo, $id);
        $errores = [];

        if (! $this->validate($this->reglas(false))) {
            $errores = $this->validator->getErrors();
        }

        $nuevoRol = (string) $this->request->getPost('rol');
        $password = (string) $this->request->getPost('password');

        if (($mensaje = $this->errorPassword($password)) && ! isset($errores['password'])) {
            $errores['password'] = $mensaje;
        }

        // Reglas de negocio: el sistema nunca puede quedarse sin administradores
        if ($usuario['rol'] === 'ADMIN' && $nuevoRol !== 'ADMIN') {
            if ($this->esUsuarioActual($usuario)) {
                $errores['rol'] = 'No puedes quitarte a ti mismo el rol de administrador.';
            } elseif ($this->totalAdmins() <= 1) {
                $errores['rol'] = 'Debe existir al menos un administrador.';
            }
        }

        if ($errores !== []) {
            return $this->volverConErrores($errores);
        }

        $datos = ['rol' => $nuevoRol];
        if ($password !== '') {
            $datos['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $modelo->update($id, $datos);

        return redirect()->to('/admin/usuarios')->with('exito', 'Usuario actualizado.');
    }

    public function eliminar(int $id)
    {
        $modelo  = new UsuarioModel();
        $usuario = $this->buscarOFallar($modelo, $id);

        if ($this->esUsuarioActual($usuario)) {
            return redirect()->to('/admin/usuarios')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        if ($usuario['rol'] === 'ADMIN' && $this->totalAdmins() <= 1) {
            return redirect()->to('/admin/usuarios')->with('error', 'No se puede eliminar al único administrador.');
        }

        $modelo->delete($id);

        return redirect()->to('/admin/usuarios')->with('exito', 'Usuario eliminado.');
    }
}
