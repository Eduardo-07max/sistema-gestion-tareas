//Le estamos diciendo que al navegador que antes de ejecutar el codigo que tenemos dentro esperemos a que se termine de dibujar el html
$(document).ready(function() {
    
    // Nos quedamos escuchando el evento submit de nuestro formulario register form
    $('#registerForm').on('submit', function(e) {
        //Por defecto al enviar los datos de un formulario se refresca la pagina pero con  e.preventDefault(); detiene este comportamiento para manejarlo nosotros con ajax
        e.preventDefault();
//con esta contsnate formData estamos tomando todos los inputs
        let formData = new FormData(this);

        $.ajax({
            url: "/registro", // Vamos a nuestra ruta registro que esta en web
            method: "POST",// Seleccionamos la que es por post
            data: formData,// Le pasamos el valor de nuestros inputs a travez de un formData
            //Si todo sale bien en mi peticion se ejecutara la funcion success y hacemos uso de la funcion de callback  y dentro le pasamos el parametro response
            contentType: false, // OBLIGATORIO para enviar archivos
            processData: false, // OBLIGATORIO para enviar archivos
            success: function(response) {
                //Con el bloque if evaluamos una condicion donde estamos preguntando en este caso si response.status es igual success entonces ejecutara lo que esta en este bloque if
                if(response.status === 'success') {
                    //Aqui estamos mostrando un mensaje de alerta y el mensaje que mostrara sera justamente el texto que hay el la propiedad response.message
                    alert(response.message);
                    //Por ultimo nos redireccionamos a la ruta response.redirect que apunta a la vista dasboarh
                    window.location.href = response.redirect;
                }
            },
            //Si algo llego a fallar se ejecuta error donde de igual manera con una funcion de callback esperamos nuestra respuesta que en este caso es xhr que contiene informacion sobre el tipo de error que surgio
            error: function(xhr) {
                manejarErrores(xhr);
            }
        });
    });

    // Manejo del formulario de Login
    $('#loginForm').on('submit', function(e) {
         //Por defecto al enviar los datos de un formulario se refresca la pagina pero con  e.preventDefault(); detiene este comportamiento para manejarlo nosotros con ajax
        e.preventDefault();
//Tomamos el valor de los inputs donde los convertimos en una cadena de texto
        let formData = $(this).serialize();

        $.ajax({
            url: "/login", // Esta es la ruta que definimos en web.php
            method: "POST",
            data: formData,
            success: function(response) {
                if(response.status === 'success') {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                manejarErrores(xhr); // Usamos la misma función de errores que ya explicamos
            }
        });
    });

    // Función reutilizable para mostrar errores
    //Creamos nuestra funcion llamada manejarErrores la cual nos servira para saber que tipo de error es el que surgio, esta funcion recibira un parametro llamado xhr
    function manejarErrores(xhr) {
        //Esta funcion lo primero que hace es que mediante un if pregunta la propiedad xhr.status es igual a 422 quiere decir que es un error de validacion 
        if(xhr.status === 422) {
            //Si se cumple la condicion lo que se hara sera Dentro de xhr.responseJSON.errors Laravel nos envía un objeto con todos los problemas. Por ejemplo: { "email": ["El correo ya está en uso"], "password": ["La contraseña es muy corta"] }
            let errors = xhr.responseJSON.errors;
            //Declaramos una variable para mostrar los errores
            let errorMsg = "";
            //Con ayuda de jquery vamos a hacer un bucle each donde vamos a recorrer los campos email, password, etc, donde key representa cada campo y value el error
            $.each(errors, function(key, value) {
                //Aqui vamos a concatenar cada error que hubo en cada campo en nuestra variable errorMsg y le agregamos un salto de linea con /n para evitar que los errores se amontonnen
                errorMsg += value[0] + "\n";
            });
            //Una vez que los errores ya estan en nuestra variable lo que hacemos es que mostramos esos errores en un alert 
            alert(errorMsg);
            //Seguimos con el elseif donde ahora preguntamos si la propiedad status es igual a 401
        } else if(xhr.status === 401) {
            //si se cumple la condicion quiere decir que hay un error en especifico el error 401 se refiere a que si tenemos el correo correcto pero la contraseña es incorrecta  y con responseJSON.message lo que hay de error es simplemente un mensaje que dira Credenciales Incorrectas. que se mostrara en un alert
            alert(xhr.responseJSON.message);
            //Ahora en caso de que el error no sea ninguno de los anteriores entrara a este else y mostrare el alert con el texto ocurrio un error inesperado
        } else {
            alert("Ocurrió un error inesperado.");
        }
    }
});