<?php

namespace App\Models;

use CodeIgniter\Model;

class OpcionModel extends Model
{
    protected $table      = 'quiz_options';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['question_id', 'text'];
}
