<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfessionsModel extends Model
{
    protected $table      = 'confessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['id', 'confession_text'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $skipValidation     = false;
}
