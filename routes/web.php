<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;



// Rutas de invitado el middlewre guest solo permite que los usuarios con id de invitado puedan acceder a estas rutas
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Rutas de Registro
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
});

// 2. Rutas Protegidas: Solo para usuarios logueados
Route::middleware('auth')->group(function () {
    //Ruta para ir a dashboard
  Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
//Ruta para cerrar sesion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Rutas para Crear Tareas
    Route::get('/tareas/crear', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tareas', [TaskController::class, 'store'])->name('tasks.store');
//Esta ruta sirve solo para actualizar el status de la tarea el {tarea} nos sirve para poder enviar un parametro a travez de la ruta
    Route::patch('/tareas/{tarea}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Ruta para mostrar el formulario de edición
Route::get('/tareas/{tarea}/editar', [TaskController::class, 'edit'])->name('tasks.edit');

// Ruta para procesar la actualización (usa PUT o PATCH)
Route::put('/tareas/{tarea}', [TaskController::class, 'update'])->name('tasks.update');

Route::delete('/tareas/{tarea}', [TaskController::class, 'destroy'])->name('tasks.destroy');

Route::get('/tareas/{tarea}', [TaskController::class, 'show'])->name('tasks.show');

// Perfil
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');
    
    // Seguridad
    Route::get('/perfil/seguridad', [ProfileController::class, 'security'])->name('profile.security');
    Route::put('/perfil/seguridad/cambiar', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

});