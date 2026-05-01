<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tareas extends Model
{
    // MUY IMPORTANTE: Como tu tabla se llama 'tareas', hay que decírselo
    protected $table = 'tareas';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'title', 
        'description', 
        'user_id', 
        'category_id', 
        'status'
    ];

    // Una tarea pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
//Muchas tareas pueden pertenecer a una sola categoria
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
