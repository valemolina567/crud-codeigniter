<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table = 'personas';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'imagen'
    ];
}