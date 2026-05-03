<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- PEGA ESTA LÍNEA AQUÍ ABAJO -->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Tareas Hub - Laravel 12</title>
<!--Importamos los estilos de bootstrap 5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!--yield nos sirve para poder colocar las diferentes vistas en esta parte -->
    @yield('content')
<!--Colocamos nuestro script para que funcione jquery-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!--Contiene la lógica para que funcionen los menús desplegables (dropdowns) y las alertas de Bootstrap.-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!--stack nos permite acumular otros scripts que vengan en las diferentes vistas-->
    @stack('scripts') 
</body>
</html>