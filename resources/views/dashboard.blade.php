@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-white p-3">
            <div class="d-flex align-items-center">
                <div class="me-3">
<!--Con ayuda de blade lo que hacemos aqui es comprobar mediante un bloque if y mediante Auth::user obtener el usuario logueado pero mas importante con ->profile imagen verificar es este campo si tenga algo si es asi entramos al if-->
                    @if(Auth::user()->profile_image)
<!--En esta parte agregamos una etiqueta img donde colocamos la ruta donde estas nuestras imagenes guardadas y con ayuda de Auth::user()->profile_image obtendremos la imagen correspondiente a cada usuario -->
                        <img src="{{ asset('storage/users/' . Auth::user()->profile_image) }}" 
                             class="rounded-circle shadow-sm" 
                             style="width: 80px; height: 80px; object-fit: cover;">
<!--En caso de que no haya nada el profile_image entraremos a este else-->
                    @else
<!--En caso de no tener imagen entonces apuntamos a la ruta https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" que es una pagina externa a laravel donde gracias a que le pasamos el nombre del usuario nos genera una imagen cuadrada con las iniciales del usuario logueado y con los estilos de boostrap la hacemos circular -->
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" 
                             class="rounded-circle shadow-sm" 
                             style="width: 80px; height: 80px;">
                    @endif
                </div>
                <div>
                    <div class="dropdown">
<!--En este h3 mostramos el nombre de nuestro usuario logueado gracias a blade y a {{ Auth::user()->name }} -->
<!--La propiedad dropdown-toggle es una clase de boostrap que dice este elemento controla un dropdown, data-bs-toggle="dropdown es de botstrap tambien y lo que hace es que cuando hagan click aqui abre el menu desplegable, id userMenu sirve para conectar con el menu" -->
                        <h3 class="mb-0 dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                            ¡Hola, {{ Auth::user()->name }}!
                        </h3>
<!--aria-labelledby="userMenu" le dice este menú pertenece al elemento con id userMenu-->
                        <ul class="dropdown-menu shadow border-0" aria-labelledby="userMenu">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit')}}">
                                    <i class="bi bi-person-gear me-2 text-primary"></i> Editar Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.security') }}">
                                    <i class="bi bi-shield-lock me-2 text-warning"></i> Seguridad / Contraseña
                                </a>
                            </li>
                        </ul>
                    </div>
                    <p class="text-muted mb-0">Gestiona tus pendientes de hoy.</p>
                </div>
<!--Aqui tenemos nuestro enlace para ir al formulario que nos permite crear una nueva tarea donde nuestro enlace apunta a {{ route('tasks.create') }}-->
                <div class="ms-auto d-flex align-items-center">
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary shadow-sm me-2">
                        <i class="bi bi-plus-lg"></i> + Nueva Tarea
                    </a>
<!--Aqui tenemos nuestro boton de tipo submit para cerrrar sesion este boton esta envuelto en un formulario donde al hacer click en el formulario invoca la ruta route(logout) por post que esta apunta anuestro metodo logout para cerrar la sesion-->
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger shadow-sm">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row">
<!--Aqui primeramente dentro del foreach creamos un array que contiene el nombre de las 4 categorias que queremos tener y con ayuda del foreach recorremos cada una de ellas con esto logramos crear 4 columnas cada una con el nombre de la categoria que definimos aqui en el array-->
        @foreach(['Urgente', 'Prioritario', 'Normal', 'Baja'] as $catNombre)
            @php
                // 1. Asignamos el color según la categoría
                //aqui lo que hacemos es que guardamos en la variable color el nombre de la clase que se defini en base a que valor trae catNombre si catNombre trae Urgente se pondra de color rojo y asi para las demas categorias despues usamos esta variable color para dar color al titulo de la cateria su borde y su mensaje de status se podra de ese color ya que color traer dentro suyo en color o la clase de bootstrtrap para dar color por ejemplo si es Urgente color trae danger correspondinete al color rojo en botstrap
                $color = match($catNombre) {
                    'Urgente'     => 'danger',    // Rojo
                    'Prioritario' => 'warning',   // Naranja/Amarillo
                    'Normal'      => 'success',   // Verde
                    'Baja'        => 'secondary', // Gris
                    default       => 'info'
                };
            @endphp

            <div class="col-md-3 mb-4">
<!--color trae el color con la clase de bootstrap correpondiente-->
                <h6 class="text-uppercase fw-bold text-{{ $color }} mb-3">
                    <i class="bi bi-circle-fill me-1"></i>
                    {{ $catNombre }}
                </h6>
                
                <div class="card bg-light border-0 shadow-sm" style="min-height: 400px;">
                    <div class="card-body p-2">
<!--Con este bloque if comprobamos que existan datos en la llave catNombre(cat nombre puede tener el valor de Urgente, prioritario, normal o bajo dependiendo de la iteracion), si se cumple entra al bloque if  -->
                        @if(isset($tareasAgrupadas[$catNombre]))
<!--Si paso la validacion  entonces ahora abrimos otro foreach donde lo recorremos pero solo recorremos la llave catNombre(cat nombre puede tener el valor de Urgente, prioritario, normal o bajo dependiendo de la iteracion) donde la variable tarea recorre esta seccion del array asociativo-->
                            @foreach($tareasAgrupadas[$catNombre] as $tarea)
<!--Aqui usamos color que contiene el color o la clase de bootstrap para dar color-->
                                <div class="card mb-2 border-0 shadow-sm border-start border-4 border-{{ $color }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="{{ $tarea->status ? 'text-decoration-line-through text-muted' : 'fw-bold' }}">
<!--Aqui mostramos el titulo de la tarea-->
                                                {{ $tarea->title }}
                                            </div>
<!--Aqui tenemos el enlace de detalles de la tarea que apunta a la vista de detalles y se lleva el id de la tarea esto con el fin de mostrar la tarea especifica-->
                                            <a href="{{ route('tasks.show', $tarea->id) }}" class="btn btn-sm text-info p-0 me-2" title="Ver Detalle">
                                        <i class="bi bi-eye"></i> 👁️
                                            </a>
<!--Aqui tenemos el enlace para editar nuestra tarea y se lleva el id de la tarea actual para editar la tarea con precision-->
                                          <a href="{{ route('tasks.edit', $tarea->id) }}" class="btn btn-sm btn-outline-primary border-0 me-1">
                                           ✏️ </a>
<!--Aqui en el boton borrar esta dentro de un formulario que va por post y tiene un alert de confirmacion antes de borrar la tarea este formulario apunta a la ruta task.destroy y se lleva de parametro el id de tarea-->
        <form action="{{ route('tasks.destroy', $tarea->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Borrar tarea?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm p-0 me-2" title="Eliminar" style="border: none; background: none;">
                🗑️ </button>
        </form>

<!--Este check sirve para marcar si la tarea esta completada o sigue pendiente este check esta dentro de un formulario que al palomearlo ira al metodo submit que asu este llama a la ruta tasks.updateStatus y se lleva de parametro el id de la tarea donde con esto actuliza el campo status de 0 false a true 1 -->
                                            <form action="{{ route('tasks.updateStatus', $tarea->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
<!--Este input sera de tipo checkbox, la propiedad onchange="this.form.submit()" significa y nos sirve que en cuanto el checkbox sufra un cambio envie el formulario, posteriormente con ayuda de {{ $tarea->status ? 'checked' : '' }}> es un if donde si status da true es decir es 1 lo que mostrara sera como marcado el checkbox o en caso contrario que sea false o 0 lo mostrara vacio o desmarcado -->
                                                <input type="checkbox" 
                                                       class="form-check-input shadow-none cursor-pointer" 
                                                       onchange="this.form.submit()" 
                                                       {{ $tarea->status ? 'checked' : '' }}>
                                            </form>
                                        </div>
<!--Aqui mostramos  la descripcion de la tarea pero esta esta limitada a 40 caracteres gracias Str limit -->
                                        <div class="text-muted small mt-1">{{ Str::limit($tarea->description, 40) }}</div>
<!--Aqui en este contenedor div tenemos el mensaje de status donde si status tiene 1 es decir es true me mostrara el texto completada si es 0 false y mostrara pendiente-->
                                        <div class="mt-2">
                                            <span class="badge {{ $tarea->status ? 'bg-success' : 'bg-'.$color.' text-'.($color == 'warning' ? 'dark' : 'white') }} rounded-pill" style="font-size: 0.7rem;">
                                                {{ $tarea->status ? 'Completada' : 'Pendiente' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
<!--Fin del foreach de las llaves ya sea Urgente, Prioritario o en la iteracion en la que se encuentre-->
                        @else
<!--En caso de que nuestro if haya dado false quiere decir que no hay tareas en la seccion y mostrara este mensaje que dice sin tareas-->
                            <div class="text-center py-5">
                                <p class="text-muted small">Sin tareas</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
        <!--Fin del foreach que crea las 4 columnas y que contiene cada tarea en su respectiva categoria-->
    </div>
</div>
@endsection