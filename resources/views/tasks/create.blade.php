@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Crear Nueva Tarea</h4>
                </div>
                <div class="card-body">
<!--Aqui tenemos el formulario para crear una tarea el cual al darle click al boton Guardar tarea que es de tipo submit mandara llamar a la ruta task.store que invoca al metodo store el cual es el encargado de crear una tarea que los datos que pusimos en los inputs-->
                    <form action="{{ route('tasks.store') }}" method="POST">
<!--Utilizamos el csfr para la seguridad donde creamos un token unico para el formulario esto para la seguridad-->
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Título de la tarea</label>
                            <input type="text" name="title" class="form-control" placeholder="Ej. Estudiar Laravel" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción (Opcional)</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prioridad / Categoría</label>
                            <select name="category_id" class="form-select" required>
                                <option value="" selected disabled>Selecciona una opción</option>
                                {{-- Aquí recorremos las 4 categorías que mandamos del controlador gracias al foreach --}}
                                @foreach($categories as $cat)
<!--Lo que tendra cada iteracion del bucle sera un option con el value sera cat->id el cual servira para pasarle el id de la categoria y saber a que categoria estara vinculada la tarea, posteriormente en el texto que visualizara el usuario sera el nombre de esa categoria y lo hacemos gracias a la propiedad cat-name es decir el nombre de la categoria-->
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Guardar Tarea</button>
<!--Agregamos tambien un enlace que apunta a la ruta dashboard para volver a las  listas de tareas-->
                            <a href="{{ route('dashboard') }}" class="btn btn-light">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection