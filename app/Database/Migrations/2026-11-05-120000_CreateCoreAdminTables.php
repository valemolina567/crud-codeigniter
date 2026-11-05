<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tablas que alimentan el Core "Perfil de afinidad JoJo".
 * Nombres y columnas siguen el Diagrama Entidad-Relación aprobado.
 * (Única diferencia: animes.rating es DECIMAL(4,2) para poder guardar 10.00.)
 */
class CreateCoreAdminTables extends Migration
{
    private array $motor = ['ENGINE' => 'InnoDB'];

    private function id(): array
    {
        return ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true];
    }

    private function fk(): array
    {
        return ['type' => 'INT', 'constraint' => 11, 'unsigned' => true];
    }

    public function up(): void
    {
        // 0. Rol del usuario (ADMIN / USER) sobre la tabla "usuarios" del login existente
        if (! $this->db->fieldExists('rol', 'usuarios')) {
            $this->forge->addColumn('usuarios', [
                'rol' => ['type' => 'ENUM', 'constraint' => ['ADMIN', 'USER'], 'default' => 'USER', 'null' => false],
            ]);
        }
        // El usuario "admin" creado en el README pasa a ser administrador
        $this->db->table('usuarios')->where('usuario', 'admin')->update(['rol' => 'ADMIN']);

        // 1. jojo_parts
        $this->forge->addField([
            'id'          => $this->id(),
            'number'      => ['type' => 'TINYINT', 'constraint' => 3, 'unsigned' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'protagonist' => ['type' => 'VARCHAR', 'constraint' => 100],
            'release_year' => ['type' => 'YEAR'],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('number');
        $this->forge->createTable('jojo_parts', true, $this->motor);

        // 2. characters (cada personaje pertenece a una parte)
        $this->forge->addField([
            'id'          => $this->id(),
            'part_id'     => $this->fk(),
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'stand_name'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'role'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'description' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('part_id', 'jojo_parts', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('characters', true, $this->motor);

        // 3. character_images
        $this->forge->addField([
            'id'           => $this->id(),
            'character_id' => $this->fk(),
            'url'          => ['type' => 'VARCHAR', 'constraint' => 255],
            'caption'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('character_id', 'characters', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('character_images', true, $this->motor);

        // 4. genres
        $this->forge->addField([
            'id'          => $this->id(),
            'name'        => ['type' => 'VARCHAR', 'constraint' => 30],
            'description' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('genres', true, $this->motor);

        // 5. animes
        $this->forge->addField([
            'id'           => $this->id(),
            'title'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'synopsis'     => ['type' => 'TEXT', 'null' => true],
            'episodes'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'release_year' => ['type' => 'YEAR'],
            'rating'       => ['type' => 'DECIMAL', 'constraint' => '4,2', 'default' => 0],
            'cover_url'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('animes', true, $this->motor);

        // 6. anime_genres (relación N:M)
        $this->forge->addField([
            'anime_id' => $this->fk(),
            'genre_id' => $this->fk(),
        ]);
        $this->forge->addPrimaryKey(['anime_id', 'genre_id']);
        $this->forge->addForeignKey('anime_id', 'animes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('genre_id', 'genres', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('anime_genres', true, $this->motor);

        // 7. quiz_questions
        $this->forge->addField([
            'id'        => $this->id(),
            'text'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'position'  => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('quiz_questions', true, $this->motor);

        // 8. quiz_options
        $this->forge->addField([
            'id'          => $this->id(),
            'question_id' => $this->fk(),
            'text'        => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('question_id', 'quiz_questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('quiz_options', true, $this->motor);

        // 9. option_character_weights (peso 0-3 de una opción hacia un personaje)
        $this->forge->addField([
            'option_id'    => $this->fk(),
            'character_id' => $this->fk(),
            'weight'       => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey(['option_id', 'character_id']);
        $this->forge->addForeignKey('option_id', 'quiz_options', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('character_id', 'characters', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('option_character_weights', true, $this->motor);

        // 10. option_genre_weights (peso 0-3 de una opción hacia un género)
        $this->forge->addField([
            'option_id' => $this->fk(),
            'genre_id'  => $this->fk(),
            'weight'    => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
        ]);
        $this->forge->addPrimaryKey(['option_id', 'genre_id']);
        $this->forge->addForeignKey('option_id', 'quiz_options', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('genre_id', 'genres', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('option_genre_weights', true, $this->motor);
    }

    public function down(): void
    {
        foreach ([
            'option_genre_weights', 'option_character_weights', 'quiz_options', 'quiz_questions',
            'anime_genres', 'animes', 'genres', 'character_images', 'characters', 'jojo_parts',
        ] as $tabla) {
            $this->forge->dropTable($tabla, true);
        }

        if ($this->db->fieldExists('rol', 'usuarios')) {
            $this->forge->dropColumn('usuarios', 'rol');
        }
    }
}
