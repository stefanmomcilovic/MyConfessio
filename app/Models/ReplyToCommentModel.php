<?php

namespace App\Models;

use CodeIgniter\Model;

class ReplyToCommentModel extends Model
{
    protected $table      = 'reply_to_comment';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['confess_id', 'username', 'comment_text', 'reply_to_comment_id'];
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $skipValidation     = false;
}
