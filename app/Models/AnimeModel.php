<?php

namespace App\Models;

use CodeIgniter\Model;

class AnimeModel extends Model
{
    protected $table      = 'animes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['title', 'synopsis', 'episodes', 'release_year', 'rating', 'cover_url'];
}
