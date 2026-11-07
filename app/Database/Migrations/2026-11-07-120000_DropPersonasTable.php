<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Elimina la tabla "personas" del ejercicio inicial (CRUD de personas),
 * que ya no forma parte del proyecto.
 */
class DropPersonasTable extends Migration
{
    public function up(): void
    {
        $this->forge->dropTable('personas', true);
    }

    public function down(): void
    {
        // No se recrea: el CRUD de personas fue retirado definitivamente.
    }
}
