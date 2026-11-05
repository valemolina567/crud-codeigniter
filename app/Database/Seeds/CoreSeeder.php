<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Datos iniciales para poder probar el panel: partes de la saga, géneros y algunos personajes.
 * Ejecutar:  php spark db:seed CoreSeeder
 */
class CoreSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->db->table('jojo_parts')->countAllResults() === 0) {
            $this->db->table('jojo_parts')->insertBatch([
                ['number' => 1, 'title' => 'Phantom Blood',        'protagonist' => 'Jonathan Joestar',   'release_year' => 1987],
                ['number' => 2, 'title' => 'Battle Tendency',      'protagonist' => 'Joseph Joestar',     'release_year' => 1989],
                ['number' => 3, 'title' => 'Stardust Crusaders',   'protagonist' => 'Jotaro Kujo',        'release_year' => 1989],
                ['number' => 4, 'title' => 'Diamond Is Unbreakable', 'protagonist' => 'Josuke Higashikata', 'release_year' => 1992],
                ['number' => 5, 'title' => 'Golden Wind',          'protagonist' => 'Giorno Giovanna',    'release_year' => 1995],
                ['number' => 6, 'title' => 'Stone Ocean',          'protagonist' => 'Jolyne Cujoh',       'release_year' => 2000],
                ['number' => 7, 'title' => 'Steel Ball Run',       'protagonist' => 'Johnny Joestar',     'release_year' => 2004],
                ['number' => 8, 'title' => 'JoJolion',             'protagonist' => 'Josuke Higashikata', 'release_year' => 2011],
            ]);
        }

        if ($this->db->table('genres')->countAllResults() === 0) {
            $this->db->table('genres')->insertBatch([
                ['name' => 'Acción',       'description' => 'Combates y escenas de alta intensidad.'],
                ['name' => 'Aventura',     'description' => 'Viajes y exploración.'],
                ['name' => 'Comedia',      'description' => 'Humor y situaciones cómicas.'],
                ['name' => 'Misterio',     'description' => 'Enigmas e investigación.'],
                ['name' => 'Drama',        'description' => 'Conflictos emocionales.'],
                ['name' => 'Sobrenatural', 'description' => 'Poderes y fenómenos fuera de lo natural.'],
                ['name' => 'Terror',       'description' => 'Suspenso y horror.'],
            ]);
        }

        if ($this->db->table('characters')->countAllResults() === 0) {
            $parte = static fn ($db, int $n) => $db->table('jojo_parts')->where('number', $n)->get()->getRow('id');

            $this->db->table('characters')->insertBatch([
                ['part_id' => $parte($this->db, 1), 'name' => 'Jonathan Joestar',   'stand_name' => null,            'role' => 'Protagonista', 'description' => 'Caballero inglés de gran fuerza y nobleza.'],
                ['part_id' => $parte($this->db, 3), 'name' => 'Jotaro Kujo',        'stand_name' => 'Star Platinum', 'role' => 'Protagonista', 'description' => 'Estudiante serio y poderoso, de pocas palabras.'],
                ['part_id' => $parte($this->db, 3), 'name' => 'Dio Brando',         'stand_name' => 'The World',     'role' => 'Antagonista',  'description' => 'Vampiro ambicioso y carismático.'],
                ['part_id' => $parte($this->db, 4), 'name' => 'Josuke Higashikata', 'stand_name' => 'Crazy Diamond', 'role' => 'Protagonista', 'description' => 'Joven leal que protege su ciudad.'],
                ['part_id' => $parte($this->db, 5), 'name' => 'Giorno Giovanna',    'stand_name' => 'Gold Experience', 'role' => 'Protagonista', 'description' => 'Aspirante a gángster con un gran sueño.'],
                ['part_id' => $parte($this->db, 6), 'name' => 'Jolyne Cujoh',       'stand_name' => 'Stone Free',    'role' => 'Protagonista', 'description' => 'Determinada y valiente, lucha por su familia.'],
            ]);
        }
    }
}
