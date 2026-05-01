<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importante para el login
use Illuminate\Support\Facades\Validator; // Para validar los datos
use App\Models\User;//Importamos el modelo de datos
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Método para mostrar la vista del login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Método que procesará la petición AJAX
    public function login(Request $request)
    {
        // 1. Validamos que lleguen los datos
        //Creamos un objeto de tipo Validator donde le pasamos los datos de nuestro formulario a travez de request que es un array asociativo.
        //este objeto nos permite validar en este caso si el campo email es required es decir si tiene algo y ademas comprueba que sea de tipo email, de igual manera el campo password tambien no puede quedar vacio
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //Aqui agregamos otra validacion con ayuda del bloque if donde estamos preguntando si validator fails da true es decir existe un error en la validacion nos retornara un mensaje de error
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Por favor, llena todos los campos correctamente.'
            ], 422);
        }

        // 2. Intentamos iniciar sesión (Attempt)
        $credentials = $request->only('email', 'password');
//Auth::attemp comprueba que exista un usuario con el mismo correo que la pasamos en credentials y posteiormente verifica que su contraseña crifrada sea igual a la de la base de datos, al hacer esto tambien crea un registro en la tabla sesions donde se genera un id cifrado relacionado con el usuario que se logeo y guarda esta sesion en la cokie 
        if (Auth::attempt($credentials)) {
            // Generamos de nuevo la sesión para evitar ataques de fijación
            //Aqui estamos modificando el id de sesion de invitado que se nos da por defecto por otro para que sea mas seguro, esta modificacion solo se hace si nuestras credenciales son correctas
            $request->session()->regenerate();
//Si todo sale bien lo que haremos sera retornar un json con un mensaje de bienvenida y una ruta hacia la vista dashbboard
            return response()->json([
                'status' => 'success',
                'message' => '¡Bienvenido al sistema!',
                'redirect' => route('dashboard') // Ruta a donde irá al entrar
            ]);
        }

        // 3. Si falla el intento
        return response()->json([
            'status' => 'error',
            'message' => 'Las credenciales no coinciden con nuestros registros.'
        ], 401);
    }

    // Método para cerrar sesión
    public function logout(Request $request)
    {
        //Auth::logout lo que hace es que borra el usuario vinculado user_id de la tabla sessions
        Auth::logout();
        //$request->session()->invalidate(); lo que hace es borrar ese registro de sessions y limpia los datos asociados a esta sesion
        $request->session()->invalidate();
        // $request->session()->regenerateToken(); lo que hace es eliminar mi token que se crea automaticamente para enviar formularios y crea uno nuevo asi evitando que se puedan hacer acciones en mi nombre con mi token
        $request->session()->regenerateToken();
//Una vez que todo esta hecho nos redirige a la ruta base
        return redirect('/');
    }
//Metodo para ir a la vista auth.resgister
    public function showRegister()
{
    return view('auth.register');
}
//Este metodo register nos pide de parametro datos de tipo Request es decir los datos de nuestro formulario
public function register(Request $request)
{
    // 1. Validamos los datos
    //request es donde nos llegan todos los datos 
    //Creamos un objeto de tipo Validator donde nos permite validar nuestros campos, de name vericamos que no este vacio con required debe ser tipo string y tener un maximo de 255 caracteres, email tambien debe tener algo no puede estar en blanco debe ser de tipo string de tipo email tener un maximo de 255 caracteres y ser unique en la base de datos, la contraseña tambien debe ser requerid es decir debe tener algo, debe ser string tener como minimo 8 caracteres y confirmed buscara el campo password_confirmation y comprobara que sean iguales sino dara error
    $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed', // 'confirmed' busca un campo password_confirmation y verificaque sean iguales
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,avif|max:2048',
    ]);

    //Con este if comprobamos que si validator->fails da true es decir tenemos un error en alguna validacion nos retornara este json con un mensaje de error 
    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors() // Enviamos todos los errores para que jQuery los muestre
        ], 422);
    }

    // 2. Procesamos la imagen (Aquí va tu código)
    $imageName = null; // Por defecto es null por si no suben foto
//Con este if comprobamos si hay algo en el campo profile_image si es asi dara true y entramos al if
    if ($request->hasFile('profile_image')) {
        //Guardamos el arhcivo en nuestra variable file
        $file = $request->file('profile_image');
        
        // Creamos el nombre único: 1712345678_mi_foto.jpg
        $nameUnique = time() . '_' . $file->getClientOriginalName();
        
        // Guardamos físicamente en storage/app/public/users
        $file->storeAs('', $nameUnique, 'users');
        
        // Asignamos el nombre para guardarlo en la DB
        $imageName = $nameUnique;
    }

    // 2. Creamos el usuario usando Eloquent donde seteamos las propiedades del objeto con las que nos llegan del formulario
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password), // Encriptación segura encriptamos la contraseña que nos llega del formulario
        'profile_image' => $imageName,
    ]);

    // 3. Iniciamos sesión automáticamente después de registrar
    Auth::login($user);
//Generamos un nuevo id mas seguro para iniciar sesion
    $request->session()->regenerate();
//Por ultimo retornamos una respuesta con un mensaje de que nos registramos correctamente y colocamos una ruta 'redirect' => route('dashboard') para ir a la vista dashboard despues de recibir nuestra respuesta
    return response()->json([
        'status'  => 'success',
        'message' => 'Usuario registrado exitosamente',
        'redirect' => route('dashboard')
    ]);
} 
}
