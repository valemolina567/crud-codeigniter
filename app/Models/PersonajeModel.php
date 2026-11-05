<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonajeModel extends Model
{
    protected $table      = 'characters';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['part_id', 'name', 'stand_name', 'role', 'description'];
}
