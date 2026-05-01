<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name'];

    // RELACIÓN: Una categoría puede tener muchas tareas
    public function tareas()
    {
        return $this->hasMany(Tareas::class, 'category_id');
    }
}
