@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Crear Cuenta</h4>
                </div>
                <div class="card-body">
<!--En esta parte estamos añadiendo un id al formulario que nos servira para que jquery nos permita escuchar a este formulario con dicho id -->
                    <form id="registerForm">
<!--Agregamos el csrf para obtener un token unico que se guarda en la sesion actual y al enviar este formulario manda si los datos pero tambien este token de seguridad donde se compara el token de sesion que se manda con el que se guardo en la sesion-->
                        @csrf <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej. Eduardo Quevedo">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto de Perfil</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                    </form>
<!--Agregamos este div que es un enlace que apunta a nuestra ruta de login en caso de ya te hayas registrado-->
                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!--Con push scripts estamos colocando este script en  nuestro archivo app.blade para que funcionen el codigo jquery y ajax que pusimos aqui-->
@push('scripts')
    <script src="{{ secure_asset('js/auth.js') }}"></script>
@endpush