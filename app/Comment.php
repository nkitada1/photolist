<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Comment extends Model
{
    protected $visible = [
        'author', 'content',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }
}
