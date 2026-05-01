@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-pencil-square me-2"></i>Editar Tarea
                </div>

                <div class="card-body">
                    <form action="{{ route('tasks.update', $tarea->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Importante: Laravel necesita esto para saber que es una actualización --}}

                        <div class="mb-3">
                            <label for="title" class="form-label">Título de la tarea</label>
                            <input type="text" 
                                   name="title" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   value="{{ old('title', $tarea->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción (opcional)</label>
                            <textarea name="description" 
                                      class="form-control" 
                                      id="description" 
                                      rows="3">{{ old('description', $tarea->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Categoría / Prioridad</label>
                            <select name="category_id" class="form-select" id="category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ (old('category_id', $tarea->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Tarea</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection