@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Navegación --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active">Detalle de Tarea</li>
                </ol>
            </nav>

            <div class="card shadow-sm border-0">
                {{-- Lógica para el color de la categoría --}}
                @php
                    $colorCategoria = match($tarea->category->name) {
                        'Urgente'     => 'danger',
                        'Prioritario' => 'warning',
                        'Normal'      => 'success',
                        'Baja'        => 'secondary',
                        default       => 'info'
                    };
                @endphp

                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        {{-- FORMULARIO PARA COMPLETAR DESDE DETALLE --}}
                        <form action="{{ route('tasks.updateStatus', $tarea->id) }}" method="POST" class="me-3">
                            @csrf
                            @method('PATCH')
                            <input type="checkbox" 
                                   class="form-check-input shadow-none cursor-pointer" 
                                   style="width: 1.5rem; height: 1.5rem;"
                                   onchange="this.form.submit()" 
                                   {{ $tarea->status ? 'checked' : '' }}>
                        </form>
                        <h4 class="mb-0 fw-bold {{ $tarea->status ? 'text-decoration-line-through text-muted' : '' }}">
                            {{ $tarea->title }}
                        </h4>
                    </div>
                    
                    {{-- Badge de Estatus Dinámico --}}
                    <span class="badge {{ $tarea->status ? 'bg-success' : 'bg-light text-dark border' }} rounded-pill px-3 py-2">
                        <i class="bi {{ $tarea->status ? 'bi-check-circle-fill' : 'bi-clock' }} me-1"></i>
                        {{ $tarea->status ? 'Completada' : 'Pendiente' }}
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase small fw-bold mb-2">Descripción del pendiente:</h6>
                        <div class="p-3 bg-light rounded shadow-sm italic">
                            {{ $tarea->description ?: 'Esta tarea no cuenta con una descripción detallada.' }}
                        </div>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="card border-0 bg-light p-3">
                                <h6 class="text-muted text-uppercase small fw-bold mb-1">Prioridad / Categoría:</h6>
                                <div>
                                    {{-- El texto cambia de color según la categoría --}}
                                    <span class="fw-bold text-{{ $colorCategoria }}">
                                        <i class="bi bi-flag-fill me-1"></i> {{ $tarea->category->name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card border-0 bg-light p-3">
                                <h6 class="text-muted text-uppercase small fw-bold mb-1">Registrada el:</h6>
                                <p class="mb-0"><i class="bi bi-calendar3 me-1"></i> {{ $tarea->created_at->format('d M, Y - h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

               <div class="card-footer bg-white d-flex justify-content-between py-3 border-top">
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver al Dashboard
    </a>
    
    <div class="d-flex align-items-center">
        <a href="{{ route('tasks.edit', $tarea->id) }}" class="btn btn-primary me-3">
            <i class="bi bi-pencil-square"></i> Editar Tarea
        </a>

        <form action="{{ route('tasks.destroy', $tarea->id) }}" method="POST" onsubmit="return confirm('¿Estás completamente seguro de que deseas eliminar esta tarea?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-trash"></i> Eliminar Tarea
            </button>
        </form>
    </div>
</div>
            </div>
        </div>
    </div>
</div>
@endsection