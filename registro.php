<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("conexion.php");
$success = false;
    $errores = array();

    // Obtener datos del formulario
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
    $apellido = isset($_POST["apellido"]) ? $_POST["apellido"] : null;
    $correo = isset($_POST["email"]) ? $_POST["email"] : null;
    $clave = isset($_POST["password"]) ? $_POST["password"] : null;
    $clave_repetida = isset($_POST["password_repeat"]) ? $_POST["password_repeat"] : null;

    // Validación básica de campos
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($clave) || empty($clave_repetida)) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    // Verificar que las contraseñas coincidan
    if ($clave !== $clave_repetida) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Si no hay errores, insertar en la base de datos
    if (empty($errores)) {
        try {
            // Encriptar la contraseña antes de guardarla
            $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

            // Preparar la consulta SQL
            $sql = "INSERT INTO usuario (nombre, apellido, correo, clave) VALUES (:nombre, :apellido, :correo, :clave)";
            $stmt = $conn->prepare($sql);

            // Asignar los valores a los parámetros
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':clave', $clave_encriptada);

            // Ejecutar la consulta
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en el registro: " . $e->getMessage();
        }
    } else {
        $success=true;
        foreach ($errores as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }

    // Cerrar la conexión
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Registro</title>
</head>
<body>
  <?php if(isset($success)){?>
    <div id="alert-3" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <div class="ms-3 text-sm font-medium">
        ¡Regisro guardado con exito! <a href="login.html" class="font-semibold underline hover:no-underline"> Ir a Login</a>. 
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
          <span class="sr-only">Close</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
      </div>
    <?php } ?>
      <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
          <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/plus/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
          <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign in to your account</h2>
        </div>
      
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
          <form class=" grid grid-cols-2 gap-x-2 gap-y-6" action="registro.php" method="POST">
            <div class="col-span-2 md:col-span-1">
                <label for="nombre" class="block text-sm/6 font-medium text-gray-900">Nombre</label>
                <div class="mt-2">
                  <input id="nombre" name="nombre" type="text" autocomplete="off" required class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
                </div>
              </div>
              <div class="col-span-2 md:col-span-1">
                <label for="apellido" class="block text-sm/6 font-medium text-gray-900">Apellido</label>
                <div class="mt-2">
                  <input id="apellido" name="apellido" type="text" autocomplete="off" required class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
                </div>
              </div>
            <div class="col-span-2">
              <label for="email" class="block text-sm/6 font-medium text-gray-900">Direccin de Correo</label>
              <div class="mt-2">
                <input id="email" name="email" type="email" autocomplete="off" required class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
              </div>
            </div>
      
            <div class="col-span-2">
              <div class="flex items-center justify-between">
                <label for="password" class="block text-sm/6 font-medium text-gray-900">Contraseña</label>
              </div>
              <div class="mt-2">
                <input id="password" name="password" type="password" autocomplete="off" required class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
              </div>
            </div>
            <div class="col-span-2">
                <div class="flex items-center justify-between">
                  <label for="password_repeat" class="block text-sm/6 font-medium text-gray-900">Repita la Contraseña</label>
                </div>
                <div class="mt-2">
                  <input id="password_repeat" name="password_repeat" type="password" autocomplete="off" required class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
                </div>
              </div>
            <div class="col-span-2">
              <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Registrar</button>
            </div>
          </form>

        </div>
      </div>
</body>
</html>