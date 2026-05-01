@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3"><i class="bi bi-shield-lock me-2"></i>Seguridad de la Cuenta</div>
                <div class="card-body p-4">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Contraseña Actual</label>
<!--error('current_password'): Es un condicional. Le pregunta a esa "bolsa de errores" de Laravel: "¿Hay algún problema con el campo 'current_password'?", is-invalid: Si la respuesta es SÍ, Blade escribe esta clase de Bootstrap. Es la que pone el borde del input en rojo.@enderror: Cierra el condicional.-->
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Ingresa tu clave actual" required>
<!--Cuando estás dentro de un bloque @error, Laravel te regala una variable temporal llamada $message.Esta variable contiene el texto exacto del error (por ejemplo: "La contraseña actual es incorrecta" o "La confirmación no coincide").invalid-feedback: Es una clase de Bootstrap que hace que el texto salga en letras pequeñas y rojas. Solo es visible si el input anterior tiene la clase is-invalid.-->
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 8 caracteres" required>
<!--Aqui lo que que hacemos con @error('password') es preguntar existe un error en password si es asi metemos la variable message que contiene el error en un div es como un if que comprueba que existe un error por ultimo enderror cierra el if digamos -->
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la nueva clave" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            {{-- Botón Volver --}}
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>

                            {{-- Botón Guardar (quitamos el w-100 para que no ocupe todo el ancho) --}}
                            <button type="submit" class="btn btn-warning fw-bold px-4">
                                <i class="bi bi-shield-check me-1"></i> Actualizar Contraseña
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection