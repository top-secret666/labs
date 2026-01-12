<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['path', 'post_id', 'type'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
