<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentsModel extends Model
{
    protected $table      = 'comments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['username', 'comment_text', 'confess_id', 'reply_to'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $skipValidation     = false;
}
