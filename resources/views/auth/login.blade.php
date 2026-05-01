@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4"> <div class="card shadow border-0">
                <div class="card-header bg-success text-white text-center">
                    <h4>Iniciar Sesión</h4>
                </div>
                <div class="card-body">
<!--Aqui agregamos este id para que jqeury este pendiente de su evento submit-->
                    <form id="loginForm">
<!--Colocamos el csrf de seguridad para que envie un token unico al enviar los datos y verificar que coincida con el que se guardo en la sesion-->
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Entrar</button>
                    </form>
                    
                    <div class="mt-3 text-center">
<!--En esta parte colocamos la ruta que definimos en web para poder ir a a la vista regiter-->
                        <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate aquí</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!--Con push scripts estamos colocando este script en  nuestro archivo app.blade para que funcionen el codigo jquery y ajax que pusimos aqui-->
@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush