@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3">Configuración del Perfil</div>
                <div class="card-body p-4">
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
<!--Esta parte enctype="multipart/form-data es vital para que el formulario envie correctamente la imagen con sus propieades, es importante notar que el formulario al hacer click en el boton de actulizar invicara la ruta para actulizar el perfil -->
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
<!--Usamos csrf para poder generar un token de auteticacion para el formulario-->
                        @csrf
<!--Los navegadores solo entienden GET y POST. Como para actualizar datos lo correcto en estándares web es usar PUT, Laravel usa este comando para "engañar" al servidor y que sepa que es una actualización.-->
                        @method('PUT')
                        <div class="text-center mb-4">
<!--Para visualizar la imagen de usuario en la ruta hcaemos un if donde preguntamos user->profile image tiene algo si es asi muestra esta ruta asset('storage/users/' . $user->profile_image si no muestra esta 'https://ui-avatars.com/api/?name='.urlencode($user->name) es una api externa que nos genera una imagen con las iniciales del usuario -->
                            <img src="{{ $user->profile_image ? asset('storage/users/' . $user->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                                 class="rounded-circle img-thumbnail shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="mt-2">
                                <label class="form-label small text-muted">Cambiar foto de perfil</label>
<!--Colocamos un input de tipo file llamado profile imagen para poder subir la nueva imagen de perfil del usuario-->
                                <input type="file" name="profile_image" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
<!--Aqui tenemos el input perteneciente al nombre del usuario este formulario es su propiedad value tiene old('name', $user->name) esta propiedad sirve primero para mostrarnos el nombre actual del usuario en su propiedad user->name pero con old name en caso de que nosotros hayamos cambiado los valores de los inputs por nuevos y alguno nos de error esto nos devolveria a la vista del formulario con los valores antiguos osea borraria los cambios que hicimos pero con old esto no pasa si pasa esto el formulario volveria pero recordaria los valores que ya previamente habiamos colocado-->
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
<!--Nuestro input de email tambien tiene la propiedad old que recuerda los valores de nuestros inputs en caso de un error haciendo mas facil rellenar un formulario y corregirlo en caso de un error-->
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection