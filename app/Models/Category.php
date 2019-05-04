<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    // Relacionamento Categorias e Posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
