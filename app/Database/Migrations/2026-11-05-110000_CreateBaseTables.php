<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tablas del CRUD original (personas y usuarios).
 * Sirve para levantar una base de datos NUEVA (por ejemplo en producción).
 * Si las tablas ya existen (tu MySQL local) no hace nada.
 */
class CreateBaseTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'auto_increment' => true],
            'nombre'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'apellido'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'fecha_nacimiento' => ['type' => 'DATE'],
            'imagen'           => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('personas', true, ['ENGINE' => 'InnoDB']);

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
        // No se borran las tablas del CRUD original al hacer rollback.
    }
}
