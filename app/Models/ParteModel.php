<?php

namespace App\Models;

use CodeIgniter\Model;

class ParteModel extends Model
{
    protected $table      = 'jojo_parts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['number', 'title', 'protagonist', 'release_year'];
}
