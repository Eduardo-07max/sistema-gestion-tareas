<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Tareas;

class TaskController extends Controller
{
    public function index()
    {
        //Obtenemos el usuario logueado
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // USAMOS la variable $user que ya tiene el "permiso" del editor
        //$user->tareas-with significa traeme todas las tareas junto a la informacion de sus categorias correspondientes que pertenecen al usuario logueado
        //groupBy va a agrupar las tareas e base al nombre de la categoria donde con ayuda de nuestra funcion de callback donde el parametro item toma el valor de cada tarea nos retornara cada tarea guardandola en el array asociativo tareasAgrupadas donde evaulara donde ponerla o agruparla en base al nombre de su categoria
        $tareasAgrupadas = $user->tareas()->with('category')->get()->groupBy(function($item) {
            return $item->category->name;
        });

        
//Ahora simplemente retornamos la vista dashboard donde le pasamos tambien nuestra variable tareasAgrupadas que es un array asociativo con nuestras categorias y tareas correspondientes a cada categoria
        return view('dashboard', compact('tareasAgrupadas'));
    }

    // 1. Método para mostrar la vista del formulario
public function create()
{
    // Obtenemos solo las 4 categorías de la base de datos
    $categories = Category::all();
    //Esta funcion nos retornara una vista y se llevara las 4 categorias que tenemos en la base de datos
    return view('tasks.create', compact('categories'));
}

// 2. Método para guardar una tarea
public function store(Request $request)
{
    //Validamos los datos que nos llegan por request en este caso validamos que el campo title no este en blanco esto lo hacemps con required ademas este campo solo puede tener un maximo de 255 caracteres, el campo categori_id tambien es requered es decir es necesario no puede estar en blanco esto ya que cada tarea debe tener una categoria
    $request->validate([
        'title' => 'required|max:255',
        'category_id' => 'required',
    ]);
    //Obtenemos al usuario logueado
    $user = Auth::user();
//Lo que hacemos aqui es crear un nuevo objeto de tipo Tareas donde de valores en sus propiedades le pasamos en title el titulo que viene del formulario y asi con las propiedades descripcion y category id
    Tareas::create([
        'title' => $request->title,
        'description' => $request->description,
        'category_id' => $request->category_id,
        'user_id' => $user->id,//Le pasamos el id del usuario logueado
        'status' => 0 // 0 es false, queda como pendiente por defecto
    ]);
//Ahora retornamos la vista dashboard junto al mensaje tarea creada
    return redirect()->route('dashboard')->with('success', 'Tarea creada!');
}
//Aqui tenemos nuestra funcion updateStatus que recibe de parametro una tarea al invocar esta funcion lo que hace es siempre cambiar al valor contrario
//El parametro que recibe esta funcion debe ser de tipo tareas o en este caso buscara el id que le pasemos en la ruta lo buscara en nujestro registro de tareas para obtener y inyectar a la funcion el registro que coincida con ese id
public function updateStatus(Tareas $tarea)
{
    // Cambiamos al valor contrario (si es 0 pone 1, si es 1 pone 0)
    $tarea->status = !$tarea->status;
    //Guardamos la tarea con el cambio realizado
    $tarea->save();
//Retornamos un mensaje 
    return back()->with('success', '¡Estatus actualizado!');
}

//Aqui tenemos la funcion show donde esta funcion reciba un parametro pero esta funcion lo que hara sera buscar en base al id que se le pase en la ruta un registro que coincida con el id de una tarea si es asi si coincide inyectara ese registro a nuestra funcion
public function show(Tareas $tarea)
{
//Obtenemos el usuario logueado
 $user = Auth::user();
//Con este bloque if preguntamos el id de la tarea es diferente al id del usuario logueado si es asi abortamos
    if ($tarea->user_id !== $user->id) {
        abort(403);
    }
//Si todo sale bien continuamos y retornamos la vista donde le pasamos nuestro objeto tarea a dicha vista
    return view('tasks.show', compact('tarea'));
}


// 1. Mostrar el formulario con los datos cargados
//Esta funcion recibe de parametro un id y como tenemos que este sera Tareas es decir buscara en la base de datos en la tabla tareas un id que coincida con el que le pasmos en la ruta inyectara dicho registro a la funcion 
public function edit(Tareas $tarea)
{
    //Obtenemos al usuario logueado
    $user = Auth::user();
    // Verificamos que la tarea pertenezca al usuario
    if ($tarea->user_id !== $user->id) {
        abort(403);
    }
//si todo sale bien obtenemos las 4 categorias
    $categories = Category::all();
    //Para finalizar retornamos una vista donde le pasamos el objeto tareas y las categorias a esa vista
    return view('tasks.edit', compact('tarea', 'categories'));
}

// 2. Procesar la actualización de los datos
//Esta funcion nos permite recibir los datos del formulario de editar tarea y tambien necesita un id de parametro en la ruta para poder obtener el registro en especifico a actualizar que es Tareas y como ya sabemos nos inyectara ese objeto en esta funcion
public function update(Request $request, Tareas $tarea)
{
    //Obtenemos al usuario logueado 
    $user = Auth::user();
    //Comprobamos que la tarea le pertenece al usuario logueado
    if ($tarea->user_id !== $user->id) {
        abort(403);
    }
//Validamos los datos del formulario title es required es decir no puede quedar en blanco y debe tener un maximo de 255 caracteres
    $request->validate([
        'title' => 'required|max:255',
        'category_id' => 'required',
    ]);
//Aqui seteamos el objeto tareas con los valores de los inputs la funcion update nos permite actualizar estos campos
    $tarea->update([
        'title' => $request->title,
        'description' => $request->description,
        'category_id' => $request->category_id,
    ]);
    //Al terminar nos redirijimos al dashboard y le pasamos el mensaje Tarea actualizada correctamente
    return redirect()->route('dashboard')->with('success', 'Tarea actualizada correctamente');
}
//La funcion destroy nos sirve para eliminar una tarea
//Le tenemos que pasar un parametro a esta funcion en la ruta donde este parametro sera de tipo Tareas donde en automatico buscara en los registros de tareas el registro que tenga el mismo id que se le paso en la ruta y inyectara ese registro en esta funcion
public function destroy(Tareas $tarea)
{
    //Obtenemos el usuario logueado
    $user = Auth::user();
    // Seguridad: solo el dueño puede borrarla
    if ($tarea->user_id !== $user->id ) {
        abort(403);
    }
    //Invocamos la funcion delete de nuestro objeto tarea para eliminar dicho registro
    $tarea->delete();
//Al terminar nos redirigimos al dashboard y le pasmaos el mensaje tarea eliminada correctamente
    return redirect()->route('dashboard')->with('success', 'Tarea eliminada correctamente');
}
}