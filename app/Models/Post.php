<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //

    // Relacionamento Post e Categorias
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageAttribute($value)
    {        
        return "<img src='{$value}' class='img-fluid img-thumbnail' width='130'>";
    }
}
