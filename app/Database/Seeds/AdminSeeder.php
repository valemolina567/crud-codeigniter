<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Crea (o actualiza) el usuario administrador con la contraseña de la variable de entorno
 * ADMIN_PASSWORD (y ADMIN_USER, por defecto "admin"). La contraseña se guarda con password_hash().
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $usuario  = getenv('ADMIN_USER') ?: 'admin';
        $password = getenv('ADMIN_PASSWORD');

        if (! $password) {
            echo "ADMIN_PASSWORD no definida: no se crea el administrador.\n";

            return;
        }

        $tabla  = $this->db->table('usuarios');
        $existe = $tabla->where('usuario', $usuario)->countAllResults() > 0;
        $datos  = ['password' => password_hash($password, PASSWORD_DEFAULT), 'rol' => 'ADMIN'];

        if ($existe) {
            $this->db->table('usuarios')->where('usuario', $usuario)->update($datos);
        } else {
            $this->db->table('usuarios')->insert(['usuario' => $usuario] + $datos);
        }
    }
}
