<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ProfileController extends Controller
{
    // Mostrar formulario de edición de datos básicos
    public function edit(Request $request)
    {
        
        //Este metodo retornara una vista pero tambien le mandamos el usuario logueado
        return view('profile.edit', ['user' => $request->user()]);
    }

    // Actualizar nombre, correo y foto
    public function update(Request $request)
    {
        //Primero obtengo los datos del usuario logueado gracias a Auth::user.
      $usuarioLogueado = Auth::user();
      //Posteriormente creo un objeto de tipo user donde obtenmos el registro del usuario con el id del usuario logueado
       $user = User::find($usuarioLogueado->id);

//Validamos los datos que nos vienen del formulario donde defimos que los campos obliatorios son name y email ademas que deben ser de tipo string, la imagen puede ser null y agregamos los tipos de formato aceptados
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,//En este parte como el email no puede estar repetido 
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Manejo de la nueva imagen
        //Comprobamos que el input de tipo file tenga si tenga algo si es asi entramos al if
        if ($request->hasFile('profile_image')) {
            // Borrar imagen anterior si existe y no es la default
            //Con otro if comprobamos que la base de datos tenga algo si es asi entramos al if para borrar ese registro
            if ($user->profile_image) {
                Storage::disk('users')->delete($user->profile_image);
            }
//Una vez borrada la imagen anterior guardamos en file la imagen que le pasamos el formluario
            $file = $request->file('profile_image');
            //Ahora en nameUnique guardamos el nombre de la nueva imagen donde gracias a la funcion time concatenada con el nombre actual de la imagen generamos un nombre unico para cada archivo evitando que se sobreescriban las imagenes
            $nameUnique = time() . '_' . $file->getClientOriginalName();
            //aqui invocamos el metodo storeAs propio del objeto que nos permite guardar fisicamente nuestra imagen donde de parametro le pasamos el nombre unico que acabamos de generar y users es el disco donde se guardara la iamgen, ojo el primer parametro que dejamosmvacio podemos poner el nombre de una carpeta pero en este caso lo dejamos vacio
            $file->storeAs('', $nameUnique, 'users');
            //Aqui seteamos el valor de nuestro objeto user en el campo profile_image con el nombre de nuestra imagen
            $user->profile_image = $nameUnique;
        }
//Ahora aqui seteamos las propiedades name y email con los valores que vienen de nuestro formulario
        $user->name = $request->name;
        $user->email = $request->email;
        //Con la funcion save guardamos los cambios hechos
        $user->save();
        //retornamos ahora un mensaje donde indicamos que el ferli se actualizo correctamente
        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    // Mostrar formulario de cambio de contraseña
    public function security()
    {
        return view('profile.security');
    }

    // Lógica para cambiar contraseña con validación de contraseña actual
    public function updatePassword(Request $request)
    {
        //Ahora validamos los campos que vienen del formulario donde el campo current pasword es obligatorio y verifica quecoincida con el del base de datos
        $request->validate([
            'current_password' => ['required', 'current_password'], // Valida que la clave coincida con la DB al colocar current_password dentro de los llaves lo que hace es verificar que la contraseña actual en base de datos sea igual a la que ingreso
            //Y el campo password debe ser required es decir no puede estar vacio con confirmed en automatico laravel busca otro campo con el nombre password?confirmation si los campos no tienen exactamente el mismo texto la validacion falla, Password::defaults(): Esta es una forma elegante de aplicar las reglas de seguridad globales que hayas definido en tu proyecto (por ejemplo: que tenga al menos 8 caracteres, una mayúscula, un número, etc.).
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            //ahora con current_password en caso de un error mostramos el mensaje de contraseña incorrecta 
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            //Y si el error de la validacion viene de password confirmed mostramos este mensaje al usuario
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.'
        ]);
        //con request obtenemos el usuario logueado y con su funcion update actualizamos la contraseña con la nueva que viene del formulario claro y las ciframos con Hash
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);
        //ahora simplemente valos a retornar un mensaje que diga ¡Seguridad actualizada! Tu contraseña ha sido cambiada.
        return back()->with('success', '¡Seguridad actualizada! Tu contraseña ha sido cambiada.');
    }
}
