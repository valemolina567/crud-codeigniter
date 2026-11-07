<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabla de usuarios del login (la base del sistema de autenticación).
 * Sirve para levantar una base de datos NUEVA (por ejemplo en producción).
 * Si la tabla ya existe (tu MySQL local) no hace nada.
 */
class CreateBaseTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'usuario'  => ['type' => 'VARCHAR', 'constraint' => 50],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('usuario');
        $this->forge->createTable('usuarios', true, ['ENGINE' => 'InnoDB']);
    }

    public function down(): void
    {
        // No se borra la tabla de usuarios al hacer rollback.
    }
}
