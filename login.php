<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("conexion.php");

    $correo = isset($_POST["email"]) ? $_POST["email"] : null;
    $clave = isset($_POST["password"]) ? $_POST["password"] : null;
    $errores = array();

    // Validación básica
    if (empty($correo) || empty($clave)) {
        $errores[] = "Por favor ingrese su correo y contraseña.";
    }

    // Si no hay errores, proceder con la verificación de credenciales
    if (empty($errores)) {
        try {
            // Preparar la consulta para buscar el usuario en la base de datos
            $sql = "SELECT * FROM usuario WHERE correo = :correo LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();

            // Verificar si se encontró un usuario
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar la contraseña
                if (password_verify($clave, $usuario['clave'])) {
                    // Inicio de sesión exitoso
                    echo "Inicio de sesión exitoso";

                    // Aquí puedes iniciar una sesión y almacenar los datos del usuario
                    session_start();
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['nombre'] = $usuario['nombre'];

                    // Redirigir al usuario a otra página si es necesario
                    header("Location: index.php");
                    exit();
                } else {
                    // Contraseña incorrecta
                    $errores[] = "Contraseña incorrecta.";
                }
            } else {
                // Usuario no encontrado
                $errores[] = "No se encontró una cuenta con ese correo.";
            }
        } catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
        }
    }

    // Mostrar errores
    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }

    // Cerrar la conexión
    $conn = null;
}
?>
