<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function posts()
    {
        // https://laravel.com/docs/5.7/eloquent-relationships#defining-relationships
        return $this->hasMany(Post::class, 'category_id', 'id');
    }
}
